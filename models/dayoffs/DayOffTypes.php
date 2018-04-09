<?php
declare(strict_types=1);

namespace app\models\dayoffs;

use app\models\Model;

/**
 * Class-mapper to store day-offs'  id-descriptions
 *
 * Class DayOffTypes
 * @package app\models\dayoffs
 */
class DayOffTypes extends Model
{
    public static $types;

    public static function init()
    {
        $typesData = DayOffTypesRecord::getTypes();
        foreach ($typesData as $num => $data) {
            $typesData[$data['description']] = (int)$data['id'];
            unset($typesData[$num]);
        }
        self::$types = $typesData;
    }
}