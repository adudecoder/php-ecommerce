<?php

    namespace Hcode;

    class PageAdmin extends Page { // Herança

        public function __construct($opts = array(), $tpl_dir = "/views/admin/")
        {
            
            // Usando o construtor da classe Page
            parent::__construct($opts, $tpl_dir); // Passando os parametros do construtor PageAdmin
            
        }

    }

?>