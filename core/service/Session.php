<?php
declare(strict_types=1);

namespace app\core\service;

class Session
{
    public $flash;

    /**
     * Saves user's data to session, to restore it in feature
     *
     * @param string $key
     * @param $data
     * @internal param $userData
     */
    public function storeToSession(string $key, $data): void
    {
        $_SESSION[$key] = $data;
    }

    public function __construct()
    {
        $this->flash = new FlashMessages();
    }

    /**
     * Get data from $_SESSION
     *
     * @param string $key
     * @return mixed | null
     */
    public function getFromSession(string $key)
    {
        return $_SESSION[$key] ?? null;
    }


    /**
     * Checks if data is present is $_SESSION
     *
     * @param string $key
     * @return bool
     */
    public function isInSession(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    public function removeFromSession(string $key)
    {
        unset($_SESSION[$key]);
    }
}