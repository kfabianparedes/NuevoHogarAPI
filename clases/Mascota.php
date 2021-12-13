<?php
    
    include_once '../../util/mysqlnd.php';

    class Mascota{
        private $conn;

        public $MASCOTA_ID; 
        public $MASCOTA_NOMBRE;
        public $MASCOTA_COLOR; 
        public $MASCOTA_SEXO; 
        public $MASCOTA_FOTO;

        public function __construct($db){
            $this->conn = $db;
        }

        function registrarMascota(&$mensaje,&$code_error){
            
            $query = "CALL SP_REGISTRAR_MASCOTA(?,?,?,?)"; 

            try {

                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("ssss",$this->MASCOTA_NOMBRE,$this->MASCOTA_COLOR,$this->MASCOTA_SEXO,$this->MASCOTA_FOTO);

                if(!$stmt->execute()){

                    $code_error = "error_ejecucionQuery";
                    $mensaje = "Hubo un error al registrar la mascota.";
                    return false; 

                }else{

                    $code_error = null;
                    $mensaje = "Se registró la mascota correctamente.";
                    return true;

                }

            } catch (Throwable $th) {

                $code_error = "error_deBD";
                $mensaje = "Ha ocurrido un error con la BD. No se pudo ejecutar la consulta.";  
                return false;     

            }
                
        }

        function editarMascota(&$mensaje,&$code_error){
            
            $query = "CALL SP_EDITAR_MASCOTA(@VALIDACIONES,?,?,?,?,?)";

            $queryValidaciones = "SELECT @VALIDACIONES"; 

            try {

                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("sssss",$this->MASCOTA_ID,$this->MASCOTA_NOMBRE,$this->MASCOTA_COLOR,$this->MASCOTA_SEXO,$this->MASCOTA_FOTO);

                if(!$stmt->execute()){

                    $code_error = "error_ejecucionQuery";
                    $mensaje = "Hubo un error al actualizar la mascota.";
                    return false; 

                }else{

                    $stmtValidaciones = $this->conn->prepare($queryValidaciones);
                    $stmtValidaciones->execute();
                    $resultValidaciones = get_result($stmtValidaciones); 

                    if (count($resultValidaciones) > 0) {
                        //obtenemos verdadero o falso dependiendo si es que se repite el nro de comprobante de la guía que se ingresará 
                        $validaciones = array_shift($resultValidaciones)["@VALIDACIONES"];
                    }

                    switch ($validaciones) {

                        case 1:

                            $code_error = "error_NoExistenciaIdMascota";
                            $mensaje = "El id de la mascota ingresado no existe.";  
                            return false; 
                            break;

                        case 0:

                            $code_error = null;
                            $mensaje = "Se actualizó la mascota correctamente.";
                            return true;
                            break; 

                    }

                }

            } catch (Throwable $th) {

                $code_error = "error_deBD";
                $mensaje = "Ha ocurrido un error con la BD. No se pudo ejecutar la consulta.";  
                return false;     

            }
                
        }

        function obtenerMascotas(&$mensaje, &$exito, &$code_error){
            $query = "SELECT * FROM MASCOTA";
            $datos = [];
            try{
                $stmt = $this->conn->prepare($query);
                $stmt->execute();
                $result = get_result($stmt); 
                
                if (count($result) > 0) {                
                    while ($dato = array_shift($result)) {    
                        $datos[] = $dato;
                    }
                }
                $mensaje = "Solicitud ejecutada con exito";
                $exito = true;
                return $datos;
        
            }catch(Throwable  $e){
                $code_error = "error_deBD";
                $mensaje = "Ha ocurrido un error con la BD. No se pudo ejecutar la consulta.";
                $exito = false;
                return $datos;
            } 
        }

    }

    