<?php
declare(strict_types=1);

namespace app\models\users;

use app\models\position\Position;
use app\models\role\Role;

/**
 * Class User to make entity of logged in user.
 *
 * @attributes
 * @package app\models\users
 */
class User extends Guest implements \ArrayAccess
{

    /**
     * @var Position
     */
    public $position;




    /**
     * @var object of app\models\Role class
     */
    public $role;

    protected $_attributes = [
        'id' => ['type' => 'int', 'value' => null],
        'login' => ['type' => 'string', 'value' => null],
        'name' => ['type' => 'string', 'value' => null],
        'password' => ['type' => 'string', 'value' => null],
    ];

    public function __construct($userData)
    {
        foreach ($this->_attributes as $attribute => $data) {
            if (isset($userData[$attribute])) {
                $this->_attributes[$attribute]['value'] = $userData[$attribute];
                if (!settype($this->_attributes[$attribute]['value'], $data['type'])) {
                    throw new \Exception('Error in casting: ' .
                        $this->_attributes[$attribute] . ' to ' . $data['type']
                        . ' in ' . __FILE__ . ' ' . __LINE__);
                }
            }
        }
        $this->_container = $userData;
        $this->isGuest = false;
        $this->position = new Position((int)$userData['position']);
        $this->role = new Role((int)$userData['role']);
        $this->_loaded = true;
    }

    /**
     * Returns current user's id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    /**
     * Function to check if user has permission to requested action
     * use app\models\role\Role constants to pass $permissionCode
     *
     * @param int $permissionCode
     * @return bool
     */
    public function hasPermission(int $permissionCode): bool
    {
        return in_array($permissionCode, $this->role->permissions);
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return isset($this->_container[$offset]);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->_container[$offset] : null;
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->_container[] = $value;
        } else {
            $this->_container[$offset] = $value;
        }
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        unset($this->_container[$offset]);
    }

    public function getContainer()
    {
        return $this->_container;;
    }



}