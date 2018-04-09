<?php
declare (strict_types=1);

namespace app\models\forms;

use app\core\AppCore;
use app\models\users\UserRecord;

/**
 * Class LoginForm presents logic for user login operation
 * @package app\models\forms
 */
class LoginForm
{
    /**
     * Handles login operation
     *
     * @param $login
     * @param $password
     * @return bool
     */
    public function login(string $login, string $password): bool
    {
        $userModel = UserRecord::findByLogin($login);


        if (!$userModel) {
            AppCore::$session->flash->setFlash('user_error', "User $login does not exist");
            return false;
        }

        if (!password_verify($password, $userModel->password)) {
            AppCore::$session->flash->setFlash('user_error', 'Wrong password');
            return false;
        }

        AppCore::$session->storeToSession('user_data', $userModel->getContainer());
        return true;
    }

}
