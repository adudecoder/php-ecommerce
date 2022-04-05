<?php

    // Essa classe é um Model e todo Model vai ter GETTERS e SETTERS

    namespace Hcode\Model;

    use \Hcode\DB\Sql;
    use \Hcode\Model;

    class User extends Model {

        const SESSION = "User";

        public static function login($login, $password)
        {

            $sql = new Sql();

            $results = $sql -> select("SELECT * FROM tb_users WHERE deslogin = :LOGIN", array(
                ":LOGIN" => $login
            ));

            // Verificando se existe um usuario ou senha
            if (count($results) === 0) {
                throw new \Exception("Usuário inexistente ou senha inválida.");
            }

            // Comparar as senhas
            $data = $results[0];

            // Essa função retorna TRUE ou FALSE se as senhas bateram ou não
            if (password_verify($password, $data["despassword"]) === true ) {

                $user = new User();

                $user -> setData($data); // Passando o array inteiro

                // Sessão para onde vai ser redirecionado o usuario após login
                $_SESSION[User::SESSION] = $user -> getValues(); // Para pegar os valores que estão no objeto User

                return $user;

            } else {

                throw new \Exception("Usuário inexistente ou senha inválida.");

            }

        }

        // Metodo para verificar se o usuario ta logado ou não
        public static function verifyLogin($inadmin = true) // Esse usuario é da administração?, se ele logou na loja, ele não tem permissão para logar no admin
        {

            if (
                !isset($_SESSION[User::SESSION]) // Verificar se ela não foi definida sessão
                ||
                !$_SESSION[User::SESSION] // Se ela for false
                ||
                !(int)$_SESSION[User::SESSION]["iduser"] > 0 // Se o Idusuario que ta dentro dessa sessão não for maior que zero, se for maior que zero, realmente é um usuario
                ||
                (bool)$_SESSION[User::SESSION]["inadmin"] !== $inadmin // Verificar se na administração ele pode acessar
            ) {
                header("Location: /admin/login"); // Então vai ser redirecionado para tela de login
                exit;
            }

        }

        public static function logout()
        {

            $_SESSION[User::SESSION] = NULL;

        }

        public static function listAll()
        {

            $sql = new Sql();

            return $sql -> select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) ORDER BY b.desperson");
            // Unimos as tabelas users e persons, ordenando pelo nome da pessoa desperson

        }

        public function save() // Método para salvar os dados do new user no db
        {

            $sql = new Sql();

            // #ATENÇÃO# Chamando o PROCEDURE que vai fazer o INSERT, SELECT automaticamente evitando várias linhas de código e request/response para o servidor:
            $results = $sql->select("CALL sp_users_save(:desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
                ":desperson"    => $this -> getdesperson(),
                ":deslogin"     => $this -> getdeslogin(),
                ":despassword"  => $this -> getdespassword(),
                ":desemail"     => $this -> getdesemail(),
                ":nrphone"      => $this -> getnrphone(),
                ":inadmin"      => $this -> getinadmin()
                // Todos esses getters foram gerados pelo setdata
            ));

            // Só nos interessa a primeira linha desse results, setando no próprio objeto:
            $this -> setData($results[0]);

        }

        public function get($iduser)
        {
         
            $sql = new Sql();
            
            $results = $sql -> select("SELECT * FROM tb_users a INNER JOIN tb_persons b USING(idperson) WHERE a.iduser = :iduser;", array(
            ":iduser" => $iduser
            ));
            
            $data = $results[0];
            
            $this -> setData($data);
         
        }

        public function update() // Vai ser bem similar ao save()
        {

            $sql = new Sql();

            // #ATENÇÃO# Chamando o PROCEDURE que vai fazer o INSERT, SELECT automaticamente evitando várias linhas de código e request/response para o servidor:
            $results = $sql->select("CALL sp_usersupdate_save(:iduser, :desperson, :deslogin, :despassword, :desemail, :nrphone, :inadmin)", array(
                ":iduser"       => $this -> getiduser(),
                ":desperson"    => $this -> getdesperson(),
                ":deslogin"     => $this -> getdeslogin(),
                ":despassword"  => $this -> getdespassword(),
                ":desemail"     => $this -> getdesemail(),
                ":nrphone"      => $this -> getnrphone(),
                ":inadmin"      => $this -> getinadmin()
                // Todos esses getters foram gerados pelo setdata
            ));

            // Só nos interessa a primeira linha desse results, setando no próprio objeto:
            $this -> setData($results[0]);

        }

        public function delete() // Método para deletar o usuário
        {

            $sql = new Sql();

            $sql -> query("CALL sp_users_delete(:iduser)", array(

                ":iduser" => $this -> getiduser()

            ));

        }

    }

?>