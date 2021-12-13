<?php
    header('Access-Control-Allow-Origin: *'); //Change
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../../clases/Usuarios.php';
    include_once '../../config/database.php';
    include_once '../../util/validaciones.php';


    if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
        return;
    }
    
    $database = new Database();
    $db = $database->getConnection();

    $mensaje = '';
    $exito = false;
    $code_error = null;
    $anuncios = [];

    $datos = json_decode(file_get_contents("php://input"));
    if(esValido($mensaje,$datos)){
    
        $usuario= new Usuarios($db);
        
        $usuario->USU_EMAIL = $datos->USU_EMAIL;
        $usuario->USU_PASSWORD = $datos->USU_PASSWORD; 
        $usuario->USU_NOMBRES = $datos->USU_NOMBRES;
        $usuario->USU_TELEFONO = $datos->USU_TELEFONO;
        $usuario->USU_DIRECCION = $datos->USU_DIRECCION; 
        $usuario->ROL_ID = $datos->ROL_ID;
            
        $exito = $usuario->registrar($mensaje, $code_error);  
        if($exito == true)
                header('HTTP/1.1 200 OK');
        else{
            header('HTTP/1.1 400 Bad Request');
        }

        echo json_encode( array("error"=>$code_error,"mensaje"=>$mensaje,"exito"=>$exito));

    }else{

        $code_error = "error_deCampo";
        echo json_encode(array("error"=>$code_error,"mensaje"=>$mensaje, "exito"=>false));
        header('HTTP/1.1 400 Bad Request');
        
    }

    function esValido(&$m,$d){

        if(!isset($d)){
            $m = "Los datos ingresados deben respetar el formato json";
            return false;
        }else{

            if(!isset($d->ROL_ID)){
                $m = "El campo ROL_ID no ha sido enviado";
                return false;
            }else{
                if(!is_numeric($d->ROL_ID)){
                    $m = "El campo ROL_ID debe ser numérico";
                    return false;
                }else{
                    if($d->ROL_ID <1 || $d->ROL_ID >3){
                        $m = "El valor de ROL_ID debe no debe ser menor que 1 o mayor que 3.";
                        return false;
                    }
                }
            }

            if(!isset($d->USU_DIRECCION)){
                $m = "El campo USU_DIRECCION no ha sido enviado";
                return false;
            }else{
                if($d->USU_DIRECCION==""){  
                    $m = "La variable USU_DIRECCION no puede ser null o vacía";
                    return false;  
                }else{
                    if(obtenerCantidadDeCaracteres($d->USU_DIRECCION)>100){
                        $m = "La variable USU_DIRECCION supera los 100 caracteres permitidos.";
                        return false;
                    }
                }
            }

            if(!isset($d->USU_TELEFONO)){
                $m = "El campo USU_TELEFONO no ha sido enviado.";
                return false;
            }else{
                if($d->USU_TELEFONO==""){
                    $m = "El campo USU_TELEFONO no puede estar vacío.";
                    return false;
                }else{
                    if(obtenerCantidadDeCaracteres($d->USU_TELEFONO)>12){
                        $m = "La variable USU_TELEFONO no debe exceder de 12 caracteres.";
                        return false;
                    }else{
                        if(!verificarCelular($d->USU_TELEFONO)){
                            $m = "La varaible USU_TELEFONO no tiene el formato permitido.";
                            return false;
                        }
                    }
                }
            }

            if(!isset($d->USU_NOMBRES)){
                $m = "La variable USU_NOMBRES no ha sido enviada.";
                return false;
            }else{  
                if($d->USU_NOMBRES == ""){
                    $m = "La variable USU_NOMBRES no puede estar vacía o ser null.";
                    return false; 
                }else{
                    if(!esTextoAlfabetico(trim($d->USU_NOMBRES))){
                        $m = "La variable USU_NOMBRES debe ser alfabético.";
                        return false;
                    }
                    else if(obtenerCantidadDeCaracteres($d->USU_NOMBRES)>45){
                        $m = "La variable USU_NOMBRES no puede ser mayor a 45 caracteres.";
                        return false; 
                    }
                }
            }


            if(!isset($d->USU_EMAIL)){
                $m = "El campo USU_EMAIL no ha sido enviado";
                return false;
            }else{
                if($d->USU_EMAIL==""){
                    $m = "La variable USU_EMAIL no debe estar vacía.";
                    return false;
                }else{
                    if(obtenerCantidadDeCaracteres($d->USU_EMAIL)>60){
                        $m = "La variable USU_EMAIL no debe exceder los 60 caracteres.";
                        return false;
                    }else{
                        if(!filter_var($d->USU_EMAIL, FILTER_VALIDATE_EMAIL)){
                            $m = "La variable USU_EMAIL no tiene un formato valido.";
                            return false;
                        }
                    }
                }
            }

            if(!isset($d->USU_PASSWORD)){
                $m = "La variable USU_PASSWORD no ha sido enviada.";
                return false;
            }else{  
                if($d->USU_PASSWORD == ""){
                    $m = "La variable USU_PASSWORD no puede estar vacía o ser null.";
                    return false; 
                }else{
                    if(obtenerCantidadDeCaracteres($d->USU_PASSWORD)<8 || obtenerCantidadDeCaracteres($d->USU_PASSWORD)>20){
                        $m = "La variable USU_PASSWORD no puede ser menor a 8 ni mayor a 20 caracteres.";
                        return false; 
                    }
                }
            }

        }

        return true; 
    }

?>