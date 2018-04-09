<?php
declare(strict_types=1);

namespace app\models;

abstract class Model
{
    /**
     * Contains collection of allowed attributes
     *
     * @var null
     */
    protected $_attributes = null;

    /**
     * Shows if non-static variables were initialized
     *
     * @var bool
     */
    protected $_loaded = false;

    /**
     * Shows if static variables were initialized
     *
     * @var bool
     */
    protected static $_inited = false;

    protected $_container = [];


    public function __construct(array $initData = [])
    {
        $this->_container = $initData;
    }

    public function __get($name)
    {
        if (array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name]['value'];
        } else {
            return null;
        }
    }

}