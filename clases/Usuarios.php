<?php
    include_once '../../util/mysqlnd.php';

    class Usuarios{
        private $conn;

        public $USU_ID;
        public $USU_EMAIL;
        public $USU_PASSWORD; 
        public $USU_NOMBRES;
        public $USU_APELLIDOS;
        public $USU_TELEFONO;
        public $USU_DIRECCION; 
        public $ROL_ID;

        public function __construct($db){
            $this->conn = $db;
        }

        function login(&$mensaje ,&$code_error){
            $queryValidarEmail = "SELECT USU_EMAIL FROM USUARIOS WHERE USU_EMAIL  = ? ";
            $queryValidarContra = "SELECT USU_PASSWORD FROM USUARIOS WHERE USU_EMAIL = ? ";
            //$queryValidarRol="SELECT * FROM ROLES WHERE ROL_ID = ?";
            $query="SELECT USU_NOMBRES,USU_APELLIDOS,ROL_ID FROM USUARIOS WHERE USU_EMAIL=? AND USU_PASSWORD=?";
            try{
                //VALIDO EL QUERY EMAIL
                $stmt = $this->conn->prepare($queryValidarEmail);
                $stmt->bind_param("s",$this->USU_EMAIL);
                $stmt->execute();
                $resultadoEmail = get_result($stmt);
                //EVALUO EL RESULTADO DE EMAIL
                if(count($resultadoEmail)>0){
                    //VALIDO EL QUERY DE CONTRA
                    $stmt = $this->conn->prepare($queryValidarContra);
                    $stmt->bind_param("s",$this->USU_EMAIL);
                    $stmt->execute();
                    $resultadoContrasenia = get_result($stmt);
                    //echo $this->USU_EMAIL;
                    //json_encode(array("resultado"=>$resultadoContrasenia));
                    if(array_shift($resultadoContrasenia)['USU_PASSWORD']== $this->USU_PASSWORD){
                        // $mensaje="ES IGUAL";
                        // $code_error=null;
                        // return true;
                        $stmt = $this->conn->prepare($query);
                        $stmt->bind_param("ss",$this->USU_EMAIL,$this->USU_PASSWORD);
                        $stmt->execute();
                        $resultadoFinal = get_result($stmt);
                        if(count($resultadoFinal)>0){
                            $USU=array_shift($resultadoFinal);

                            //echo json_encode(array($USU['USU_NOMBRES'],$USU['USU_APELLIDOS'],$USU['ROL_ID']));
                            //echo array_shift($resultadoFinal)['USU_NOMBRES'];
                            //echo array_shift($resultadoFinal)['USU_APELLIDOS'];
                           //echo array_shift($resultadoFinal)['ROL_ID'];
                            $this->USU_NOMBRES=$USU["USU_NOMBRES"];
                            $this->USU_APELLIDOS=$USU["USU_APELLIDOS"];
                            $this->ROL_ID=$USU["ROL_ID"];
                            //echo json_encode(array("NOMBRE"=>$this->USU_NOMBRES,"APELLIDO"=>$this->USU_APELLIDOS,"ROL_ID"=>$this->ROL_ID));

                            $mensaje="LOGUEO EXITOSO";
                            $code_error=null;
                            return true;
                        }else{
                            $mensaje="DATOS INCORRECTOS";
                            $code_error="error_valores no validos";
                            return false;  
                        }
                    }
                    $mensaje="Error no coincide";
                    $code_error="error_sas";
                    return false;  
                }

                else{
                    $code_error = "error_emailiNEXISTENTE";
                    $mensaje = "El email ingresado es incorrecto";  
                    return false;  
                }
            }
            catch(Throwable  $e){
                $code_error = "error_deBD";
                $mensaje = "Ha ocurrido un error con la BD. No se pudo ejecutar la consulta.";  
                return false;     
            }
        }

        function registrar(&$mensaje,&$code_error){
            
            $queryValidarEmail = "SELECT * FROM USUARIOS WHERE USU_EMAIL  = ? ";
            $queryValidarRol = "SELECT * FROM ROLES WHERE ROL_ID = ? ";
            $query = "
            INSERT INTO USUARIOS(USU_EMAIL,USU_PASSWORD,USU_NOMBRES,USU_TELEFONO,USU_DIRECCION,ROL_ID)
            VALUES(?,?,?,?,?,?);
            "; 

            try{
                $stmt = $this->conn->prepare($queryValidarEmail);
                $stmt->bind_param("s",$this->USU_EMAIL);
                $stmt->execute();
                $result0 = get_result($stmt);

                if(count($result0 )== 0){
                    
                    $stmt = $this->conn->prepare($queryValidarRol);
                    $stmt->bind_param("s",$this->ROL_ID);
                    $stmt->execute();
                    $result1 = get_result($stmt);

                    if(count($result1 ) > 0 ){
                        // $hash = password_hash($this->USU_PASSWORD,PASSWORD_DEFAULT);
                        $stmt = $this->conn->prepare($query);
                        $stmt->bind_param("ssssss",$this->USU_EMAIL,$this->USU_PASSWORD,$this->USU_NOMBRES,
                        $this->USU_TELEFONO,$this->USU_DIRECCION,$this->ROL_ID);
                        //verificamos que se haya realizado correctamente el ingreso de la compra
                        if(!$stmt->execute()){

                            $code_error = "error_ejecucionQuery";
                            $mensaje = "Hubo un error al registrar un usuario.";
                            return false; 

                        }else{

                            $mensaje = "Usuario registrado con éxito.";
                            return true; 

                        }

                    }else{
                        
                        $code_error = "error_NoExistenciaRol";
                        $mensaje = "El id del rol ingresado no existe.";  
                        return false;  

                    }

                }else{
                    
                    $code_error = "error_emailExistente";
                    $mensaje = "El email ingresado ya pertenece a una cuenta.";  
                    return false;  

                }
                
            }catch(Throwable  $e){
                $code_error = "error_deBD";
                $mensaje = "Ha ocurrido un error con la BD. No se pudo ejecutar la consulta.";  
                return false;     
            }
        }

        function editar(&$mensaje,&$code_error){
            
            $queryValidarEmail = "SELECT * FROM USUARIOS WHERE USU_EMAIL  = ? AND USU_ID <> ?";
            $queryValidarUsuario = "SELECT * FROM USUARIOS WHERE USU_ID = ?"; 
            $query = "
            UPDATE USUARIOS SET USU_EMAIL = ?, USU_PASSWORD = ?, USU_NOMBRES = ?, USU_TELEFONO = ?, USU_DIRECCION = ? WHERE USU_ID = ?

            "; 

            try{
                $stmt = $this->conn->prepare($queryValidarUsuario);
                $stmt->bind_param("s",$this->USU_ID);
                $stmt->execute();
                $result0 = get_result($stmt);

                if(count($result0 )== 0){
                    
                    $stmt = $this->conn->prepare($queryValidarEmail);
                    $stmt->bind_param("ss",$this->USU_EMAIL,$this->USU_ID);
                    $stmt->execute();
                    $result1 = get_result($stmt);

                    if(count($result1 ) > 0 ){
                        
                        $stmt = $this->conn->prepare($query);
                        $stmt->bind_param("ssssss",$this->USU_EMAIL,$this->USU_PASSWORD,$this->USU_NOMBRES,
                        $this->USU_TELEFONO,$this->USU_DIRECCION,$this->USU_ID);
                        //verificamos que se haya realizado correctamente el ingreso de la compra
                        if(!$stmt->execute()){

                            $code_error = "error_ejecucionQuery";
                            $mensaje = "Hubo un error al registrar un usuario.";
                            return false; 

                        }else{

                            $mensaje = "Usuario registrado con éxito.";
                            return true; 

                        }

                    }else{
                        
                        $code_error = "error_emailExistente";
                        $mensaje = "El email ingresado ya pertenece a una cuenta.";  
                        return false;  

                    }

                }else{
                    
                    $code_error = "error_NoExistenciaUsuario";
                    $mensaje = "El id del usuario ingresado no existe.";  
                    return false;    

                }
                
            }catch(Throwable  $e){
                $code_error = "error_deBD";
                $mensaje = "Ha ocurrido un error con la BD. No se pudo ejecutar la consulta.";  
                return false;     
            }
        }


    }

?>