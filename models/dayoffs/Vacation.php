<?php
declare(strict_types=1);

namespace app\models\dayoffs;

use app\core\AppCore;
use app\interfaces\IDayOff;
use app\models\Model;
use app\models\users\User;
use app\models\users\UserRecord;

/**
 * Class VacationRequest presents logic for handle vacation operations
 * @package app\models\dayoffs
 */
class Vacation extends Model implements IDayOff
{
    /**
     * Shows id of current dayoffs type
     *
     * @var int
     */
    protected static $requestType;

    /**
     * Sets allowed attributes and types of creating
     *
     * @var array
     */
    protected $_attributes = [
        'id' => ['type' => 'int', 'value' => null],
        'user_id' => ['type' => 'int', 'value' => null],
        'start_date' => ['type' => 'string', 'value' => null],
        'finish_date' => ['type' => 'string', 'value' => null],
        'status' => ['type' => 'int', 'value' => null],
        'created_at' => ['type' => 'string', 'value' => null],
        'updated_at' => ['type' => 'string', 'value' => null],
        'updated_by' => ['type' => 'int', 'value' => null],
    ];

    /**
     * Returns collection of VacationRequests from one user
     *
     * @param int $userId
     * @return array of Vacation
     * @internal param array $userData
     * @throws \Exception
     */
    public static function getVacations(int $userId): array
    {
        $vacationsData = RequestsRecord::getRequestsStatuses($userId, self::$requestType);
        $vacations = [];
        foreach ($vacationsData as $item) {
            $vacations[] = new self($item);
        }
        return $vacations;
    }

    /**
     * Returns one vacation object
     *
     * @param int $vacationId
     * @return Vacation
     * @throws \Exception
     */
    public static function findById(int $vacationId): Vacation
    {
        $vacationData = RequestsRecord::getRequestById($vacationId);
        return new self($vacationData);;
    }

    /**
     * Handles operation of updating request
     *
     * @param int $statusCode
     * @param int $daysLeft
     * @return bool
     * @internal param int $vacationId
     * @throws \Exception
     */
    public function changeState(int $statusCode, int $daysLeft): bool
    {
        if (!$this->canBeUpdated($daysLeft)) {
            return false;
        }

        return RequestsRecord::updateRequest($this->id, $statusCode);
    }

    /**
     * Handles operation of creating new request
     *
     * @param \DateTime $startDay
     * @param \DateTime $endDay
     * @return bool
     * @throws \Exception
     */
    public static function createRequest(\DateTime $startDay, \DateTime $endDay): bool
    {
        if (self::isValidRequest($startDay, $endDay)) {
            return RequestsRecord::putRequest($startDay, $endDay, self::$requestType);
        }

        return false;
    }


    /**
     * Checks if new request params are valid
     *
     * @param \DateTime $startDay
     * @param \DateTime $endDay
     * @return bool
     * @throws \Exception
     */
    protected static function isValidRequest(\DateTime $startDay, \DateTime $endDay): bool
    {
        $user = UserRecord::findById(AppCore::$user->id);
        $daysLeft = self::getRemainedDays($user);

        if ($startDay == $endDay) {
            AppCore::$session->flash->setFlash('request_error',
                'Your vacation\'s start date can\'t be equal to end date');
            return false;
        }

        $nowDay = new \DateTime("midnight");
        if ($startDay < $nowDay) {
            AppCore::$session->flash->setFlash('request_error',
                'Your vacation can\'t start before today');
            return false;
        }

        if ($endDay < $startDay) {
            AppCore::$session->flash->setFlash('request_error',
                'Your vacation\'s start date can\'t exceed one\'s end date');
            return false;
        }

        $vacationLength = ($endDay->diff($startDay))->days;
        if ($vacationLength > $daysLeft) {
            AppCore::$session->flash->setFlash('request_error',
                'You are requesting more days then you have');
            return false;
        }

        return true;
    }

    /**
     * Marks request as deleted
     *
     * @return bool
     * @throws \Exception
     */
    public function deleteRequest(): bool
    {
        $vacation = RequestsRecord::getRequestById($this->id);
        if ($this->canBeUpdated()) {
            return RequestsRecord::deleteRequest($this->id);
        } else {
            return false;
        }
    }

    /**
     * Checks if requests is allowed to update
     *
     * @param int|null $daysLeft
     * @return bool
     */
    public function canBeUpdated(int $daysLeft = null): bool
    {
        if ($this->status != RequestsRecord::STATUS_PENDING ||
            !empty($this->deleted_at)
        ) {

            AppCore::$session->flash->setFlash('request_update_error',
                'This request was already processed');
            return false;
        }

        if ($daysLeft != null) {
            $length = (new \DateTime($this->finish_date))->diff(new \DateTime($this->start_date))->days;
            if ($length >= $daysLeft) {
                AppCore::$session->flash->setFlash('request_update_error',
                    'User don\'t have enough days for this vacation');
                return false;
            }
        }

        return true;
    }

    /**
     * Returns remaining days of user by type of day-off
     *
     * @param User $user
     * @return int
     * @throws \Exception
     * @internal param array $userData
     */
    public static function getRemainedDays(User $user): int
    {
        $daysUsed = RequestsRecord::getDaysUsedByType(self::$requestType, $user->id);
        $totalDays = PositionDaysRecord::getDayoffsTotalAmount(self::$requestType, $user->position->id);

        return $totalDays - $daysUsed;
    }


    /**
     * Sets initial values to static properties
     *
     */
    public static function init(): void
    {
        if (!self::$_inited) {
            $requestTypeName = @strtolower(end(explode('\\', self::class)));
            self::$requestType = DayOffTypes::$types[$requestTypeName];
        }
    }

    public function __construct($initData)
    {
        parent::__construct($initData);

        foreach ($this->_attributes as $attribute => $data) {

            if (isset($initData[$attribute])) {
                $this->_attributes[$attribute]['value'] = $initData[$attribute];
                if (!settype($this->_attributes[$attribute]['value'], $data['type'])) {
                    throw new \Exception('Error in casting: ' .
                        $this->_attributes[$attribute] . ' to ' . $data['type']
                        . ' in ' . __FILE__ . ' ' . __LINE__);
                }
            }
        }
    }

}