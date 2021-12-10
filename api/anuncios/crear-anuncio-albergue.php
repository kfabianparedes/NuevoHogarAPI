<?php
    header('Access-Control-Allow-Origin: *'); //Change
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../../clases/AnuncioAlbergue.php';
    include_once '../../config/database.php';
    
    if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
        return;
    }

    $database = new Database();
    $db = $database->getConnection();

    $mensaje = '';
    $exito = false;
    $code_error = null;
    

    $datos = json_decode(file_get_contents("php://input"));

    
    $anuncioAlbuergue= new AnuncioAlbergue($db);
    //ANUN_AL_DESCRIPCION,ANUN_AL_DIRECCION,ANUN_AL_TELEFONO,MASCOTA_ID,USU_ID
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

?>