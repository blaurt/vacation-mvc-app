<?php
declare(strict_types=1);

namespace app\core\service;

use app\core\AppCore;

/**
 * Class to manipulate responses
 *
 * Class Response
 * @package app\core\service
 */
class Response
{
    public $protocol;

    public function __construct()
    {
        $this->protocol = AppCore::$request->server['SERVER_PROTOCOL'] ?? 'HTTP/1.0';
    }

    public function sendResponse(int $code, string $message)
    {
        header($this->protocol . ' ' . $code . ' ' . $message);
        exit();
    }
}