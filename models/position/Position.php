<?php
declare(strict_types=1);

namespace app\models\position;


use app\models\Model;

class Position extends Model
{

    protected $_attributes = [
        'description' => ['type' => 'string', 'value' => null],
    ];

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function __get($name)
    {
        if ($this->_loaded) {
            return $this->_attributes[$name]['value'];
        } else {
            $positionData = PositionRecord::loadData($this->id);
            foreach ($this->_attributes as $attribute => $data) {
                $this->_attributes[$attribute]['value'] = $positionData[$attribute];
                $castResult = settype($this->_attributes[$attribute]['value'], $data['type']);
                if (!$castResult) {
                    throw new \Exception('Error in casting: ' .
                        $positionData[$attribute] . ' to ' . $data['type']);
                }
            }
            $this->_loaded = true;
            return $this->_attributes[$name]['value'];

        }
    }
}
