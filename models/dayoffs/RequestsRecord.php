<?php
declare(strict_types=1);

namespace app\models\dayoffs;

use app\core\AppCore;
use app\models\Record;
use PDO;

/**
 * Class RequestsRecord to manage requests table
 * @package app\models\dayoffs
 */
class RequestsRecord extends Record
{
    /**
     * Initial vacation's status - not processed by IManager
     */
    const STATUS_PENDING = 1;

    /**
     * Vacation approved status
     */
    const STATUS_APPROVED = 2;

    /**
     * Vacation rejected status
     */
    const STATUS_REJECTED = 3;

    /**
     * Name of the table handled
     */
    const TABLE_NAME = 'requests';

    /**
     * Adds new request to requests table with pending status
     *
     * @param \DateTime $startDate
     * @param \DateTime $endDate
     * @param int $requestType
     * @return bool
     * @throws \Exception
     */
    public static function putRequest(\DateTime $startDate, \DateTime $endDate, int $requestType): bool
    {
        $query = AppCore::$dbConnection->prepare("
                    INSERT
                    INTO " . self::TABLE_NAME . "
                    (user_id, start_date, finish_date,type,updated_by)
                    VALUES
                    (:userId, :startDate, :endDate, :type, :userId)
                ");

        $query->bindValue(':userId', AppCore::$user->id);
        $query->bindValue(':startDate', $startDate->format('Y-m-d H:i:s'));
        $query->bindValue(':endDate', $endDate->format('Y-m-d H:i:s'));
        $query->bindValue(':type', $requestType);
        $result = $query->execute();

        parent::recheckResult($query);

        return $result;
    }

    /**
     * Returns statuses of user's requests by day-off type
     *
     * @param int $userId
     * @param int $typeOfRequests
     * @return array
     * @throws \Exception
     */
    public static function getRequestsStatuses(int $userId, int $typeOfRequests): array
    {
        $query = AppCore::$dbConnection->prepare("
            SELECT id, user_id, start_date, finish_date, status, created_at, updated_at, updated_by 
            FROM " . self::TABLE_NAME . "
            WHERE user_id = :userId 
              AND type = :type 
              AND deleted_at IS NULL 
            ");
        $query->bindValue(':userId', $userId);
        $query->bindValue(':type', $typeOfRequests);
        $query->execute();

        parent::recheckResult($query);
        $requestData = $query->fetchAll(PDO::FETCH_ASSOC);

        return $requestData;
    }

    /**
     * Returns request data by request id
     *
     * @param int $requestId
     * @return array
     * @throws \Exception
     */
    public static function getRequestById(int $requestId): array
    {
        $query = AppCore::$dbConnection->prepare("
            SELECT *
            FROM " . self::TABLE_NAME . "
            WHERE id = :requestId
            ");
        $query->bindValue(':requestId', $requestId);
        $query->execute();

        parent::recheckResult($query);
        $requestData = $query->fetch(PDO::FETCH_ASSOC);

        return $requestData;
    }

    /**
     * Updates request status
     *
     * @param int $vacationId
     * @param int $status
     * @return bool
     * @throws \Exception
     */
    public static function updateRequest(int $vacationId, int $status): bool
    {
        $query = AppCore::$dbConnection->prepare("
            UPDATE " . self::TABLE_NAME . "
            SET 
              status = :status,
              updated_at = CURRENT_TIMESTAMP,
              updated_by = :userId
            WHERE id = :vacationId
            ");
        $query->bindParam(':status', $status);
        $query->bindParam(':vacationId', $vacationId);
        $query->bindValue(':userId', AppCore::$user->id);

        $result = $query->execute();

        parent::recheckResult($query);

        return $result;
    }

    /**
     * Marks Day-off request as deleted
     *
     * @param int $vacationId
     * @return bool
     * @throws \Exception
     */
    public static function deleteRequest(int $vacationId): bool
    {
        $query = AppCore::$dbConnection->prepare("
            UPDATE " . self::TABLE_NAME . "
            SET 
              deleted_at = CURRENT_TIMESTAMP,
              deleted_by = :userId
            WHERE id = :vacationId
            ");
        $query->bindValue(':userId', AppCore::$user->id);
        $query->bindParam(':vacationId', $vacationId);

        $result = $query->execute();
        parent::recheckResult($query);

        return $result;
    }

    /**
     * Returns user's remaining days of a certain type for this year
     * (e.g. vacations, or any other, if last will be added to system)
     *
     * @param int $typeOfDayOff sets a the type of day-offs
     * @param int $userId id of user we are interested in
     * @return int amount of days
     * @throws \Exception
     */
    public static function getDaysUsedByType(int $typeOfDayOff, int $userId): int
    {
        $query = AppCore::$dbConnection->prepare("
            SELECT 
              SUM(TIMESTAMPDIFF(DAY, start_date, finish_date))
            FROM " . self::TABLE_NAME . "
            WHERE  user_id = :userId 
              AND type = :typeOfDayOff
              AND status = :statusId
              AND deleted_at IS NULL
              AND YEAR(start_date) = YEAR(CURRENT_DATE)
            ");
        $query->bindValue(':userId', $userId);
        $query->bindValue(':statusId', self::STATUS_APPROVED);
        $query->bindValue(':typeOfDayOff', $typeOfDayOff);
        $query->execute();
        $result = $query->fetch(PDO::FETCH_NUM);

        parent::recheckResult($query);

        $daysLeft = (int)$result[0];
        return $daysLeft;
    }


}