<?php

namespace Anonymous\Recipioneer\Deployer\Utils;


/**
 * Class ArrayHelper
 * @package App\Helpers
 */
class ArrayHelper
{

    /**
     * @param $array
     * @param $key
     * @param mixed $default
     * @return mixed
     */
    public static function getValue($array, $key, $default = null)
    {
        if (empty($key) && !is_numeric($key) || empty($array)) {
            return $default;
        }

        $partsOfKey = explode('.', $key);

        if (is_array($array) || $array instanceof \ArrayAccess) {

            $partial = '';

            do {
                $part = array_shift($partsOfKey);
                $partial .= $partial ? '.' . $part : $part;

                if (!array_key_exists($partial, $array)) {
                    continue;
                }

                $value = $array[$partial];

                return count($partsOfKey)
                    ? static::getValue($value, implode('.', $partsOfKey), $default)
                    : $value;
            } while (count($partsOfKey));
        }

        if (is_object($array)) {
            $firstPart = array_shift($partsOfKey);

            if (property_exists($array, $firstPart)) {
                $value = $array->{$firstPart};

                return count($partsOfKey)
                    ? static::getValue($value, implode('.', $partsOfKey), $default)
                    : $value;
            }
        }

        return $default;
    }

    /**
     * @param $array
     * @param $index
     * @param bool $useOldKeyOnNull
     * @return array
     */
    public static function indexBy($array, $index, $useOldKeyOnNull = false)
    {
        $result = [];

        foreach ($array as $key => $value) {
            $newKey = static::getValue($value, $index);

            if ($newKey === null) {
                if ($useOldKeyOnNull) {
                    $newKey = $key;
                } else {
                    continue;
                }
            }

            $result[$newKey] = $value;
        }

        return $result;
    }

    /**
     * @param $a
     * @param $b
     * @return array
     */
    public static function merge($a, $b)
    {
        $args = func_get_args();
        $res = array_shift($args);

        while (!empty($args)) {
            $next = array_shift($args);

            foreach ($next as $k => $v) {
                if (is_int($k)) {
                    if (array_key_exists($k, $res)) {
                        $res[] = $v;
                    } else {
                        $res[$k] = $v;
                    }
                } elseif (is_array($v) && isset($res[$k]) && is_array($res[$k])) {
                    $res[$k] = self::merge($res[$k], $v);
                } else {
                    $res[$k] = $v;
                }
            }
        }

        return $res;
    }

}