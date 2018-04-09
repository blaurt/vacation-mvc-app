<?php
declare(strict_types=1);

namespace app\controllers;

use app\core\AppCore;

abstract class AbstractController
{
    /**
     * Shows if response requires layout
     *
     * @var bool
     */
    public $renderLayout = true;

    /**
     * Calls app\core\service\View::renderContent
     *
     * @param string $view
     * @param array $params
     * @return string
     * @throws \Exception
     */
    public function render(string $view, array $params = []): string
    {
        return AppCore::$view->renderContent($view, $params, $this);
    }

    /**
     * @param $name - action name
     * @param $arguments - not available :D
     * @return mixed
     * @throws \Exception
     */
    public function __call(string $name, array $arguments): string
    {
        $action = 'action' . ucfirst($name);
        if (method_exists($this, $action)) {
            AppCore::$logger->debug('action exists: ' . $action);
            $this->beforeAction($action);
            $actionResult = $this->$action();
            $this->afterAction($action);
            return $actionResult;
        } else {
            AppCore::$logger->debug("Method $action not found in " . static::class);
            throw new \Exception('Method not found in ' . static::class);
        }
    }

    /**
     * Subtracts controller name out of namespace(full class name)
     * need to find proper controller folder in views
     * @return string
     */
    public function fetchControllerName()
    {
        $fullName = explode('\\', static::class);
        $controllerName = end($fullName);
        return strtolower(substr($controllerName, 0, -strlen('Controller')));
    }

    /**
     * Сan be overridden by child classes for their purpose
     * @param string $action shows what action of certain controller will be called
     */
    protected function beforeAction(string $action): void
    {
        AppCore::$logger->debug('before action method called');
    }

    /**
     * Сan be overridden by child classes for their purpose
     * @param string $action shows what action of certain controller was called
     */
    protected function afterAction(string $action): void
    {
        AppCore::$logger->debug('after action method called');

    }

    /**
     * Redirect to current page to clear $_POST data
     */
    public function refresh()
    {
        header('Location: ' . AppCore::$request->server['REQUEST_URI']);
        exit();
    }

    /**
     * Redirects user to given route
     *
     * Redirect to given route
     * @param string $location
     */
    public function redirect(string $location)
    {
        header('Location: ' . $location);
        exit();
    }
}