<?php
declare(strict_types=1);

namespace app\controllers;
/**
 * To handle empty requests
 * Class DefaultController
 * @package core\controllers
 */
class DefaultController extends AbstractController
{
    public function actionIndex()
    {
        return $this->render('index');
    }
}