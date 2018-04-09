<?php
declare(strict_types=1);

namespace app\core\service;

/**
 * Can be extended to filter input data and store it in AppCore::$request
 *
 * Class Request
 * @package app\core
 */
class Request
{
    public $get;
    public $post;
    public $cookie;
    public $server;
    public $isAjax;
    public $requestMethod;

    const METHOD_POST = 'POST';
    const METHOD_GET = 'GET';

    public function __construct()
    {
        $this->get = $_GET;
        $this->post = $_POST;
        $this->cookie = $_COOKIE;
        $this->server = $_SERVER;
        $this->requestMethod = $this->server['REQUEST_METHOD'];
        if (isset($this->server['HTTP_X_REQUESTED_WITH'])
            && $this->server['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') {
            $this->isAjax = true;
        } else {
            $this->isAjax = false;
        }
    }


}