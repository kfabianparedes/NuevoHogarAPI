<?php

    header('Access-Control-Allow-Origin: *'); //Change
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../../clases/Mascota.php';
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

    if(esValido($datos,$mensaje)){

        $mascotaC = new Mascota($db);

        $mascotaC->MASCOTA_NOMBRE = $datos->MASCOTA_NOMBRE;
        $mascotaC->MASCOTA_COLOR = $datos->MASCOTA_COLOR; 
        $mascotaC->MASCOTA_SEXO = $datos->MASCOTA_SEXO; 
        $mascotaC->MASCOTA_FOTO = $datos->MASCOTA_FOTO;

        $exito = $mascotaC->registrarMascota($mensaje,$code_error);

        

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

    function esValido($d,&$m){
        
        if(!isset($d)){

            $m = "Los datos ingresados deben respetar el formato json";
            return false;

        }else{
            if(!isset($d->MASCOTA_NOMBRE)){
                $m = "El campo MASCOTA_NOMBRE no ha sido enviado";
                return false;
            }else{
                if($d->MASCOTA_NOMBRE==""){  
                    $m = "La variable MASCOTA_NOMBRE no puede ser null o vacía";
                    return false;  
                }else if(obtenerCantidadDeCaracteres($d->MASCOTA_NOMBRE)>30){
                    $m = "La variable MASCOTA_NOMBRE supera los 30 caracteres permitidos.";
                    return false;

                }else if(is_numeric($d->MASCOTA_NOMBRE)){
                    $m = "La variable MASCOTA_NOMBRE no acepta caracteres numéricos.";
                    return false;
                }
                
            }

            if(!isset($d->MASCOTA_COLOR)){
                $m = "El campo MASCOTA_COLOR no ha sido enviado";
                return false;
            }else{
                if($d->MASCOTA_COLOR==""){  
                    $m = "La variable MASCOTA_COLOR no puede ser null o vacía";
                    return false;  

                }else if(obtenerCantidadDeCaracteres($d->MASCOTA_COLOR)>20){
                    $m = "La variable MASCOTA_COLOR supera los 20 caracteres permitidos.";
                    return false;

                }else if(is_numeric($d->MASCOTA_COLOR)){
                    $m = "La variable MASCOTA_COLOR no acepta caracteres numéricos.";
                    return false;
                }
                
            }

            if(!isset($d->MASCOTA_SEXO)){
                $m = "El campo MASCOTA_SEXO no ha sido enviado";
                return false;
            }else{
                if($d->MASCOTA_SEXO==""){  
                    $m = "La variable MASCOTA_SEXO no puede ser null o vacía";
                    return false;  

                }else if(obtenerCantidadDeCaracteres($d->MASCOTA_SEXO)>20){
                    $m = "La variable MASCOTA_SEXO supera los 20 caracteres permitidos.";
                    return false;

                }else if(is_numeric($d->MASCOTA_SEXO)){
                    $m = "La variable MASCOTA_SEXO no acepta caracteres numéricos.";
                    return false;
                }
                
            }

            if(!isset($d->MASCOTA_FOTO)){
                $m = "El campo MASCOTA_FOTO no ha sido enviado";
                return false;
            }
            
        }

        return true;
    }

?>
