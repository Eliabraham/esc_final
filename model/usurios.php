<?php
    include_once("model.php");
    class ModUsuario extends ProgramModel{
        const cl_id      = 'id_usuario';
        const cl_nombre  = 'nombre';
        const cl_rango   = 'rango';
        const cl_usuario = 'usuario';
        const cl_clave   = 'clave';
        const cl_status  = 'status';
        public function iniciar_sesion_usuarios(){
            $this->informacion($_POST);
            $sql="SELECT id_usuario, id_docente, nombre FROM usuarios WHERE usuario='$this->usuario' and clave='$this->clave'";
            $num_usu=$this->contar($sql);
            if($num_usu > 0){
                $datousuario=$this->capturar($sql);
                $id_docente=$datousuario[0]['id_docente'];
                $fecha_actual = date("d-m-Y");
                $sql = "SELECT desi.*, cent.id_centro, cent.municipio AS cenmuni, cent.Codigo_centro, cent.Nombre, cent.Municipio, cent.Tipo_centro, dire.municipio AS dir_muni 
                FROM designaciones AS desi 
                LEFT JOIN centro as cent ON cent.id_centro = desi.id_centro 
                LEFT JOIN direcciones as dire ON dire.id = desi.id_direccion 
                WHERE desi.id_docente = $id_docente 
                    AND STR_TO_DATE(desi.fasignacion, '%d-%m-%Y') <= STR_TO_DATE('$fecha_actual', '%d-%m-%Y') 
                    AND STR_TO_DATE(desi.fvencimiento, '%d-%m-%Y') >= STR_TO_DATE('$fecha_actual', '%d-%m-%Y') 
                    AND desi.estatus != 'inactivo'
                    AND (desi.puesto = 'SysAdmin' OR desi.puesto = 'Director' OR desi.puesto = 'Director(a) Municipal')";
                #print_r([0, $sql]);die();
                $num_des=$this->contar($sql);
                if($num_des > 0){
                    $designaciones=$this->capturar($sql);
                    $resp=[1,$datousuario,$designaciones];
                }else{
                    $resp = [0,"LAS CREDENCIALES EXISTEN PERO:\n NO TIENE NINGUNA ASIGNACION VALIDA\nESTO PUEDE SUCEDER POR DOS MOTIVOS:\nSUS CREDNCIALES PUEDEN ESTAR VENCIDAS\nALGUN ADMINISTRADOR LAS DESACTIVO"];
                }
            }else{
                $resp = [0,"NO SE ENCUENTRAS ESTAS CREDENCIALES EN LA BASE DE DATOS"];
            }
            return $resp;
        }
        public function cont_usuarios(){
            try{
                $sql = "SELECT id_usuario FROM usuarios";
                return $this->contar($sql);
            }catch(PDOException $e){echo 'error: '.$e->getMessage();}
        }
        public function CtIdUsuario(){
            try{
                $sql = "SELECT ".$this::cl_id ." FROM ". CONFIG['DB_DATABASE'].".".CONFIG['TB_USUARIO']." WHERE ".$this::cl_usuario."='".$_POST["usuario"]."'";
                return $this->capturar($sql);
            }catch(PDOException $e){echo 'error: '.$e->getMessage();}
        }
        public function CtIdClave(){
            try{
                $sql = "SELECT ".$this::cl_id." FROM ". CONFIG['DB_DATABASE'].".".CONFIG['TB_USUARIO']." WHERE ". $this::cl_clave."='".$_POST["clave"]."'";
                return $this->capturar($sql);
            }catch(PDOException $e){echo 'error: '.$e->getMessage();}
        }
        public function CtStatus($idrec){
            try{
                $sql = "SELECT * FROM ". CONFIG['DB_DATABASE'].".".CONFIG['TB_USUARIO']." WHERE ". $this::cl_id ."=". $idrec[$this::cl_id];
                $status=$this->capturar($sql);
                if($status[0]['rango']=="director"){
                    
                }
                return $status;
            }
            catch(PDOException $e){echo 'error: '.$e->getMessage();}
        }
        public function datos_personales($id){
            $sql="SELECT usu.*, doc.* FROM usuarios AS usu LEFT JOIN docente as doc ON doc.id=usu.id_docente WHERE usu.id_usuario=$id";
            $datos=$this->capturar($sql);
            #session_start();
            $_SESSION['fotografia']=$datos[0]['Foto'];
            return $datos;
        }
        public function capturar_escuelas(){
            $fechaActual = date('Y-m-d');
            //session_start();
            $sql="SELECT desig.puesto, desig.fasignacion, desig.fvencimiento, desig.id_centro, cent.Nombre, cent.Tipo_centro, cent.Codigo_centro  FROM designaciones as desig LEFT JOIN centro as cent ON cent.id_centro = desig.id_centro WHERE id_docente = ".$_SESSION['id_docente']." AND  desig.fvencimiento >= '" . $fechaActual . "'";
            //print($sql);
            return $this->capturar($sql);
        }
        public function capturar_direccion_municipal(){
            //session_start();
            $doc=$_SESSION['id_docente'];
            $fechaActual = date('Y-m-d');
            $sql="SELECT asdir.*,dir.* FROM asignacion_directores AS asdir LEFT JOIN direcciones AS dir ON dir.id=asdir.id_direccion WHERE asdir.id_director = $doc AND fecha_asignacion<='$fechaActual' AND fecha_culminacion>='$fechaActual'";
            $re=$this->capturar($sql);
            return $re;
        }
        public function codigo_escuela(){
            $sql="SELECT Codigo_centro FROM centro WHERE id_centro=".$_SESSION['id_centro'];
            $r=$this->capturar($sql);
            return $r[0]['Codigo_centro'];
        }
        public function centros_solicitudes_dm(){
            session_start();
            $sql="SELECT id_centro, Codigo_centro, Nombre, Tipo_centro, N_acuerdo 
            FROM centro
            WHERE Municipio =". $_SESSION['id_direccion'];
            $r=$this->capturar($sql);
            print_r(json_encode($r));
        }
        public function mostrar_identidad(){
            session_start();
            if ($_SESSION['id_usuario']!="G"){
                $sql="SELECT asi.puesto, CONCAT(doc.Nombre1,' ',doc.Nombre2,' ',doc.Apellido1,' ',doc.Apellido2) AS nom_com, cen.Codigo_centro, cen.Nombre, cen.Tipo_centro, concat(dir.departamento,' ',dir.municipio ) AS Municipio
                FROM designaciones AS asi
                LEFT JOIN docente AS doc ON doc.id = asi.id_docente
                LEFT JOIN centro AS cen ON cen.id_centro = asi.id_centro
                LEFT JOIN direcciones AS dir ON dir.id = asi.id_direccion
                WHERE asi.id=".$_SESSION['id_asignacion'];
                $obj=$this->capturar($sql);
                $cad=$obj[0]['nom_com'].' '.$obj[0]['puesto'];
                if($obj[0]['puesto']=="Director"){
                    $cad.=$obj[0]['Tipo_centro'].' '.$obj[0]['Nombre'].' '.$obj[0]['Codigo_centro'];
                }
                if($obj[0]['puesto']=="Director(a) Municipal"){$cad.=' '.$obj[0]['Municipio'];}
            }else{
                $cad=$_SESSION["rango"];
            }
            print($cad);
        }
    }