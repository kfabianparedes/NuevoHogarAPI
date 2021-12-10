<?php
    include_once '../../util/mysqlnd.php';
    class AnuncioAlbergue{
        private $conn;

        public $ANUN_AL_ID;
        public $ANUN_AL_DESCRIPCION;
        public $ANUN_AL_DIRECCION;
        public $ANUN_AL_TELEFONO;
        public $MASCOTA_ID;
        public $USU_ID;


        public function __construct($db){
            $this->conn = $db;
        }
        //FUNCION PARA LISTAR ANUNCIOS ALBERGUES
        function listarAnunciosAlbergues(&$mensaje, &$exito, &$code_error){
            
            $query="
            select AN.ANUN_AL_DESCRIPCION, AN.ANUN_AL_DIRECCION, AN.ANUN_AL_TELEFONO, 
            MASC.MASCOTA_NOMBRE, MASC.MASCOTA_COLOR, MASC.MASCOTA_SEXO, MASC.MASCOTA_FOTO,
            USU.USU_NOMBRES 
            from ANUNCIOS_ALBERGUE AN 
            INNER JOIN USUARIOS USU ON (AN.USU_ID = USU.USU_ID)
            INNER JOIN MASCOTA MASC ON (AN.MASCOTA_ID = MASC.MASCOTA_ID);  
            ";

            $datos =[];

            try {
                
                $stmt = $this->conn->prepare($query);
                if(!$stmt->execute()){

                    $code_error = "error_ejecucionQuery";
                    $mensaje = "Hubo un error al listar los anuncios de albergues.";
                    $exito = false; 

                }else{

                    $result = get_result($stmt); 
                
                    if (count($result) > 0) {                
                        while ($dato = array_shift($result)) {    
                            $datos[] = $dato;
                        }
                    }

                    $mensaje = "Solicitud ejecutada con exito";
                    $exito = true;
                    
                }

                return $datos;

            } catch (Throwable $th) {

                $code_error = "error_deBD";
                $mensaje = "Ha ocurrido un error con la BD. No se pudo ejecutar la consulta.";
                $exito = false;
                return $datos;

            }
        }
        //FUNCION PARA CREAR ANUNCIO ALBERGUE
        function crearAnuncioAlbergue(&$mensaje,&$code_error){
            $query = "
            INSERT INTO ANUNCIOS_ALBERGUE(ANUN_AL_DESCRIPCION,ANUN_AL_DIRECCION,ANUN_AL_TELEFONO,MASCOTA_ID,USU_ID)
            VALUES(?,?,?,?,?);
            "; 

            try{
                $stmt = $this->conn->prepare($query);
                $stmt->bind_param("sssss",$this->ANUN_AL_DESCRIPCION,$this->ANUN_AL_DIRECCION,$this->ANUN_AL_TELEFONO,$this->MASCOTA_ID,$this->USU_ID);
                if(!$stmt->execute()){
                    $code_error = "error_ejecucionQuery";
                    $mensaje = "Hubo un error al crear los anuncios de albergues.";
                    return false; 
                }else{
                    $code_error = null;
                    $mensaje = "Se creo el anuncio de albergue correctamente.";
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