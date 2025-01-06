<?php 

    namespace App;

    class Connection {
        public static function getDb() {
            try {
                $connection = new \PDO(
                    "mysql:host=localhost;dbname=;charset=utf8",
                    "root",
                    ""
                );

                return $connection;
            }

            catch(\PDOException $e) {
                $e.getMessage();
            }
        }
    }

?>