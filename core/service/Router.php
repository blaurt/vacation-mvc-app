<?php
declare (strict_types=1);

namespace app\core\service;

use app\core\AppCore;

/**
 * Class Router
 * to fetch controller-action
 * @package app|core
 */
class Router
{
    /**
     * Fetches controller-action pare from request
     */
    public function parseRequest(): array
    {
        if (isset(AppCore::$request->get['r'])) {
            $request = AppCore::$request->get['r'];
        } else {
            $request = AppCore::$config['defaultRoute'];
            AppCore::$logger->debug('route param is empty');
        }

        AppCore::$logger->debug('processing request: ' . print_r($request, true));
        $requestParts = explode('/', $request);

        $controller = ucfirst($requestParts[0]);
        $controller = 'app\controllers\\' . $controller . 'Controller';

        $action = $requestParts[1] ?? 'index';

        return [$controller, $action];
    }


}