<?php
    include_once '../../util/mysqlnd.php';
    class AnuncioVeterinaria{
        private $conn;

        public $ANUN_VET_ID;
        public $ANUN_VET_DESCRIPCION;
        public $ANUN_VET_DIRECCION;
        public $ANUN_VET_FECHA;
        public $ANUN_VET_FOTO;
        public $USU_ID;


        public function __construct($db){
            $this->conn = $db;
        }
        // //FUNCION PARA LISTAR ANUNCIOS ALBERGUES
        // function listarAnunciosVeterinaria(&$mensaje, &$exito, &$code_error){
            
        //     $query="
        //     select AN.ANUN_AL_DESCRIPCION, AN.ANUN_AL_DIRECCION, AN.ANUN_AL_TELEFONO, 
        //     MASC.MASCOTA_NOMBRE, MASC.MASCOTA_COLOR, MASC.MASCOTA_SEXO, MASC.MASCOTA_FOTO,
        //     USU.USU_NOMBRES 
        //     from ANUNCIOS_ALBERGUE AN 
        //     INNER JOIN USUARIOS USU ON (AN.USU_ID = USU.USU_ID)
        //     INNER JOIN MASCOTA MASC ON (AN.MASCOTA_ID = MASC.MASCOTA_ID);  
        //     ";

        //     $datos =[];

        //     try {
                
        //         $stmt = $this->conn->prepare($query);
        //         if(!$stmt->execute()){

        //             $code_error = "error_ejecucionQuery";
        //             $mensaje = "Hubo un error al listar los anuncios de albergues.";
        //             $exito = false; 

        //         }else{

        //             $result = get_result($stmt); 
                
        //             if (count($result) > 0) {                
        //                 while ($dato = array_shift($result)) {    
        //                     $datos[] = $dato;
        //                 }
        //             }

        //             $mensaje = "Solicitud ejecutada con exito";
        //             $exito = true;
                    
        //         }

        //         return $datos;

        //     } catch (Throwable $th) {

        //         $code_error = "error_deBD";
        //         $mensaje = "Ha ocurrido un error con la BD. No se pudo ejecutar la consulta.";
        //         $exito = false;
        //         return $datos;

        //     }
        // }
        //FUNCION PARA CREAR ANUNCIO ALBERGUE
        function crearAnuncioVeterinaria(&$mensaje,&$code_error){
            $query = "
            INSERT INTO ANUNCIOS_VETERINARIA(ANUN_VET_DESCRIPCION,ANUN_VET_DIRECCION,ANUN_VET_FECHA,ANUN_VET_FOTO,USU_ID)
            VALUES(?,?,?,?,?);
            "; 

            try{
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("sssss",$this->ANUN_VET_DESCRIPCION,$this->ANUN_VET_DIRECCION,$this->ANUN_VET_FECHA,$this->ANUN_VET_FOTO,$this->USU_ID);
                if(!$stmt->execute()){
                    $code_error = "error_ejecucionQuery";
                    $mensaje = "Hubo un error al crear los anuncios de la veterinaria.";
                    return false; 
                }else{
                    $code_error = null;
                    $mensaje = "Se creo el anuncio de la veterinaria correctamente.";
                    return true;
                }
            }catch(Throwable  $e){
                $code_error = "error_deBD";
                $mensaje = "Ha ocurrido un error con la BD. No se pudo ejecutar la consulta.";  
                return false;     
            }
        }
    }
?>