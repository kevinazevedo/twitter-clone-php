<?php 

    namespace App\Models;

    use MF\Model\Model;

    class Tweet extends Model {
        private $id;
        private $id_usuario;
        private $tweet;
        private $data;

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }

        // salvar
        public function salvar() {
            $query = "INSERT INTO tweets(id_usuario, tweet) VALUES(:id_usuario, :tweet)";
            $st = $this->db->prepare($query);

            $st->bindValue(":id_usuario", $this->__get("id_usuario"));
            $st->bindValue(":tweet", $this->__get("tweet"));
            $st->execute();
 
            return $this;
        }

        // remover
        public function remover($tweet_id) {
            $query = "DELETE FROM tweets WHERE id = :tweet_id";
            $st = $this->db->prepare($query);
            $st->bindValue(":tweet_id", $tweet_id);
            $st->execute();

            return $this;
        }

        // recuperar
        public function getAll() {
            $query = "SELECT 
                    t.id, t.id_usuario, u.nome, t.tweet, DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data
                FROM 
                    tweets as t
                LEFT JOIN 
                    usuarios as u ON (t.id_usuario = u.id)
                WHERE 
                    t.id_usuario = :id_usuario
                OR
                    t.id_usuario in (SELECT id_usuario_seguindo FROM usuarios_seguidores WHERE id_usuario = :id_usuario)
                ORDER BY
                    t.data desc
            ";

            $st = $this->db->prepare($query);

            $st->bindValue(":id_usuario", $this->__get("id_usuario"));
            $st->execute();
 
            return $st->fetchAll(\PDO::FETCH_ASSOC);
        }

        // recuperar por paginação
        public function getPorPagina($limit, $offset) {
            $query = "SELECT 
                    t.id, t.id_usuario, u.nome, t.tweet, DATE_FORMAT(t.data, '%d/%m/%Y %H:%i') as data
                FROM 
                    tweets as t
                LEFT JOIN 
                    usuarios as u ON (t.id_usuario = u.id)
                WHERE 
                    t.id_usuario = :id_usuario
                OR
                    t.id_usuario in (SELECT id_usuario_seguindo FROM usuarios_seguidores WHERE id_usuario = :id_usuario)
                ORDER BY
                    t.data desc
                LIMIT
                    $limit
                OFFSET
                    $offset
            ";

            $st = $this->db->prepare($query);

            $st->bindValue(":id_usuario", $this->__get("id_usuario"));
            $st->execute();

            return $st->fetchAll(\PDO::FETCH_ASSOC);
        }

        // recuperar total de tweets
        public function getTotalRegistros() {
            $query = "SELECT 
                    COUNT(*) AS total
                FROM 
                    tweets as t
                LEFT JOIN 
                    usuarios as u ON (t.id_usuario = u.id)
                WHERE 
                    t.id_usuario = :id_usuario
                OR
                    t.id_usuario in (SELECT id_usuario_seguindo FROM usuarios_seguidores WHERE id_usuario = :id_usuario)
            ";

            $st = $this->db->prepare($query);

            $st->bindValue(":id_usuario", $this->__get("id_usuario"));
            $st->execute();

            return $st->fetch(\PDO::FETCH_ASSOC);
        }
    }

?>