<?php
declare(strict_types=1);

namespace app\controllers;

use app\core\AppCore;
use app\models\dayoffs\Vacation;
use app\models\users\UserRecord;

/**
 * Handles request associated with vacations
 * Class VacationController
 * @package core\controllers
 */
class VacationController extends AbstractController
{
    /**
     * Entry point for create vacation requests
     *
     * @return string
     * @throws \Exception
     */
    public function actionRequest()
    {
        if (!empty(AppCore::$request->post)) {
            if (empty(AppCore::$request->post['start_date']) || empty(AppCore::$request->post['end_date'])) {
                AppCore::$session->flash->setFlash('request_error',
                    'Your vacation request data is not valid');
                $this->refresh();
            }
            $startDay = new \DateTime(AppCore::$request->post['start_date']);
            $endDay = new \DateTime(AppCore::$request->post['end_date']);
            if (!$startDay || !$endDay) {
                AppCore::$session->flash->setFlash('request_error',
                    'Your vacation request data is not valid');
                $this->refresh();
            }

            if (Vacation::createRequest($startDay, $endDay)) {
                $this->redirect(' /?r=vacation/status');
            }
        }
        $daysLeft = Vacation::getRemainedDays(AppCore::$user);

        return $this->render('request', compact('daysLeft'));
    }

    /**
     * Returns information about user's vacations
     *
     * @return string
     * @throws \Exception
     */
    public function actionStatus()
    {
        $vacations = Vacation::getVacations(AppCore::$user->id);
        $daysLeft = Vacation::getRemainedDays(AppCore::$user);

        return $this->render('status', compact('vacations', 'daysLeft'));
    }

    /**
     * Returns form to select user by name
     * @return string
     * @throws \Exception
     */
    public function actionManage()
    {
        if (!empty(AppCore::$request->post)) {
            $this->renderLayout = false;
            $login = AppCore::$request->post['login'];
            $userModel = UserRecord::findByLogin($login);
            $vacations = [];

            if (!empty($userModel)) {
                $vacations = Vacation::getVacations($userModel->id);
            }

            return $this->render('_search', compact('vacations', 'login'));
        } else {
            return $this->render('manage');
        }
    }

    /**
     * Entry point for AJAX update requests
     *
     * @return string or response with http code 400
     * @throws \Exception
     */
    public function actionUpdate()
    {
        if (!empty(AppCore::$request->post)) {
            $this->renderLayout = false;
            $status = (int)AppCore::$request->post['status'];
            $vacationId = (int)AppCore::$request->post['id'];
            $vacation = Vacation::findById($vacationId);
            $user = UserRecord::findById($vacation->user_id);
            $daysLeft = Vacation::getRemainedDays($user);
            $updateStatus = $vacation->changeState($status, $daysLeft);
            if (!$updateStatus) {
                AppCore::$response->sendResponse(400, '');
            }
            return $this->actionManage();
        } else {
            AppCore::$response->sendResponse(400, '');
        }
    }

    /**
     * Checks if requests made by logged in user
     *
     * @param string $action
     */
    protected function beforeAction(string $action): void
    {
        if (AppCore::$user->isGuest()) {
            $this->redirect(' /');
        }

        Vacation::init();
    }

    /**
     * Entry point for AJAX delete request
     *
     * returns http code in response
     * @throws \Exception
     */
    public function actionDelete()
    {
        $this->renderLayout = false;

        if (!empty(AppCore::$request->post)) {
            $vacationId = (int)AppCore::$request->post['id'];
            $vacation = Vacation::findById($vacationId);
            if ($vacation->deleteRequest()) {
                AppCore::$response->sendResponse(200, '');
            } else {
                AppCore::$response->sendResponse(400, '');
            }
        }

        AppCore::$response->sendResponse(400, 'post');
    }

}