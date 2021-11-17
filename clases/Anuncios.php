<?php
    include_once '../../util/mysqlnd.php';

    class Anuncios{
        private $conn;

        public $AN_ID;
        public $AN_FOTO; 
        public $AN_DESCRIPCION; 
        public $AN_DIRECCION; 
        public $AN_FECHA; 
        public $USU_ID;

        public function __construct($db){
            $this->conn = $db;
        }

        function registrar(&$mensaje,&$code_error){

        }

        function listarAnunciosVeterinarias(&$mensaje, &$exito, &$code_error){

        }

        function listarAnunciosAlbergues(&$mensaje, &$exito, &$code_error){

        }
    }   

?>