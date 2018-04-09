<?php
declare(strict_types=1);

namespace app\models\dayoffs;

use app\core\AppCore;
use app\models\Record;
use PDO;

class DayOffTypesRecord extends Record
{
    /**
     * Name of the table handled
     */
    const TABLE_NAME = 'dayoffs_types';

    /**
     * Get data from table
     *
     * @return array
     * @throws \Exception
     */
    public static function getTypes(): array
    {
        $query = AppCore::$dbConnection->prepare("
            SELECT *
            FROM " . self::TABLE_NAME . " 
            ");
        $query->bindParam(':roleId', $roleId);
        $query->execute();

        parent::recheckResult($query);

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        return $result;
    }
}