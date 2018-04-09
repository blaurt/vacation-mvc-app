<?php
declare(strict_types=1);

namespace app\core\service;

use app\controllers\AbstractController;

class View
{
    /**
     * Shows if response requires layout
     *
     * @var bool
     */
    public $renderLayout;

    /**
     * Renders content of response
     * Sets $renderLayout according to requirements
     *
     * @param string $view - name of content template(view)
     * @param array|null $params
     * @param AbstractController $controller is child of AbstractController
     * @return string - rendered content of response
     * @throws \Exception
     */
    public function renderContent(string $view, array $params = [], AbstractController $controller): string
    {
        $this->renderLayout = $controller->renderLayout;
        extract($params);
        $viewsPath = realpath(APP_DIR . '/views/' . $controller->fetchControllerName() . '/' . $view . '.php');

        if (file_exists($viewsPath)) {
            ob_start();
            include $viewsPath;
            return ob_get_clean();
        } else {
            throw new \Exception('Can\'t find view file: ' . $viewsPath
                . ' at path: ' . $viewsPath);
        }
    }

    public function renderPage(string $content): string
    {
        if ($this->renderLayout) {
            $layoutFile = realpath(APP_DIR . '/views/template/main.php');
            if (file_exists($layoutFile)) {
                ob_start();
                require_once $layoutFile;
                return ob_get_clean();
            }
        } else {
            return $content;
        }
    }

}