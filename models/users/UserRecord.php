<?php
declare (strict_types=1);

namespace app\models\users;

use app\core\AppCore;
use app\models\Record;
use PDO;

/**
 * Used to handle requests to users table
 *
 * Class UserRecord
 * @package app\models\users
 */
class UserRecord extends Record
{
    /**
     * Amount of vacations on registration of new user
     */
    const VACATIONS_DAYS_LEFT = 20;

    /**
     * id of worker role
     */
    const WORKER_ROLE = 1;

    /**
     * id of manager role
     */
    const MANAGER_ROLE = 2;

    /**
     * Status of active user record
     */
    const NOT_DELETED_STATUS = 0;

    /**
     * Name of the users table handled
     */
    const TABLE_NAME = 'users';


    /**
     * Default worker position
     */
    const DEFAULT_POSITION_ID = 1;

    public static $availableRoles = [
        self::WORKER_ROLE,
        self::MANAGER_ROLE
    ];

    /**
     * Adds new record to users table and stores initial vacations days to dayoffs_count table
     * in one transaction
     *
     * @param $data array data inputed by user in register form
     * @return int new user id
     * @throws \Exception
     */
    public static function addUser($data): int
    {

        $query = AppCore::$dbConnection->prepare(
            "
                    INSERT
                    INTO " . self::TABLE_NAME . "
                    (login, password, name, role)
                    VALUES (
                      :login,
                      :password,
                      :username,
                      :role
                    )
                "
        );

        $query->bindParam(':login', $data['login']);
        $query->bindValue(':password', $data['password_hash']);
        $query->bindParam(':username', $data['username']);
        $query->bindValue(':role', $data['role']);
        $query->execute();

        parent::recheckResult($query);

        $usersId = (int)AppCore::$dbConnection->lastInsertId();

        return $usersId;
    }


    /**
     * Usually used in logging in process
     *
     * @param $login
     * @return User if success
     * @throws \Exception
     */
    public static function findByLogin($login): User
    {
        $query = AppCore::$dbConnection->prepare("
            SELECT 
                id,
                login, 
                name,
                password,
                position,
                role
            FROM " . self::TABLE_NAME . "
            WHERE login = :login 
              AND deleted_at IS NULL
              ");
        $query->bindParam(':login', $login);
        $query->execute();
        $userData = $query->fetch(PDO::FETCH_ASSOC);

        parent::recheckResult($query);
        if (!$userData) {
            $userData = [];
        };
        

        
        
        return new User($userData);
    }


    /**
     * Returns user data by id
     *
     * @param int $id
     * @return User
     * @throws \Exception
     */
    public static function findById(int $id): User
    {
        $query = AppCore::$dbConnection->prepare("
           SELECT 
                id,
                login, 
                name,
                password,
                position,
                role
            FROM " . self::TABLE_NAME . "
            WHERE users.id = :id 
              AND deleted_at IS NULL
            ");
        $query->bindParam(':id', $id);
        $query->execute();

        parent::recheckResult($query);

        $userData = $query->fetch(PDO::FETCH_ASSOC);
        return new User($userData);
    }

}