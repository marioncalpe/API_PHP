<?php

namespace App\Util;

class HTTP {
    const CODE_2XX_SUCCESS        = 200;
    const CODE_2XX_CREATED        = 201;
    const CODE_2XX_NOCONTENT      = 204;
    const CODE_4XX_BADREQUEST     = 400;
    const CODE_4XX_NOTFOUND       = 404;
    const CODE_5XX_INTERNAL       = 500;
    const CODE_5XX_NOTIMPLEMENTED = 501;
    const CODE_5XX_UNAVAILABLE    = 503;

    public static function isJSON($string): bool{
        return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE);
     }

    public static function parseHttpRequest(?array &$arr): void {
        // read incoming data
        $input = file_get_contents('php://input');

        if(self::isJSON($input)) {
            $arr = json_decode($input, true);
        } else {
            if(is_null($arr)) {
                $arr = array();
            }

            // grab multipart boundary from content type header
            preg_match('/boundary=(.*)$/', $_SERVER['CONTENT_TYPE'], $matches);
            $boundary = $matches[1];

            // split content by boundary and get rid of last -- element
            $a_blocks = preg_split("/-+$boundary/", $input);
            array_pop($a_blocks);

            // loop data blocks
            foreach ($a_blocks as $id => $block) {
                if (empty($block)) {
                    continue;
                }

                // parse uploaded files
                if (strpos($block, 'application/octet-stream') !== FALSE) {
                    // match "name", then everything after "stream" (optional) except for prepending newlines
                    preg_match("/name=\"([^\"]*)\".*stream[\n|\r]+([^\n\r].*)?$/s", $block, $matches);
                }
                // parse all other fields
                else {
                    // match "name" and optional value in between newline sequences
                    preg_match('/name=\"([^\"]*)\"[\n|\r]+([^\n\r].*)?\r$/s', $block, $matches);
                }
                $arr[$matches[1]] = $matches[2];
            }
        }
    }

    static function response(int $status, string $status_message, mixed $data = null) {
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Allow-Methods: *");
        header("Access-Control-Allow-Credentials: true");
        header("Access-Control-Allow-Headers: content-type, Authorization");
        header("Content-Type:application/json");
        header("HTTP/1.1 ".$status);

        $response['status']=$status;
        $response['status_message']=$status_message;
        $response['data']=$data;

        echo json_encode($response);
    }
}