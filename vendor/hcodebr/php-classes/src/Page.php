<?php

    namespace Hcode;

    use Rain\Tpl;

    class Page {

        // ATTRIBUTS
        private $tpl;
        private $options = [];
        private $defaults = [
            "header" => true, // Necessario validar no draw do template
            "footer" => true,
            "data" => []
        ];

        // METHODS
        public function __construct($opts = array(), $tpl_dir = "/views/") // Primeiro a ser executado
        {

            $this -> options = array_merge($this -> defaults, $opts);

            $config = array(

                "tpl_dir"       => $_SERVER["DOCUMENT_ROOT"].$tpl_dir,
                "cache_dir"     => $_SERVER["DOCUMENT_ROOT"]."/views-cache/",
                "debug"         => false // set to false to improve the speed

            );

            Tpl::configure( $config );

            $this -> tpl = new Tpl;

            $this -> setData($this -> options["data"]);

            if ($this -> options["header"] === true) $this -> tpl -> draw("header"); // VALIDAÇÃO

        }

        private function setData($data = array())
        {

            foreach ($data as $key => $value) {
                $this -> tpl -> assign($key, $value);
            }

        }

        // Para retornar um ou um HTML direto na tela
        public function setTpl($name, $data = array(), $returnHTML = false)
        {

            $this -> setData($data);
            return $this -> tpl -> draw($name, $returnHTML);

        }

        public function __destruct() // Último a ser executado
        {
            
            if ($this -> options["footer"] === true) $this -> tpl -> draw("footer"); // VALIDAÇÃO

        }

    }

?>