<?php 

class Connection{
    public static function conn(){
        try{
            $conn = new PDO('mysql:dbname=compilerio;host=127.0.0.1', 'root', '');
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        }catch(Exception $e){
            die($e ->getMessage());
        }
    }
}

?>