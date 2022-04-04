<?php

    namespace Hcode;

    class Model {

        // CRIAR OS GETTERS E SETTERS

        private $values;

        public function __call($name, $args) // O call pegou SetIdUser e quebrou em duas partes
        {
            
            // Para saber se é um metodo GET ou SET
            $method = substr($name, 0, 3); // substr(SUB-STRING) ele não quer dizer a ultima posição ele quer a quantidade
            $fieldName = substr($name, 3, strlen($name)); // Apartir da terceira posição e vai até o final

            // var_dump($method, $fieldName);
            // exit;

            switch ($method)
            {

                case "get":
                    return $this -> values[$fieldName]; // Retornamos alguma coisa
                break;

                case "set":
                    $this -> values[$fieldName] = $args[0]; // Vamos atribuir o valor que esta vindo do $args na posição zero que é o primeiro argumento a ser solicitado
                break;

            }

        }

        // Metodo para retornar os campos completo do banco da dados
        public function setData($data = array())
        {

            foreach ($data as $key => $values) {

                // Tudo o que vc for criar dinamicamente em PHP vc precisa colocar entre chaves
                $this -> {"set".$key}($values); // Vai chamar cada um dos metodos automaticamente
                // Vai ser retornado como um metodo

            }

        }

        // Metodo para retornar os valores do objeto User
        public function getValues()
        {

            return $this -> values;

        }

    }

?>