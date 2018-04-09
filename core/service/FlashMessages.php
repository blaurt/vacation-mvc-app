<?php
declare(strict_types=1);

namespace app\core\service;

use app\core\AppCore;

/**
 * Class to manage flash-messages
 *
 * Class FlashMessages
 * @package app\core\service
 */
class FlashMessages
{
    /**
     * Adds message to collection
     *
     * @param string $key
     * @param string $message
     */
    public function setFlash(string $key, string $message): void
    {
        $_SESSION['flash'][$key] = $message;
        AppCore::$logger->debug($message);
    }

    /**
     * Checks, if message exists
     *
     * @param string $key
     * @return bool
     */
    public function existsFlash(string $key): bool
    {
        return isset($_SESSION['flash'][$key]);
    }

    /**
     * Returns message, removing it from storage
     *
     * @param string $key
     * @return string
     */
    public function getFlash(string $key): string
    {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }
}