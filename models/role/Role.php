<?php
declare(strict_types=1);

namespace app\models\role;

use app\models\Model;

/**
 * Class to handle user's permissions
 *
 * Class Role
 * @package app\models\role
 */
class Role extends Model
{
    /**
     * @var array stores role's permissions
     */
    public $permissions;

    /**
     * Permission to request vacation
     */
    const REQUEST_VACATION = 1;

    /**
     * Permission to check request's status
     */
    const CHECK_VACATION_STATUS = 2;

    /**
     * Permission to approve / reject requests
     */
    const MANAGE_VACATIONS = 3;


    protected $_attributes = [
        'description' => ['type' => 'string', 'value' => null],
    ];


    public function __construct(int $roleId)
    {
        $this->id = $roleId;
        $this->permissions = RolesPermissionsRecord::getPermissions($roleId);
    }

    public function __get($name)
    {
        if ($this->_loaded) {
            return $this->_attributes[$name]['value'];
        } else {
            $roleData = RolesRecord::loadData($this->id);

            foreach ($this->_attributes as $attribute => $data) {
                $this->_attributes[$attribute]['value'] = $roleData[$attribute];
                $castResult = settype($this->_attributes[$attribute]['value'], $data['type']);
                if (!$castResult) {
                    throw new \Exception('Error in casting: ' .
                        $roleData[$attribute] . ' to ' . $data['type']);
                }
            }
            $this->_loaded = true;

            return $this->_attributes[$name]['value'];
        }
    }


}