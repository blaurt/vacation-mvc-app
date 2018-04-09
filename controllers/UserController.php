<?php
declare(strict_types=1);

namespace app\controllers;

use app\core\AppCore;
use app\core\service\Request;
use app\models\forms\LoginForm;
use app\models\forms\RegisterForm;

/**
 * Handles operations: Login, Logout, Register
 * Called by classes
 * Class UserController
 * @package core\controllers
 */
class UserController extends AbstractController
{
    public function actionRegister()
    {
        if (!empty(AppCore::$request->post)) {
            $regData = [
                'login' => AppCore::$request->post['login'] ?? '',
                'password' => AppCore::$request->post['password'] ?? '',
                'username' => AppCore::$request->post['username'] ?? '',
                'role' => AppCore::$request->post['role'] ?? '',
            ];
            $registerForm = new RegisterForm();
            if ($registerForm->registerUser($regData)) {
                $this->redirect('/?r=user/login');
            } else {
                $this->refresh();
            }
        }

        return $this->render('register');
    }

    public function actionLogin()
    {
        if (!AppCore::$user->isGuest()) {
            $this->redirect('/');
        }

        if (!empty(AppCore::$request->post)) {
            $login = AppCore::$request->post['login'] ?? '';
            $password = AppCore::$request->post['password'] ?? '';
            $loginModel = new LoginForm();
            if(!$loginModel->login($login, $password)){
                $this->refresh();
            }
            $this->redirect('/');
        }

        return $this->render('login');
    }

    public function actionLogout()
    {
        if (AppCore::$request->requestMethod == Request::METHOD_POST) {
            if (!AppCore::$user->isGuest()) {
                AppCore::$session->removeFromSession('user_data');
            }
        }
        $this->redirect('/');
    }

}