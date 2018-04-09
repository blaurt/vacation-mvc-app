<?php
declare(strict_types=1);

namespace app\models\users;

use app\models\Model;

/**
 * Initial user's state on the portal
 *
 * Class Guest
 * @package core\models\users
 */
class Guest extends Model
{
    /**
     * @var bool shows if user is logged in
     */
    protected $isGuest = true;

    /**
     * @return bool returns is user logged in or not
     */
    public function isGuest(): bool
    {
        return $this->isGuest;
    }
}