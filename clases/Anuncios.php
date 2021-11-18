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
            
            #QUERY VARA REGISTRAR USUARIOS
            $query = 
            "
            INSERT INTO ANUNCIOS(AN_FOTO,AN_DESCRIPCION,AN_DIRECCION,AN_FECHA,USU_ID)
            VALUES(?,?,?,?,?); 
            ";

            #QUERY PARA VERIFICAR SI EL USUARIO EXISTE 
            $queryVerificarUsuId =
            "
            SELECT * FROM USUARIOS WHERE USU_ID = ?; 
            ";

            try {

                $stmtValidarUsuario = $this->conn->prepare($queryVerificarUsuId);
                $stmtValidarUsuario->bind_param("s",$this->USU_ID);
                $stmtValidarUsuario->execute();
                $resultValidarUsuario= get_result($stmtValidarUsuario);
                //validamos si existe el id del usuario ingresado
                if(count($resultValidarUsuario) > 0){

                    $stmt = $this->conn->prepare($query);
                    $stmt->bind_param("sssss",$this->AN_FOTO,$this->AN_DESCRIPCION,$this->AN_DIRECCION,$this->AN_FECHA,$this->USU_ID);
                    //verificamos que se haya realizado correctamente el ingreso de la compra
                    if(!$stmt->execute()){

                        $code_error = "error_ejecucionQuery";
                        $mensaje = "Hubo un error al registrar un anuncio.";
                        return false; 

                    }else{

                        $mensaje = "Anuncio registrado con éxito.";
                        return true; 

                    }

                }else{

                    $code_error = "error_NoExistenciaUsuario";
                    $mensaje = "El id del usuario ingresado no se encuentra registrado.";
                    return false; 

                }


            } catch (Throwable $th) {
                
                $code_error = "error_deBD";
                $mensaje = "Ha ocurrido un error con la BD. No se pudo ejecutar la consulta.";
                $exito = false;
                return $datos;

            }
        }

        function editar(&$mensaje,&$code_error){
            
            #QUERY VARA REGISTRAR USUARIOS
            $query = 
            "
            INSERT INTO ANUNCIOS(AN_FOTO,AN_DESCRIPCION,AN_DIRECCION,AN_FECHA,USU_ID)
            VALUES(?,?,?,?,?); 
            ";

            #QUERY PARA VERIFICAR SI EL USUARIO EXISTE 
            $queryVerificarUsuId =
            "
            SELECT * FROM ANUNCIOS WHERE USU_ID = ? AND AN_ID = ?; 
            ";

            $queryVerificarAnId = 
            "
            SELECT * FROM ANUNCIOS WHERE AN_ID = ?;
            ";

            try {

                    $stmtValidarAnuncio = $this->conn->prepare($queryVerificarAnId);
                    $stmtValidarAnuncio->bind_param("s",$this->AN_ID);
                    $stmtValidarAnuncio->execute();
                    $resultValidarAnuncio= get_result($stmtValidarAnuncio);
                    //validamos si existe el id del usuario ingresado
                    if(count($resultValidarAnuncio) > 0){

                        $stmtValidarUsuario = $this->conn->prepare($queryVerificarUsuId);
                        $stmtValidarUsuario->bind_param("s",$this->USU_ID,$this->AN_ID);
                        $stmtValidarUsuario->execute();
                        $resultValidarUsuario= get_result($stmtValidarUsuario);
                        //validamos si existe el id del usuario ingresado
                        if(count($resultValidarUsuario) > 0){

                            $stmt = $this->conn->prepare($query);
                            $stmt->bind_param("sssss",$this->AN_FOTO,$this->AN_DESCRIPCION,$this->AN_DIRECCION,$this->AN_FECHA,$this->USU_ID,$this->AN_ID);
                            //verificamos que se haya realizado correctamente el ingreso de la compra
                            if(!$stmt->execute()){

                                $code_error = "error_ejecucionQuery";
                                $mensaje = "Hubo un error al registrar un anuncio.";
                                return false; 

                            }else{

                                $mensaje = "Anuncio registrado con éxito.";
                                return true; 

                            }

                        }else{

                            $code_error = "error_NoExistenciaUsuario";
                            $mensaje = "El id del usuario ingresado no se encuentra registrado o ese usuario no tiene permitido editar este anuncio.";
                            return false; 
        
                        }

                    }else {

                        $code_error = "error_NoExistenciaAnuncio";
                        $mensaje = "El id del anuncio ingresado no se encuentra registrado.";
                        return false; 

                    }

                


            } catch (Throwable $th) {
                
                $code_error = "error_deBD";
                $mensaje = "Ha ocurrido un error con la BD. No se pudo ejecutar la consulta.";
                $exito = false;
                return $datos;

            }
        }

        function listarAnunciosVeterinarias(&$mensaje, &$exito, &$code_error){
            
            $query="
            select AN.* from ANUNCIOS AN 
            INNER JOIN USUARIOS USU ON (AN.USU_ID = USU.USU_ID)
            INNER JOIN ROLES ROL ON (ROL.ROL_ID = USU.ROL_ID)
            WHERE ROL.ROL_ID = 3;
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

        function listarAnunciosAlbergues(&$mensaje, &$exito, &$code_error){
            
            $query="
            select AN.* from ANUNCIOS AN 
            INNER JOIN USUARIOS USU ON (AN.USU_ID = USU.USU_ID)
            INNER JOIN ROLES ROL ON (ROL.ROL_ID = USU.ROL_ID)
            WHERE ROL.ROL_ID = 2 ;  
            ";

            $datos =[];

            try {
                
                $stmt = $this->conn->prepare($query);
                if(!$stmt->execute()){

                    $code_error = "error_ejecucionQuery";
                    $mensaje = "Hubo un error al listar los anuncios de albergues.";
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