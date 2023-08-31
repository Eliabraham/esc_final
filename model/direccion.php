<?php
    include_once("model.php");
    class ModDireccion extends ProgramModel{
        public function insertar_direccion(){
            try {
                $params=$this->informacion($_POST);
                $sql="INSERT INTO ".CONFIG["DB_DATABASE"].".".CONFIG["TB_DIRECCION"]."( departamento, municipio, ubicacion, email, telefono)VALUES('$this->Departamento','$this->Municipio', '$this->DireccionGeografica', '$this->EmailDireccion', '$this->TelefonoDireccion')";    
                $this->vid=$this->ejecutar($sql);
                $this->respuesta="INSERCION REALIZADA SATISFACTORIAMENTE";
            } catch(PDOException $e){echo 'ERROR AL INSERTAR: '.$e->getMessage();}
        }
        public function listar_direccion(){
            try{
                $sql="SELECT * FROM direcciones"; 
                $direcciones = $this->capturar($sql);
                print_r(json_encode($direcciones));
            }catch(PDOException $e){echo 'ERROR AL INSERTAR: '.$e->getMessage();}
        }
        public function del_direccion(){
            try {
                $params=$this->informacion($_POST);
                $sql="DELETE FROM ".CONFIG["DB_DATABASE"].".".CONFIG["TB_DIRECCION"]." WHERE id=".$this->direccion;
                $this->vid=$this->ejecutar($sql);
                $this->respuesta="ACCION REALIZADA SATISFACTORIAMENTE";
                print($this->respuesta);
            } catch(PDOException $e){echo 'ERROR AL INSERTAR: '.$e->getMessage();}
        }
        public function edit_direccion(){
            try {
                $params=$this->informacion($_POST);
                $sql="SELECT * FROM ".CONFIG["DB_DATABASE"].".".CONFIG["TB_DIRECCION"]." WHERE id=".$this->direccion;
                $this->respuesta = $this->capturar($sql);
                print_r(json_encode($this->respuesta));
            } catch(PDOException $e){echo 'ERROR AL INSERTAR: '.$e->getMessage();}
        }
        public function update_direccion(){
            try {
                $this->informacion($_POST);
                $sql="UPDATE ".CONFIG["DB_DATABASE"].".".CONFIG["TB_DIRECCION"]." SET  departamento = '$this->Departamento', municipio = '$this->Municipio', ubicacion = '$this->DireccionGeografica', email = '$this->EmailDireccion', telefono= '$this->TelefonoDireccion' WHERE id = ".$this->direccion;
                $this->ejecutar($sql);
                print("ACCION REALIZADA SATISFACTORIAMNETE");
            } catch(PDOException $e){echo 'ERROR AL INSERTAR: '.$e->getMessage();}
        }
        public function filtrar_direccion(){
            try {
                $w="id>0";
                $this->informacion($_POST);
                $sql="SELECT * FROM ".CONFIG["DB_DATABASE"].".".CONFIG["TB_DIRECCION"]." WHERE ";
                if(!empty($this->filDepartamento)){
                   $w=$w." and departamento LIKE '%$this->filDepartamento%'";
                }    
                if(!empty($this->filMunicipio)){
                    $w=$w." and municipio LIKE '%$this->filMunicipio%'";
                }
                $sql=$sql.$w;
                //print($sql);
                print_r(json_encode($this->capturar($sql)));
            }catch(PDOException $e){echo 'ERROR AL INSERTAR: '.$e->getMessage();}
        }
        public function listar_docentes(){
            $sql="SELECT * FROM docente WHERE Status='activo'";
            $resp=$this->capturar($sql);
            print_r(json_encode($resp));
        }
        public function crear_director(){
            $resp="";
            $direccion=$_POST["direccion"];
            $director=$_POST["director"];
            $director=explode("||", $director);
            $cargo="Director(a) Municipal";
            $fasignacion=$_POST["fasignacion"];
            $fvencimiento=$_POST["fvencimiento"];
            $fasignacion = date('d-m-Y', strtotime($fasignacion));
            $fvencimiento = date('d-m-Y', strtotime($fvencimiento));
            $sql="SELECT * FROM designaciones WHERE id_direccion=$direccion AND ((fasignacion<='$fasignacion' AND fvencimiento>'$fasignacion')OR(fasignacion>='$fasignacion' AND fasignacion<='$fvencimiento'))";
            //print($sql);die();
            if($this->contar($sql)==0){
                $sql="INSERT INTO designaciones (id_docente, id_direccion, puesto, condicion, estatus, fasignacion, fvencimiento) VALUE ($director[0], $direccion, '$cargo', '--', 'activo', '$fasignacion', '$fvencimiento')";
                //print($sql);die();
                $asignaciondireccion=$this->ejecutar($sql);
                if($asignaciondireccion[0]>0){
                    $resp.="\nASIGNACION REALIZADA SATISFACTORIAMENTE";
                    $sql="SELECT id_usuario FROM usuarios WHERE id_docente=$director[0]";
                    if($this->contar($sql)==0){
                        $sql = "SELECT id, Correo, CONCAT(Nombre1, ' ', Nombre2, ' ', Apellido1, ' ', Apellido2) AS NombreCompleto FROM docente WHERE id = $director[0]";
                        $persona=$this->capturar($sql);
                        $par=$persona[0]['Correo'];
                        $nombre=$persona[0]['NombreCompleto'];
                        $sql="INSERT INTO usuarios(nombre, usuario, clave, id_docente)VALUES('$nombre','$par','$par', $director[0])";
                        $this->ejecutar($sql);
                    }
                }else{
                    $resp.=$asignaciondireccion[1][2];
                }
            }else{
                $resp.="\nYA EXISTE UNA ASIGNACION PARA ESTE PERIODO";
            }
            print($resp);
        }
        public function listar_directores(){
            $sql="SELECT id FROM direcciones"; 
            $direcciones = $this->capturar($sql);
            $resp=array();
            for($i=0;$i<count($direcciones);$i++){
                $asi=$direcciones[$i]['id'];
                $sql="SELECT desi.fasignacion, desi.id ,desi.fvencimiento, CONCAT(doc.Nombre1,' ',doc.Nombre1,' ',doc.Apellido1,' ',Apellido2) AS nombre_completo
                FROM designaciones AS desi 
                LEFT JOIN docente as doc ON doc.id=desi.id_docente
                WHERE id_direccion = $asi 
                ORDER BY desi.fasignacion ASC";
                //print($sql);
                $asignaciones=$this->capturar($sql);
                //print_r($asignaciones);
                $tc="<tbody>";
                for($ii = 0; $ii < count($asignaciones); $ii++){
                    $tc.="<tr><td>".$asignaciones[$ii]['nombre_completo']."</td>
                    <td>".$asignaciones[$ii]['fasignacion']."</td>
                    <td>".$asignaciones[$ii]['fvencimiento']."</td>
                    <td><button onclick='eliminar_asignacion(this)' id='".$asignaciones[$ii]['id']."' class='btn btn-sm btn-danger'>-</button></td></tr>";
                }
                $tc.="</tbody>";
                $resp[$asi]=$tc;
            }
            print_r(json_encode($resp));
        }
        public function eliminar_asignacion(){
            #print_r($_POST);die();
            $sql="DELETE FROM designaciones WHERE id=".$_POST['asignacion'];
            $resp=$this->ejecutar($sql);
            if($resp[0]>0){
                print("ACCION REALIZADA SATISFACTORIAMENTE");
            }else{
                print($resp[1][2]);
            }
        }
    }