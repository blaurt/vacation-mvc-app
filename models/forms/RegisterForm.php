<?php
declare (strict_types=1);

namespace app\models\forms;

use app\core\AppCore;
use app\models\users\UserRecord;

/**
 * Class RegisterForm handles process of adding new user to db
 *
 * Class can be extended with input validation or smth
 * @package app\models\forms
 */
class RegisterForm
{
    /**
     * Adds new user to db
     *
     * @param array $regData
     * @return bool
     */
    public function registerUser(array $regData): bool
    {
        try {
            $validationResult = $this->validateParams($regData);
        } catch (\Exception $e) {
            AppCore::$session->flash->setFlash(
                'register_error',
                $e->getMessage());
            return false;
        }
        $regData['password_hash'] = password_hash($regData['password'], PASSWORD_BCRYPT);

        AppCore::$dbConnection->beginTransaction();
        try {
            $newUserId = UserRecord::addUser($regData);
        } catch (\Exception $e) {
            if (strstr(strtolower($e->getMessage()), 'duplicate')) {
                AppCore::$session->flash->setFlash(
                    'register_error',
                    'User with login ' . $regData['login'] . ' already exists'
                );
            }
            AppCore::$dbConnection->rollBack();
            return false;
        }
        AppCore::$dbConnection->commit();

        return true;
    }

    public function validateParams($regData): bool
    {
        foreach ($regData as $key => $data) {
            if (empty($data)) {
                throw new \Exception($key . ' is empty');
            }
        }

        if (!in_array($regData['role'], UserRecord::$availableRoles)) {
            throw new \Exception('Invalid type of user');
        }

        return true;
    }
}