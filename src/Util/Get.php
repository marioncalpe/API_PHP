<?php

namespace App\Util;

/**
 * get management class
 * @author Gwenaël Guiraud
 * @version 3
 * @static
 */
class Get {

    /**
     * Check if the specified get exist
     * @param string $name The get's name to check
     * @return true|false true if the get exist, false otherwise
     */
    public static function exists(... $variablesName): bool {
        $result = true;

        foreach($variablesName as $vName) {
            $result = $result && isset($_GET[$vName]);
        }
        
        return $result;
    }

    /**
     * get variable getter
     * @param string $name The get variable's name to retrieve
     * @return mixed The get variable's value
     */
    public static function get(string $name): mixed {
        return $_GET[$name];
    }

    public static function getMultiples(... $variables): array {
        $result = array();

        foreach($variables as $v) {
            array_push($result, self::get($v));
        }

        return $result;
    }
}