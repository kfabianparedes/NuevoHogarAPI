<?php
    include_once '../../util/mysqlnd.php';

    class Usuarios{
        private $conn;

        public $USU_ID;
        public $USU_EMAIL;
        public $USU_PASSWORD; 
        public $USU_NOMBRES;
        public $USU_TELEFONO;
        public $USU_DIRECCION; 
        public $ROL_ID;

        public function __construct($db){
            $this->conn = $db;
        }

        function login(&$mensaje ,&$code_error){
            
        }

        function registrar(&$mensaje,&$code_error){
            $query = "";
            
            try{
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("s",$this->USU_EMAIL);
                $stmt->execute();
                $result0 = get_result($stmt);
                if(count($result0)>0){
                    $code_error = "error_emailExistente";
                    $mensaje = "El email ingresado ya pertenece a una cuenta.";  
                    return false;    
                }else{
                    
                }
                
            }catch(Throwable  $e){
                $code_error = "error_deBD";
                $mensaje = "Ha ocurrido un error con la BD. No se pudo ejecutar la consulta.";  
                return false;     
            }
        }

    }

?>