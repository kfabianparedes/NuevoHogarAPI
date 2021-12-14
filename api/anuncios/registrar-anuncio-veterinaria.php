<?php
    header('Access-Control-Allow-Origin: *'); //Change
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../../clases/AnuncioVeterinaria.php';
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
    
        $anuncio= new AnuncioVeterinaria($db);

        $anuncio->ANUN_VET_DESCRIPCION = $datos->ANUN_VET_DESCRIPCION; 
        $anuncio->ANUN_VET_DIRECCION = $datos->ANUN_VET_DIRECCION; 
        $anuncio->ANUN_VET_FECHA = $datos->ANUN_VET_FECHA; 
        $anuncio->ANUN_VET_FOTO = $datos->ANUN_VET_FOTO; 
        $anuncio->USU_ID = $datos->USU_ID;
            
        $exito = $anuncio->crearAnuncioVeterinaria($mensaje, $code_error);  

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
        //echo strlen($d->USU_EMAIL);
    if(!isset($d)){
        $m = "Los datos ingresados deben respetar el formato json";
        return false;
    }else{

        if(!isset($d->ANUN_VET_DESCRIPCION)){
            $m = "El campo VET_DESCRIPCION no ha sido enviado";
            return false;
        }else{
            if( strlen($d->ANUN_VET_DESCRIPCION) <= 0){
                $m = "La variable ANUN_VET_DESCRIPCION no debe estar vacía.";
                return false;
            }else{
                if(obtenerCantidadDeCaracteres($d->ANUN_VET_DESCRIPCION)>200){
                    $m = "La variable ANUN_VET_DESCRIPCION no debe exceder los 200 caracteres.";
                    return false;
                }
            }
        }
    }
            
    if(!isset($d)){
        $m = "Los datos ingresados deben respetar el formato json";
        return false;
    }else{
        if(!isset($d->ANUN_VET_DIRECCION)){
            $m = "El campo VET_DIRECCION no ha sido enviado";
            return false;
        }else{
            if( strlen($d->ANUN_VET_DIRECCION) <= 0){
                $m = "La variable ANUN_VET_DIRECCION no debe estar vacía.";
                return false;
            }else{
                if(obtenerCantidadDeCaracteres($d->ANUN_VET_DIRECCION)>100){
                    $m = "La variable ANUN_VET_DIRECCION no debe exceder los 100 caracteres.";
                    return false;
                }
            }
        }           
    }
            
    
    if(!isset($d)){
        $m = "Los datos ingresados deben respetar el formato json";
        return false;
    }else{
        if(!isset($d->ANUN_VET_FECHA)){
            $m = "El campo VET_FECHA no ha sido enviado";
            return false;
        }else{
            if( strlen($d->ANUN_VET_FECHA) <= 0){
                $m = "La variable ANUN_VET_FECHA no debe estar vacía.";
                return false;
            }
        }           
    }

    if(!isset($d)){
        $m = "Los datos ingresados deben respetar el formato json";
        return false;
    }else{
        if(!isset($d->ANUN_VET_FOTO)){
            $m = "El campo VET_FOTO no ha sido enviado";
            return false;
        }else{
                if(obtenerCantidadDeCaracteres($d->ANUN_VET_FOTO)>500){
                    $m = "La variable ANUN_VET_FOTO no debe exceder los 500 caracteres.";
                    return false;
                }
        }           
    }

    if(!isset($d)){
        $m = "Los datos ingresados deben respetar el formato json";
        return false;
    }else{
        if(!isset($d->USU_ID)){
            $m = "El campo USU_ID no ha sido enviado";
            return false;
        }else{
            if( strlen($d->USU_ID) <= 0){
                $m = "La variable USU_ID no debe estar vacía.";
                return false;
            }else{
                if(is_numeric($d->USU_ID)==false){
                    $m = "El usu ID Tiene que ser un numero";
                    return false;
                }
                else{
                    if( $d->USU_ID<= 0){
                        $m = "La variable USU_ID no puede ser 0 por que no existe un usuario 0";
                        return false;
                    }
                }               
            }           
        }    
                return true; 
    }
}
?>