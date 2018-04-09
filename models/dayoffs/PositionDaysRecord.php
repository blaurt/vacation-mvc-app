<?php
declare(strict_types=1);

namespace app\models\dayoffs;

use app\core\AppCore;
use app\models\Record;
use PDO;

/**
 * Hanldes operation with user's day-off count
 *
 * Class PositionDaysRecord
 * @package app\models\dayoffs
 */
class PositionDaysRecord extends Record
{

    const TABLE_NAME = 'positions_days';

    /**
     * Returns initial amount of day-offs by user's position
     *
     * @param int $typeOfDays
     * @param int $positionId
     * @return int
     * @throws \Exception
     */
    public static function getDayoffsTotalAmount(int $typeOfDays, int $positionId): int
    {
        $query = AppCore::$dbConnection->prepare("
            SELECT amount
            FROM " . self::TABLE_NAME . " 
            WHERE position_id = :positionId
              AND dayoff_type_id = :typeOfDays
            ");
        $query->bindParam(':positionId', $positionId);
        $query->bindParam(':typeOfDays', $typeOfDays);
        $query->execute();
        parent::recheckResult($query);

        $result = $query->fetch(PDO::FETCH_ASSOC);


        return (int)$result['amount'];
    }
}