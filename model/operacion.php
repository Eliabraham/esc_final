<?php
    include_once("model.php");
    class Operacion extends ProgramModel{
        public function insertar_operacion(){
            $this->informacion($_POST);
            #print_r($_POST);die();
            $resp="";
            $sql="INSERT INTO operaciones(nombre_proceso, descripcion_proceso, link_plantilla) VALUE('$this->nombre_proceso', '$this->descripcion_proceso', '$this->link_ficha')";
            $id=$this->ejecutar($sql);
            if($id[0]>0){
                $resp.="operacion insertada satisfactoriamente";
                $detalles=json_decode($this->detalles);
                foreach ($detalles as $data) {
                    $sql = "INSERT INTO detalles_operaciones (id_operacion, campo, tipo, valores, descripcion) VALUES ($id[0], '$data[0]', '$data[1]', '$data[2]', '$data[3]')";
                    $det=$this->ejecutar($sql);
                    if($det[0]>0){$resp.="\ndetalle insertado satisfactoriamente";}else{$resp.="\n".$det[1][2];}
                } 
            }else{$resp.="\n".$id[1][2];}
            print($resp);
        }
        public function lista_tarjetas(){
            $sql="SELECT * FROM operaciones";
            $obj=$this->capturar($sql);
            print_r(json_encode($obj));
        }
        public function eliminar_opcion(){
            $sql="DELETE FROM operaciones WHERE id=".$_POST['id'];
            $obj=$this->ejecutar($sql);
            if($obj[0]==1){
                print("ACCION REALIZADA SATISFACTORIAMENTE");
            }else{
                print(json_encode($obj[1][2]));
            }
        }
        public function filtrar_opcion(){
            $nombre=$_POST['nombre'];
            $sql="SELECT * FROM operaciones WHERE nombre_proceso LIKE '%$nombre%'";
            $obj=$this->capturar($sql);
            print_r(json_encode($obj));
        }
        public function editar_operacion(){
            $id=$_POST['id'];
            $sql="SELECT * FROM operaciones WHERE id = $id";
            $obj=$this->capturar($sql);
            $sql="SELECT * FROM detalles_operaciones WHERE id_operacion=$id";
            $detalles=$this->capturar($sql);
            print_r(json_encode([$obj,$detalles]));
        }
        public function eliminar_campo(){
            $sql="DELETE FROM detalles_operaciones WHERE id=".$_POST['id'];
            $obj=$this->ejecutar($sql);
            if($obj[0]==1){
                print("ACCION REALIZADA SATISFACTORIAMENTE");
            }else{
                print(json_encode($obj[1][2]));
            }
        }
        public function modificar_operacion(){
            $this->informacion($_POST);
            //print_r($_POST);
            $resp="";
            $sql="UPDATE operaciones SET nombre_proceso='$this->nombre_proceso',descripcion_proceso='$this->descripcion_proceso', link_plantilla='$this->link_ficha' WHERE id=$this->id";
            $acc=$this->ejecutar($sql);
            $resp.="ACTUALIZACION REALIZADA SATISFACTORIAMENTE";
            $sql="DELETE FROM detalles_operaciones WHERE id_operacion=$this->id";
            $this->ejecutar($sql);
            $detalles=json_decode($this->detalles);
            foreach ($detalles as $data) {
                $sql = "INSERT INTO detalles_operaciones (id_operacion, campo, tipo, valores, descripcion) VALUES ($this->id, '$data[0]', '$data[1]', '$data[2]', '$data[3]')";
                $det=$this->ejecutar($sql);
                if($det[0]>0){$resp.="\ndetalle insertado satisfactoriamente";}else{$resp.="\n".$det[1][2];}
            }
            print($resp);
        }
        public function mostrar_solicitudes_sa(){
            $sql="SELECT sol.id as 'numero solicitud' , sol.fecha_solicitud, sol.causa AS causa_solicitud, sol.status AS estado_solicitud, sol.resolucion, ope.nombre_proceso, desi.puesto, desi.condicion, desi.estatus, concat(doc.Nombre1,' ',doc.Nombre2,' ',doc.Apellido1,' ',doc.Apellido2) AS nombre_completo, doc.Telefono, doc.Correo, doc.Titulo, cen.Nombre as nombre_centro, cen.Municipio as municipio_centro, cen.Direccion as direccion_centro, cen.Telefono as telefono_centro, ope.nombre_proceso
            FROM solicitudes AS sol
            LEFT JOIN centro AS cen ON cen.id_centro = sol.id_centro
            LEFT JOIN operaciones AS ope ON ope.id=sol.id_tipo_solicitud 
            LEFT JOIN designaciones AS desi ON desi.id=sol.id_solicitante 
            LEFT JOIN docente AS doc ON doc.id = desi.id_docente WHERE sol.id > 0";
            if(isset($_POST["centro"]) && $_POST["centro"]!="" ){$sql.=" AND sol.id_centro = ".$_POST['centro'];}
            if(isset($_POST["tipo"]) && $_POST["tipo"]!="" ){$sql.=" AND sol.id_tipo_solicitud = ".$_POST['tipo'];}
            if(isset($_POST["estado"]) && $_POST["estado"]!="" ){$sql.=" AND  sol.status='".$_POST['estado']."'";}
            if(isset($_POST["numero"]) && $_POST["numero"]!="" ){$sql.=" AND  sol.id =".$_POST['numero'];}
            
            #print($sql);
            $obj=$this->capturar($sql);
            print_r(json_encode($obj));
        }
        PUBLIC FUNCTION iniciar_filtros(){
            $sql="SELECT Nombre, Codigo_centro, Municipio, Tipo_centro, id_centro FROM centro";
            $centro=$this->capturar($sql);
            $sql="SELECT id, nombre_proceso FROM operaciones";
            $operaciones=$this->capturar($sql);
            $sql="SELECT DISTINCT status FROM solicitudes";
            $estado=$this->capturar($sql);
            print_r(json_encode([$centro,$operaciones,$estado]));
        }
        public function finalizar_tramite(){
            $id=$_POST['solicitud'];
            $resolucion=$_POST['resolucion'];
            $estado=$_POST['estado'];
            $sql="UPDATE solicitudes SET resolucion='$resolucion', status = '$estado' WHERE id=$id";
            $resp=$this->ejecutar($sql);
            print("ACCION REALIZADA SATISFACTORIEMENTE");
        }
    }
    class Solicitud extends ProgramModel{
        public function nombre_solicitudes(){
            $sql="SELECT id,nombre_proceso FROM operaciones";
            $obj=$this->capturar($sql);
            print_r(json_encode($obj));
        }
        public function seleccionar_operacion(){
            $sql="SELECT * FROM operaciones WHERE id=".$_POST['id'];
            $obj=$this->capturar($sql);
            $sql="SELECT * FROM detalles_operaciones WHERE id_operacion=".$_POST['id'];
            $det=$this->capturar($sql);
            print_r(json_encode([$obj,$det]));
        }
        public function crear_solicitud(){
            session_start();
            $solicitante =$_SESSION["id_asignacion"];
            if ($_SESSION['rango']=='Director'){
                $centro=$_SESSION["id_centro"];
            }
            if($_SESSION['rango']=='Director(a) Municipal'){
                $centro=$_POST['sol_centro'];
            }
            $fecha = date("d-m-Y");
            $proceso     =$_POST['nombre_proceso'];
            $causa       =$_POST['causa'];
            $sql="INSERT INTO solicitudes(id_centro, id_solicitante, fecha_solicitud, id_tipo_solicitud, causa, status) VALUES($centro, $solicitante, '$fecha', $proceso, '$causa','recepcion')";
            $obj=$this->ejecutar($sql);
            if($obj[0]>0){$resp=$obj[0];}
            else{$resp="HA OCURRIDO EL SIGUIENTE PROBLEMA: ".$obj[1][2];}
            print_r($resp);
        }
        public function ingresar_cuerpo_solicitud(){
            $solicitud=$_POST['id_solicitud'];
            $sql="SELECT FROM WHERE";
            $resp="";
            foreach ($_POST as $clave=>$valor){
                //print($clave."=>".$valor."\n");
                if($clave!="id_solicitud" && $clave!="gv_action"){
                    $sql = "INSERT INTO detalle_solicitud (id_solicitud, campo, valor) VALUES($solicitud,'$clave','$valor')";
                    //print($sql."\n");
                    $det = $this->ejecutar($sql);
                    if($det[0]>1){$resp.="\ncampo $clave guardado satisfactoriamente";}
                    else{$resp.=$det[1][2];}
                }
            }
            foreach ($_FILES as $clave => $valor){
                //print_r($clave);
                //print_r($valor);
                $ext=explode("/", $valor['type']);
                $nombre=explode("\\", $valor['tmp_name']);
                $nom=array_pop($nombre);
                $nom=explode(".",$nom);
                $ruta="view/tramites/$solicitud/";
                $soporte=$ruta.$nom[0].".".$ext[1];
                try{
                    if (!file_exists(__DIR__."/../".$ruta)) {
                        if (mkdir(__DIR__."/../".$ruta, 0777, true)){$resp.="\nEl directorio ha sido creado exitosamente.";} 
                        else {echo "Error al crear el directorio.";}
                    }
                    move_uploaded_file($valor['tmp_name'] , __DIR__."/../".$soporte);
                }catch(PDOException $e){echo 'ERROR AL MOVER LOGO: '.$e->getMessage();}
                $sql="INSERT INTO detalle_solicitud (id_solicitud, campo, valor) VALUES($solicitud, '$clave', '$soporte')";
                #print($sql."\n");
                $det = $this->ejecutar($sql);
                if($det[0]>1){$resp.="\ncampo $clave guardado satisfactoriamente";}
                else{$resp.=$det[1][2];}
            }
            print($resp);
        }
        public function listar_solicitudes(){
            session_start();
            if($_SESSION["rango"]=="SysAdmin"){
                $sql="SELECT sol.*, ope.nombre_proceso, doce.identidad, CONCAT(doce.Nombre1,' ',doce.Nombre2,' ',doce.Apellido1,' ',doce.Apellido2) AS nombre_completo, doce.Telefono, doce.Correo, doce.titulo 
                FROM solicitudes AS sol 
                LEFT JOIN operaciones AS ope ON ope.id=sol.id_tipo_solicitud 
                LEFT JOIN designaciones AS desi ON desi.id=sol.id_solicitante 
                LEFT JOIN docente AS doce ON doce.id = desi.id_docente";
            }
            if($_SESSION["rango"]=="Director"){
                $sql="SELECT sol.*, ope.nombre_proceso, doce.identidad, CONCAT(doce.Nombre1,' ',doce.Nombre2,' ',doce.Apellido1,' ',doce.Apellido2) AS nombre_completo, doce.Telefono, doce.Correo, doce.titulo 
                FROM solicitudes AS sol 
                LEFT JOIN operaciones AS ope ON ope.id=sol.id_tipo_solicitud 
                LEFT JOIN designaciones AS desi ON desi.id=sol.id_solicitante 
                LEFT JOIN docente AS doce ON doce.id = desi.id_docente 
                WHERE sol.id_centro=".$_SESSION['id_centro'];
            }
            if($_SESSION["rango"]=="Director(a) Municipal"){
                $sql="SELECT sol.*, ope.nombre_proceso, doce.identidad, CONCAT(doce.Nombre1,' ',doce.Nombre2,' ',doce.Apellido1,' ',doce.Apellido2) AS nombre_completo, doce.Telefono, doce.Correo, doce.titulo 
                FROM solicitudes AS sol 
                INNER JOIN centro AS cen ON cen.id_centro = sol.id_centro
                LEFT JOIN operaciones AS ope ON ope.id=sol.id_tipo_solicitud 
                LEFT JOIN designaciones AS desi ON desi.id=sol.id_solicitante 
                LEFT JOIN docente AS doce ON doce.id = desi.id_docente 
                WHERE cen.Municipio=".$_SESSION['id_direccion'];
                //print($sql);
            }
            $obj=$this->capturar($sql);
            print_r(json_encode($obj));
        }
        public function filtrar_solicitudes(){
            session_start();
            if($_SESSION["rango"]=="SysAdmin"){
                $sql="SELECT sol.*, ope.nombre_proceso, doce.identidad, CONCAT(doce.Nombre1,' ',doce.Nombre2,' ',doce.Apellido1,' ',doce.Apellido2) AS nombre_completo, doce.Telefono, doce.Correo, doce.titulo 
                FROM solicitudes AS sol 
                LEFT JOIN operaciones AS ope ON ope.id=sol.id_tipo_solicitud 
                LEFT JOIN designaciones AS desi ON desi.id=sol.id_solicitante 
                LEFT JOIN docente AS doce ON doce.id = desi.id_docente
                WHERE sol.id>0";
            }            
            if($_SESSION["rango"]=="Director"){
                $sql="SELECT sol.*, ope.nombre_proceso, doce.identidad, CONCAT(doce.Nombre1,' ',doce.Nombre2,' ',doce.Apellido1,' ',doce.Apellido2) AS nombre_completo, doce.Telefono, doce.Correo, doce.titulo 
                FROM solicitudes AS sol 
                LEFT JOIN operaciones AS ope ON ope.id=sol.id_tipo_solicitud 
                LEFT JOIN designaciones AS desi ON desi.id=sol.id_solicitante 
                LEFT JOIN docente AS doce ON doce.id = desi.id_docente 
                WHERE sol.id_centro=".$_SESSION['id_centro'];
            }
            if($_SESSION["rango"]=="Director(a) Municipal"){
                $sql="SELECT sol.*, ope.nombre_proceso, doce.identidad, CONCAT(doce.Nombre1,' ',doce.Nombre2,' ',doce.Apellido1,' ',doce.Apellido2) AS nombre_completo, doce.Telefono, doce.Correo, doce.titulo 
                FROM solicitudes AS sol 
                INNER JOIN centro AS cen ON cen.id_centro = sol.id_centro
                LEFT JOIN operaciones AS ope ON ope.id=sol.id_tipo_solicitud 
                LEFT JOIN designaciones AS desi ON desi.id=sol.id_solicitante 
                LEFT JOIN docente AS doce ON doce.id = desi.id_docente 
                WHERE cen.Municipio=".$_SESSION['id_direccion'];
            }
            if($_POST['centro']){$sql.=" AND sol.id_centro=".$_POST["centro"];}
            if($_POST['Numero']){$sql.=" AND sol.id=".$_POST["Numero"];}
            if($_POST['tipo']){$sql.=" AND sol.id_tipo_solicitud=".$_POST['tipo'];}
            if($_POST['estado']){$sql.=" AND sol.status='".$_POST['estado']."'";}
            $sql.=" ORDER BY sol.id ASC";
            #print($sql);die();
            $obj=$this->capturar($sql);
            print_r(json_encode($obj));
        }
        public function eliminar_solicitud(){
            $sql="DELETE FROM solicitudes WHERE id=".$_POST['id'];
            $obj=$this->ejecutar($sql);
            if($obj[0]==1){
                print("ACCION REALIZADA SATISFACTORIAMENTE");
            }else{
                print(json_encode($obj[1][2]));
            }
        }
        public function editar_solicitud(){
            $sql="SELECT sol.*, ope.* FROM solicitudes AS sol LEFT JOIN operaciones AS ope ON ope.id=sol.id_tipo_solicitud WHERE sol.id=".$_POST['id'];
            $obj    = $this->capturar($sql);
            $sql    = "SELECT * FROM detalles_operaciones WHERE id_operacion=".$obj[0]['id_tipo_solicitud'];
            $opedet = $this->capturar($sql);
            $sql    ="SELECT * FROM detalle_solicitud WHERE id_solicitud=".$_POST['id'];
            $soldet = $this->capturar($sql);
            print_r(json_encode([$obj,$opedet,$soldet,$_POST['id']]));
        }
        public function modificar_solicitud(){
            session_start();
            $solicitante =$_SESSION["id_asignacion"];
            $id=$_POST['id'];
            $causa=$_POST['causa'];
            $sql="UPDATE solicitudes SET causa='$causa', id_solicitante = $solicitante WHERE id=$id";
            $resp=$this->ejecutar($sql);
        }
        public function modificar_cuerpo_solicitud(){
            $id=$_POST['solicitud'];
            foreach ($_POST as $clave => $valor){
                if($valor!="undefined" and $clave!='gv_action' and $clave!="solicitud"){
                    $sql="UPDATE detalle_solicitud SET valor='$valor' WHERE id_solicitud=$id AND campo='$clave'";
                    $resp=$this->ejecutar($sql);
                    //print_r($resp);
                }
            }
            foreach($_FILES as $clave =>$valor){
                #$clave->nombre de campo
                #$valor->caraacteristicas
                $ext=explode("/", $valor['type']);
                $nombre=explode("\\", $valor['tmp_name']);
                $nom=array_pop($nombre);
                $nom=explode(".",$nom);
                $ruta="view/tramites/$id/";
                $soporte=$ruta.$nom[0].".".$ext[1];
                try{
                    $sql="SELECT valor FROM detalle_solicitud WHERE id_solicitud=$id AND campo='$clave'";
                    $obj=$this->capturar($sql);
                    unlink(__DIR__."/../".$obj[0]['valor']);
                    move_uploaded_file($valor['tmp_name'] , __DIR__."/../".$soporte);
                }catch(PDOException $e){echo 'ERROR AL MOVER LOGO: '.$e->getMessage();}
                $sql="UPDATE detalle_solicitud SET valor='$soporte' WHERE id_solicitud=$id AND campo='$clave'";
                $resp=$this->ejecutar($sql);    
            }
        }
        public function insertar_observacion(){
            session_start();
            $fecha = date("d-m-Y");
            $autor = $_SESSION["id_asignacion"];
            $solicitud = $_POST["solicitud"];
            $observacion = $_POST['observacion'];
            $sql="INSERT INTO observaciones_solicitudes(id_solicitud, id_autor, fecha, Observacion)VALUES($solicitud, $autor, '$fecha', '$observacion')";
            $id=$this->ejecutar($sql);
            if($id[0]>0){$resp="ACCION REALIZADA SATISFACTORIAMENTE";}
            else{$resp=$id[1][2];}
        }
        public function buscar_observaciones(){
            $lista = explode(",", $_POST['lista']);
            $resp=[];
            foreach ($lista as $elemento){
                $sql = "SELECT obs.*, concat(doc.Nombre1,' ',doc.Nombre2,' ',doc.Apellido1,' ',doc.Apellido2) AS nombre_completo, doc.telefono, doc.Correo
                FROM observaciones_solicitudes AS obs
                LEFT JOIN designaciones AS desi ON desi.id = obs.id_autor
                LEFT JOIN docente AS doc ON doc.id = desi.id_docente
                WHERE id_solicitud = $elemento ORDER BY obs.id DESC";
                $obj = $this->capturar($sql);
                $resp[]=$obj;
            }
            print_r(json_encode($resp));
        }
        public function lista_estados(){
            $sql="SELECT DISTINCT status FROM solicitudes";
            $estado=$this->capturar($sql);
            print_r(json_encode($estado));
        }
    }

