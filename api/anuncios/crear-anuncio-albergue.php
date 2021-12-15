<?php
    header('Access-Control-Allow-Origin: *'); //Change
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../../clases/AnuncioAlbergue.php';
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
        $anuncioAlbuergue= new AnuncioAlbergue($db);
        $anuncioAlbuergue->ANUN_AL_DESCRIPCION = $datos->ANUN_AL_DESCRIPCION; 
        $anuncioAlbuergue->ANUN_AL_DIRECCION = $datos->ANUN_AL_DIRECCION; 
        $anuncioAlbuergue->ANUN_AL_TELEFONO = $datos->ANUN_AL_TELEFONO; 
        $anuncioAlbuergue->MASCOTA_ID = $datos->MASCOTA_ID; 
        $anuncioAlbuergue->USU_ID = $datos->USU_ID;
            
        $exito = $anuncioAlbuergue->crearAnuncioAlbergue($mensaje, $code_error);  
    
        if($exito == true)
                header('HTTP/1.1 200 OK');
        else{
            header('HTTP/1.1 400 Bad Request');
        }
    
        echo json_encode( array("error"=>$code_error,"mensaje"=>$mensaje,"exito"=>$exito));
    
    }else{
        $code_error = "error de campo";
        echo json_encode( array("error"=>$code_error,"mensaje"=>$mensaje,"exito"=>false));
        header('HTTP/1.1 400 Bad Request');
    }

    

    function esValido(&$m,$d){
        
        if(!isset($d)){
            $m = "Los datos ingresados deben respetar el formato json";
            return false;
        }else{
            if(!isset($d -> USU_ID)){
                $m = "Es necesario completar el campo USU_ID. ";
                return false;
            }else{
                if(!is_numeric($d -> USU_ID)){
                    $m = "El campo USU_ID debe ser numérico.";
                    return false;
                }else{
                    if($d->USU_ID < 1 || $d->USU_ID > 3){
                        $m = "El valor de USU_ID no debe ser menor que 1 o mayor que 3.";
                        return false;
                    }
                }
            }

            if(!isset($d -> MASCOTA_ID)){
                $m = "Es necesario completar el campo MASCOTA_ID. ";
                return false;
            }else{
                if(!is_numeric($d -> MASCOTA_ID)){
                    $m = "El campo MASCOTA_ID debe ser numérico.";
                    return false;
                }else{
                    if($d->MASCOTA_ID ==""){
                        $m = "El campo MASCOTA_ID no puede estar vacío.";
                        return false;
                    }
                }
            }

            if(!isset($d->ANUN_AL_TELEFONO)){
                $m = "El campo ANUN_AL_TELEFONO no ha sido enviado.";
                return false;
            }else{
                if($d->ANUN_AL_TELEFONO==""){
                    $m = "El campo ANUN_AL_TELEFONO no puede estar vacío.";
                    return false;
                }else{
                    if(obtenerCantidadDeCaracteres($d->ANUN_AL_TELEFONO)>12){
                        $m = "La variable ANUN_AL_TELEFONO no debe exceder de 12 caracteres.";
                        return false;
                    }else{
                        if(!verificarCelular($d->ANUN_AL_TELEFONO)){
                            $m = "La varaible ANUN_AL_TELEFONO no tiene el formato permitido.";
                            return false;
                        }
                    }
                }
            }

            if(!isset($d->ANUN_AL_DIRECCION)){
                $m = "El campo ANUN_AL_DIRECCION no ha sido enviado";
                return false;
            }else{
                if($d->ANUN_AL_DIRECCION==""){  
                    $m = "La variable ANUN_AL_DIRECCION no puede ser null o vacía";
                    return false;  
                }else{
                    if(obtenerCantidadDeCaracteres($d->ANUN_AL_DIRECCION)>100){
                        $m = "La variable ANUN_AL_DIRECCION supera los 100 caracteres permitidos.";
                        return false;
                    }
                }
            }
            
            if(!isset($d->ANUN_AL_DESCRIPCION)){
                $m = "El campo ANUN_AL_DESCRIPCION no ha sido enviado";
                return false;
            }else{
                if($d->ANUN_AL_DESCRIPCION==""){  
                    $m = "La variable ANUN_AL_DESCRIPCION no puede ser null o vacía";
                    return false;  
                }else{
                    if(obtenerCantidadDeCaracteres($d->ANUN_AL_DESCRIPCION)>500){
                        $m = "La variable ANUN_AL_DESCRIPCION supera los 500 caracteres permitidos.";
                        return false;
                    }
                }
            }
        }

        return true;
    }

?>