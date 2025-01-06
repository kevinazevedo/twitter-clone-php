<?php 

    namespace MF\Model;
    use App\Connection;

    class Container {
        public static function getModel($model) {
            $class = "\\App\\Models\\" . ucfirst($model);
            
            // instância de conexão
            $connection = Connection::getDb();

            return new $class($connection);
        }
    }

?>