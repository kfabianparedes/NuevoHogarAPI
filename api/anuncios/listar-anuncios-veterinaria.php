<?php
    header('Access-Control-Allow-Origin: *'); //Change
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../../clases/AnuncioVeterinaria.php';
    include_once '../../config/database.php';


    if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
        return;
    }
    
    $database = new Database();
    $db = $database->getConnection();
    $anuncio= new AnuncioVeterinaria($db);

    $mensaje = '';
    $exito = false;
    $code_error = null;
    $anuncios = [];

    $anuncios = $anuncio->listarAnunciosVeterinarias($mensaje, $exito, $code_error);  
    if($exito){
        header('HTTP/1.1 200 OK');
        echo json_encode( array("error"=>$code_error, "resultado"=>$anuncios, "mensaje"=>$mensaje,"exito"=>true));
    }else{
        header('HTTP/1.1 400 Bad Request');
        echo json_encode( array("error"=>$code_error, "resultado"=>$anuncios, "mensaje"=>$mensaje,"exito"=>false));
    }

?>
