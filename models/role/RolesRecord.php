<?php
declare(strict_types=1);

namespace app\models\role;

use app\core\AppCore;
use app\models\Record;
use PDO;

class RolesRecord extends Record
{
    /**
     * Name of the table handled
     */
    const TABLE_NAME = 'roles';


    public static function loadData(int $positionId): array
    {
        $query = AppCore::$dbConnection->prepare("
            SELECT *
            FROM " . self::TABLE_NAME . " 
            WHERE 
              id = :positionId
            ");
        $query->bindParam(':positionId', $positionId);
        $query->execute();

        parent::recheckResult($query);

        $result = $query->fetch(PDO::FETCH_ASSOC);

        return $result;
    }
}