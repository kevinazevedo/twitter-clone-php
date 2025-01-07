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
    }

?>