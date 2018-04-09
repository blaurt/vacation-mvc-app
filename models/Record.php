<?php
declare(strict_types=1);

namespace app\models;

use app\core\AppCore;
use PDO;

/**
 * Base class for all *Record classes
 *
 * Class Record
 * @package app\models
 */
abstract class Record
{
    /**
     * Checks sql-query for errors, add them to log if ones exist,
     * Throws Exception if errors occurred
     *
     * @param \PDOStatement $query
     * @throws \Exception
     */
    protected static function recheckResult(\PDOStatement $query): void
    {
        if ($query->errorCode() != '00000') {
            $errorInfo = $query->errorInfo();
            AppCore::$logger->debug(__FILE__ . ' ' . __LINE__ . ' ' . __METHOD__ . ' ' . end($errorInfo));
            throw new \Exception('Error: ' . end($errorInfo));
        }
    }

    /**
     * Method used to implement lazy-load feature for objects
     *
     * @param int $entityId
     * @return array
     */
    public static function loadData(int $entityId): array
    {
        $query = AppCore::$dbConnection->prepare("
            SELECT *
            FROM " . static::TABLE_NAME . " 
            WHERE 
              id = :entityId
            ");
        $query->bindParam(':entityId', $entityId);
        $query->execute();

        self::recheckResult($query);

        $result = $query->fetch(PDO::FETCH_ASSOC);


        return $result;
    }
}