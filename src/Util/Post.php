<?php

namespace App\Util;

/**
 * post management class
 * @author Gwenaël Guiraud
 * @version 3
 * @static
 */
class Post {

    /**
     * Check if the specified post exist
     * @param string $name The post's name to check
     * @return true|false true if the post exist, false otherwise
     */
    public static function exists(... $variablesName): bool {
        $result = true;

        foreach($variablesName as $vName) {
            $result = $result && isset($_POST[$vName]);
        }
        
        return $result;
    }

    /**
     * post variable getter
     * @param string $name The post variable's name to retrieve
     * @return mixed The post variable's value
     */
    public static function get(string $name): mixed {
        return $_POST[$name];
    }

    public static function getMultiples(... $variables): array {
        $result = array();

        foreach($variables as $v) {
            array_push($result, self::get($v));
        }

        return $result;
    }
}