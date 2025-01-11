<?php 

    namespace App\Models;

    use MF\Model\Model;

    class Usuario extends Model {
        private $id;
        private $nome;
        private $email;
        private $senha;

        public function __get($atributo) {
            return $this->$atributo;
        }

        public function __set($atributo, $valor) {
            $this->$atributo = $valor;
        }

        // salvar
        public function salvar() {
            $query = "INSERT INTO usuarios(nome, email, senha) VALUES(:nome, :email, :senha)";
            $st = $this->db->prepare($query);

            $st->bindValue(":nome", $this->__get("nome"));
            $st->bindValue(":email", $this->__get("email"));
            $st->bindValue(":senha", $this->__get("senha"));

            $st->execute();

            return $this;
        }

        // validar se um cadastro pode ser feito
        public function validarCadastro() {
            $valido = true;

            if(strlen($this->__get("nome")) < 3) {
                $valido = false;
            }

            if(strlen($this->__get("email")) < 3) {
                $valido = false;
            }

            if(strlen($this->__get("senha")) < 3) {
                $valido = false;
            }

            return $valido;
        }

        // recuperar um usuário por email
        public function getUsuarioPorEmail() {
            $query = "SELECT nome, email FROM usuarios WHERE email = :email";
            $st = $this->db->prepare($query);
            
            $st->bindValue(":email", $this->__get("email"));
            $st->execute();

            return $st->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function autenticar() {
            $query = "SELECT id, nome, email FROM usuarios WHERE email = :email AND senha = :senha";
            $st =  $this->db->prepare($query);

            $st->bindValue(":email", $this->__get("email"));
            $st->bindValue(":senha", $this->__get("senha"));

            $st->execute();

            $usuario = $st->fetch(\PDO::FETCH_ASSOC);

            if($usuario["id"] != "" && $usuario["nome"] != "") {
                $this->__set("id", $usuario["id"]);
                $this->__set("nome", $usuario["nome"]);
            }

            return $this;
        }

        public function getAll() {
            $query = "SELECT u.id, u.nome, u.email, 
                (SELECT count(*) FROM usuarios_seguidores AS us WHERE us.id_usuario = :id_usuario AND us.id_usuario_seguindo = u.id) AS seguindo_sn 
                FROM usuarios AS u WHERE u.nome LIKE :nome AND u.id != :id_usuario"
            ;
            $st =  $this->db->prepare($query);

            $st->bindValue(":nome", "%" . $this->__get("nome") . "%");
            $st->bindValue(":id_usuario", $this->__get("id"));

            $st->execute();

            return $st->fetchAll(\PDO::FETCH_ASSOC);
        }

        public function seguirUsuario($id_usuario_seguindo) {
            $query = "INSERT INTO usuarios_seguidores(id_usuario, id_usuario_seguindo) VALUES(:id_usuario, :id_usuario_seguindo)";
            $st =  $this->db->prepare($query);
            $st->bindValue(":id_usuario", $this->__get("id"));
            $st->bindValue(":id_usuario_seguindo", $id_usuario_seguindo);

            $st->execute();

            return true;
        }

        public function deixarSeguirUsuario($id_usuario_seguindo) {
            $query = "DELETE FROM usuarios_seguidores WHERE id_usuario = :id_usuario AND id_usuario_seguindo = :id_usuario_seguindo";
            $st =  $this->db->prepare($query);
            $st->bindValue(":id_usuario", $this->__get("id"));
            $st->bindValue(":id_usuario_seguindo", $id_usuario_seguindo);

            $st->execute();

            return true;
        }
    }

?>