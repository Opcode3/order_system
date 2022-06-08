<?php
    namespace app\config;
    class Dto{

        public static function response($data){
            return json_encode(['response'=> $data]);
        }
    }
?>