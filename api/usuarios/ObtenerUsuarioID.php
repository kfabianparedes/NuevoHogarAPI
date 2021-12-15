<?php
    header('Access-Control-Allow-Origin: *'); //Change
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: GET");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    //include_once '../../clases/Anuncios.php';
    include_once '../../clases/Usuarios.php';
    include_once '../../config/database.php';


    if($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
        return;
    }
    
    $database = new Database();
    $db = $database->getConnection();
    $usuario= new Usuarios($db);

    $mensaje = '';
    $exito = false;
    $code_error = null;
    
    $usuario->USU_ID= $_GET['USU_ID'];
    $exito = $usuario->obtenerUsuarioPorID($mensaje, $code_error);  
    if($exito){
        header('HTTP/1.1 200 OK');
        echo json_encode( array("error"=>$code_error, "mensaje"=>$mensaje,"exito"=>true, "resultado"=>$usuario));
    }else{
        header('HTTP/1.1 400 Bad Request');
        echo json_encode( array("error"=>$code_error, "mensaje"=>$mensaje,"exito"=>false));
    }
?>