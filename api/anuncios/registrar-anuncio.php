<?php
    header('Access-Control-Allow-Origin: *'); //Change
    header("Content-Type: application/json; charset=UTF-8");
    header("Access-Control-Allow-Methods: POST");
    header("Access-Control-Max-Age: 3600");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

    include_once '../../clases/Anuncios.php';
    include_once '../../config/database.php';


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
    
        $anuncio= new Anuncios($db);

        $anuncio->AN_FOTO = $datos->AN_FOTO; 
        $anuncio->AN_DESCRIPCION = $datos->AN_DESCRIPCION; 
        $anuncio->AN_DIRECCION = $datos->AN_DIRECCION; 
        $anuncio->AN_FECHA = $datos->AN_FECHA; 
        $anuncio->USU_ID = $datos->USU_ID;
            
        $exito = $anuncio->registrar($mensaje, $code_error);  

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

        return true; 
    }

?>