<?php
    include_once("model.php");
    class ModDocente extends ProgramModel{
        public function insertar_docente(){
            try{
                $respuesta="";
                $this->informacion($_POST);
                $sql="SELECT id FROM docente WHERE Identidad='$this->Identidad'";
                if($this->contar($sql)==0){
                    $this->archivos($_FILES);
                    strpos($this->Foto['type'], '/') !== false ? $ext=explode("/", $this->Foto['type']):$ext=explode("\\", $this->Foto['type']);
                    strpos($this->Foto['tmp_name'], '/') !== false ? $nombre=explode("/", $this->Foto['tmp_name']) : $nombre=explode("\\", $this->Foto['tmp_name']);
                    $nom=array_pop($nombre);
                    $nom=str_replace(".tmp","",$nom);
                    //_____________________________________________________________
                    $this->crear_carpeta(__DIR__."/../view/img/docentes");
                    $fotografia="view/img/docentes/".$nom.".".$ext[1];
                    if(move_uploaded_file($this->Foto['tmp_name'] , __DIR__."/../".$fotografia)){
                        $respuesta.="\nFOTO GUARDADA SATISFACTORIAMENTE";
                        $sql="INSERT INTO docente (Identidad, Nombre1, Nombre2, Apellido1, Apellido2, Escalafon, Imprema, Telefono, Correo, Foto, Status, sexo, fecha_nacimeito, titulo,edo_mail)VALUES('$this->Identidad', '$this->Pnombre', '$this->Snombre', '$this->Papellido', '$this->Sapellido', '$this->Escalafon', '$this->Imprema', '$this->Telefono', '$this->Correo', '$fotografia', '$this->Status', '$this->Sexo', '$this->FechaNacimiento', '$this->Titulo','pendiente')";
                        $ing=$this->ejecutar($sql);
                        if($ing[0] > 0){
                            $iddocente=$ing[0];
                            $respuesta.="\n REGISTRO $ing[0] GUARDADO SATISFACTORIAMENTE";
                            $sql="SELECT id_usuario FROM usuarios";
                            if($this->contar($sql)==0){
                                $nom=$this->Pnombre.' '.$this->Snombre.' '.$this->Papellido.' '.$this->Sapellido;
                                $sql ="INSERT INTO usuarios(nombre, usuario, clave,id_docente)values('$nom','$this->Correo','$this->Correo',$iddocente)";
                                $ing=$this->ejecutar($sql);
                                if($ing[0] > 0){
                                    $idusuario=$ing[0];
                                    $respuesta.="\n ADMINISTRADOR CREADO SATISFACTORIAMENTE";
                                    $fecha_actual = date("d-m-Y");
                                    $fecha = DateTime::createFromFormat("d-m-Y", $fecha_actual);
                                    $fecha->add(new DateInterval('P1Y'));
                                    $fechavencimiento = $fecha->format("d-m-Y");
                                    $sql="INSERT INTO designaciones(id_docente,puesto,condicion,estatus,fasignacion,fvencimiento) VALUES($iddocente, 'SysAdmin', 'Permanente', 'Activo', '$fecha_actual', '$fechavencimiento')";
                                    $ing=$this->ejecutar($sql);
                                    if($ing[0] > 0){
                                        $respuesta.="\n FUNCION DE ADMINISTRADOR CREADA SATISFACTORIAMENTE";
                                    }else{$respuesta = serialize($ing[1]);}
                                }else{$respuesta = serialize($ing[1]);}
                            }
                        }else{$respuesta = serialize($ing[1]);}
                    }
                }else{$respuesta="YA EXISTE UN DOCENTE CON ESTE DOCUMENTO DE IDENTIDAD EN LA BASE DE DATOS";}
                print($respuesta);
            }catch(PDOException $e){echo 'error: '.$e->getMessage();}
            $this->cone = null;
            unset($this->cone);
        }
        public function lista_docentes(){
            try {
                session_start();
                $sql="SELECT docente.*,'".$_SESSION["rango"]."' as rango_propio ,usuarios.* FROM docente LEFT JOIN usuarios ON usuarios.id_docente=docente.id";
                print_r(json_encode($this->capturar($sql)));
            }catch(PDOException $e){echo 'error: '.$e->getMessage();}
        }
        public function docentes_filtrados(){
            try {
                session_start();
                $sql="SELECT docente.*, usuarios.*, '".$_SESSION["rango"]."' as rango_propio FROM ".CONFIG['DB_DATABASE'].".".CONFIG['TB_DOCENTE']." LEFT JOIN usuarios ON usuarios.id_docente=docente.id WHERE 
                docente.identidad like '%".$_POST['identidad']."%' and (
                docente.Nombre1 like '%".$_POST['nombre']."%' or 
                docente.Nombre2 like '%".$_POST['nombre']."%' or 
                docente.Apellido1 like '%".$_POST['nombre']."%' or 
                docente.Apellido2 like '%".$_POST['nombre']."%') and 
                docente.Escalafon like '%".$_POST['escalafon']."%' and 
                docente.Imprema like '%".$_POST['imprema']."%'";
                if(trim($_POST['estatus'])!=""){$sql=$sql." and docente.Status ='".$_POST['estatus']."'";}
                #print($sql);
                print_r(json_encode($this->capturar($sql)));
            }catch(PDOException $e){echo 'error: '.$e->getMessage();}
        }
        public function verificar_identidad_docente(){
            $respuesta="";
            $this->informacion($_POST);
            $sql="SELECT id FROM docente WHERE Identidad='$this->identidad'";
            $numero=$this->contar($sql);
            $respuesta=$this->capturar($sql);
            print_r(json_encode([$numero,$respuesta]));
        }
        public function delete_docente(){
            try {
                $respuesta="";
                $sql="SELECT Foto FROM docente WHERE id=".$_POST['id'];
                $foto=$this->capturar($sql);
                $sql="DELETE FROM docente WHERE id=".$_POST['id'];
                $resultado=$this->ejecutar($sql);
                if ($resultado[0]==1) {
                    unlink(__DIR__."/../".$foto[0]['Foto']);
                    $respuesta."\n FOTO ELIMINADA SATISFACTORIAMENTE";
                    $respuesta.="\n EJECUCION REALIZADA SATISFACTORIAMENTE";
                }else{
                    $respuesta=$resultado[1][2];
                }
            }catch(PDOException $e){echo 'ERROR AL EJECUTAR: '.$e->getMessage();}
            print($respuesta);
        }
        public function edit_docente(){
            try {
                $sql="SELECT * FROM docente WHERE id = ".$_POST['id'];
                $res=$this->capturar($sql);
                session_start();
                $_SESSION["fotografia"]=$res[0]['Foto'];
                print_r(json_encode($res));
            }catch(PDOException $e){echo 'error: '.$e->getMessage();}
        }
        public function update_docente(){
            $respuesta="";
            $this->informacion($_POST);
            $sql="UPDATE docente SET 
            sexo='$this->Sexo',
            titulo='$this->Titulo',
            fecha_nacimeito='$this->FechaNacimiento',
            Identidad='$this->Identidad',
            Nombre1='$this->Pnombre',
            Nombre2='$this->Snombre',
            Apellido1='$this->Papellido',
            Apellido2='$this->Sapellido',
            Escalafon='$this->Escalafon',
            Imprema='$this->Imprema',
            Telefono='$this->Telefono',
            Correo='$this->Correo',
            Status='$this->Status'";
            if(!empty($_FILES["Foto"])){
                $this->archivos($_FILES);
                strpos($this->Foto['type'], '/') !== false ? $ext=explode("/", $this->Foto['type']):$ext=explode("\\", $this->Foto['type']);
                strpos($this->Foto['tmp_name'], '/') !== false ? $nombre=explode("/", $this->Foto['tmp_name']) : $nombre=explode("\\", $this->Foto['tmp_name']);
                $nom=array_pop($nombre);
                $nom=str_replace(".tmp","",$nom);
                $fotografia="view/img/docentes/".$nom.".".$ext[1];
                $sql.=",Foto='$fotografia'";
                try{
                    session_start();
                    move_uploaded_file($this->Foto['tmp_name'] , __DIR__."/../".$fotografia);
                    unlink(__DIR__.'/../'.$_SESSION["fotografia"]);
                    $respuesta.="\n FOTO MODIFICADA SATISFACTORIAMENTE";
                }catch(PDOException $e){echo 'ERROR AL MOVER LOGO: '.$e->getMessage();}
            }
            $sql=$sql." WHERE id = ".$this->id;
            try {
                $resultado = $this->ejecutar($sql);
                if($resultado[0]==0 AND $resultado[1][0]==0){$respuesta="NO ENVIO NINGUN DATO PARA MODIFICAR";}
                if($resultado[0]==0 AND $resultado[1][0]>0){$respuesta.=$resultado[1][2];}
                if($resultado[0]==1){$respuesta.="\n ACTUALIZACION REALIZADA SATISFACTORIAMENTE";}
            } catch(PDOException $e){echo 'ERROR AL INSERTAR: '.$e->getMessage();}
            print($respuesta);
        }
        public function filtrar_direcciones(){
            $sql="SELECT * FROM direcciones";
            print_r(json_encode($this->capturar($sql)));
        }
        public function lista_direcciones_asignadas(){
            $sql = "SELECT desi.id_direccion, desi.fasignacion, desi.fvencimiento, dire.departamento, dire.municipio 
            FROM designaciones AS desi 
            LEFT JOIN direcciones AS dire ON dire.id = desi.id_direccion 
            WHERE desi.id_direccion IS NOT NULL AND desi.id_docente = " . $_POST['id'];
    
            #print($sql);die();
            $resp=$this->capturar($sql);
            print_r(json_encode($resp));
        }
        public function datos_personales(){
            session_start();
            $this->informacion($_POST);
            $sql="UPDATE docente SET 
            Identidad='$this->Identidad',
            Nombre1='$this->Nombre1',
            Nombre2='$this->Nombre2',
            Apellido1='$this->Apellido1',
            Apellido2='$this->Apellido2',
            Escalafon='$this->Escalafon',
            Imprema='$this->Imprema',
            Telefono='$this->Telefono',
            Correo='$this->Correo',
            sexo='$this->sexo',
            fecha_nacimeito='$this->fecha_nacimeito',
            titulo='$this->Titulo'";
            $respuesta="";
            if(!empty($_FILES)){
                $this->vfoto          = $_FILES['Foto'];
                $nombre=explode("\\", $this->vfoto['tmp_name']);
                $ext=explode("/", $this->vfoto['type']);
                $nom=array_pop($nombre);
                $nom=explode(".",$nom);
                $fotografia="view/img/docentes/".$nom[0].".".$ext[1];
                $sql=$sql.", Foto='$fotografia'";
                try{
                    move_uploaded_file($this->vfoto['tmp_name'] , __DIR__."/../".$fotografia);
                    unlink(__DIR__.'/../'.$_SESSION["fotografia"]);
                    $respuesta = $respuesta."\n FOTO MODIFICADA SATISFACTORIAMENTE";
                    $sql=$sql." , Foto = '$fotografia'";
                }catch(PDOException $e){echo 'ERROR AL MOVER LOGO: '.$e->getMessage();}
            }
            $sql=$sql." WHERE id=".$_SESSION['id_docente'];
            $resp1=$this->ejecutar($sql);
            #print_r($resp1);die();
            $nom=$this->Nombre1." ".$this->Nombre2." ".$this->Apellido1." ".$this->Apellido2;
            $sql="UPDATE usuarios SET nombre='$nom'"; 
            if(!empty($this->Usuario)){
                $sgl = "SELECT usuario FROM usuarios WHERE usuario='$this->Usuario' and id_usuario!=".$_SESSION["id_usuario"];
                if($this->contar($sgl)==0){$sql=$sql.",usuario='$this->Usuario'";}
                else{print("no se puede utilizar este usuario, por favor modifiquelo");die();}
            }
            if(!empty($this->Clave)){
                $sgl = "SELECT clave FROM usuarios WHERE clave='$this->Clave' and id_usuario!=".$_SESSION["id_usuario"];
                if($this->contar($sgl)==0){$sql=$sql.",clave='$this->Clave'";}
                else{print("no se puede utilizar esta Contraseña, por favor modifiquela");die();}
            }
            $sql=$sql." Where id_usuario = ".$_SESSION['id_usuario'];
            $resp2=$this->ejecutar($sql);
            if($resp1[0]==1 || $resp2[0]==1){$respuesta.= "\nDATOS ACTUALIZADOS SATISFACTORIAMENTE";}
            else{$respuesta="No ha Enviado Ningún Cambio En sus Datos";}
            print($respuesta);
        }
        public function Cambiar_Clave(){
            $sql="SELECT id_docente FROM usuarios WHERE id_docente=".$_POST['docente'];
            if($this->contar($sql)>0){
                $sql="SELECT id_usuario FROM usuarios WHERE clave='".$_POST['clave']."'";
                if($this->contar($sql)>0){print("estos datos ya estan siendo usados por otro usuario");die();}
                $sql="SELECT id_usuario FROM usuarios WHERE usuario='".$_POST['usuario']."'";
                if($this->contar($sql)>0){print("estos datos ya estan siendo usados por otro usuario");die();}
                $sql="UPDATE usuarios SET ";
                if(!empty($_POST['usuario'])){
                    $sql.="usuario = '".$_POST['usuario']."',";
                }
                if(!empty($_POST['usuario'])){
                    $sql.="clave = '".$_POST['clave']."'";
                }
                $sql.=" WHERE id_docente=".$_POST['docente'];
                $resp=$this->ejecutar($sql);
                if($resp[0]>0){
                    print"ACTUALIZACION REALIZADA SATISFACTORIAMENTE";
                }else{
                    print_r($resp[1][2]);
                }
            }else{
                print("este docente no tiene usuario en el sistema");
            }
        }
        public function verificar_existencia_email(){
            $sql="SELECT id FROM docente WHERE Correo='".$_POST['email']."'";
            print($this->contar($sql));
        }
    }