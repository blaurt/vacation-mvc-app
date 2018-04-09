<?php
declare(strict_types=1);

namespace app\models\role;

use app\core\AppCore;
use app\models\Record;
use PDO;

class RolesPermissionsRecord extends Record
{
    /**
     * Name of the table handled
     */
    const TABLE_NAME = 'roles_permissions';

    /**
     * Get user's permissions from db by user's role
     *
     * @param int $roleId
     * @return array
     * @throws \Exception
     */
    public static function getPermissions(int $roleId): array
    {
        $query = AppCore::$dbConnection->prepare("
            SELECT perm_id
            FROM " . self::TABLE_NAME . " 
            WHERE 
              role_id = :roleId
            ");
        $query->bindParam(':roleId', $roleId);
        $query->execute();

        parent::recheckResult($query);

        $result = $query->fetchAll(PDO::FETCH_ASSOC);

        $permissions = [];
        foreach ($result as $item) {
            $permissions[] = $item['perm_id'];
        }

        return $permissions;
    }
}