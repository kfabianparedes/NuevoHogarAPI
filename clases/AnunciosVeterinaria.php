<?php
    include_once '../../util/mysqlnd.php';

    class AnunciosVeterinaria{
        private $conn;

        public $ANUN_VET_ID;
        public $ANUN_VET_DESCRIPCION; 
        public $ANUN_VET_DIRECCION; 
        public $ANUN_VET_FECHA; 
        public $ANUN_VET_FOTO; 
        public $USU_ID;

        public function __construct($db){
            $this->conn = $db;
        }

        function listarAnunciosVeterinarias(&$mensaje, &$exito, &$code_error){
            
            $query="
            select ANVET.ANUN_VET_DESCRIPCION, ANVET.ANUN_VET_DIRECCION, ANVET.ANUN_VET_FECHA, ANVET.ANUN_VET_FOTO, USU.USU_NOMBRES  FROM ANUNCIOS_VETERINARIA ANVET
            INNER JOIN USUARIOS USU ON (ANVET.USU_ID = USU.USU_ID);
            ";

            $datos =[];

            try {

                $stmt = $this->conn->prepare($query);
                if(!$stmt->execute()){

                    $code_error = "error_ejecucionQuery";
                    $mensaje = "Hubo un error al listar los anuncios de veterinarias.";
                    $exito = false; 

                }else{

                    $result = get_result($stmt); 
                
                    if (count($result) > 0) {                
                        while ($dato = array_shift($result)) {    
                            $datos[] = $dato;
                        }
                    }

                    $mensaje = "Solicitud ejecutada con exito";
                    $exito = true;
                    
                }

                return $datos;

            } catch (Throwable $th) {

                $code_error = "error_deBD";
                $mensaje = "Ha ocurrido un error con la BD. No se pudo ejecutar la consulta.";
                $exito = false;
                return $datos;

            }
        }
    }    
?>