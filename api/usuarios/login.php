<?php
    header('Access-Control-Allow-Origin: *'); //Change
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type");

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
        echo strlen($d->USU_EMAIL);
        if(!isset($d)){
            $m = "Los datos ingresados deben respetar el formato json";
            return false;
        }else{

            if(!isset($d->USU_EMAIL)){
                $m = "El campo USU_EMAIL no ha sido enviado";
                return false;
            }else{
                if( strlen($d->USU_EMAIL) <= 0){
                    $m = "La variable USU_EMAIL no debe estar vacía.";
                    return false;
                }else{
                    if(obtenerCantidadDeCaracteres($d->USU_EMAIL)>50){
                        $m = "La variable USU_EMAIL no debe exceder los 50 caracteres.";
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
                if( strlen($d->USU_PASSWORDL) <= 0){
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