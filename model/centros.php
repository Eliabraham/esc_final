<?php
    include_once("model.php");
    class ModCentro extends ProgramModel{
        public $vid;
        public $vcodigo;
        public $vnombre;
        public $vdireccion;
        public $vmunicipio;
        public $vtipo;
        public $vtelefono;
        public $vnacuerdo;
        public $vestatus;
        public $pdf_acuerdo;
        public $ruta_acuerdo;
        public $vlogo;
        public $ruta_logo;
        public $ext_logo;
        public $ext_acuerdo;
        public $nombre_archivo;
        public $respuesta;
        const id          = 'id_centro';
        const codigo      = 'Codigo_centro';
	    const nombre      = 'Nombre';
	    const direccion   = 'Direccion';
        const municipio   = 'Municipio';
	    const tipo        = 'Tipo_centro';
	    const telefono    = 'Telefono';
	    const acuerdo     = 'N_acuerdo';
        const estatus     = 'estatus';
        const pdf_acuerdo = 'pdf_acuerdo';
	    const logo        = 'Logo';
        const ext_logo    ='ext_logo';
        const ext_acuerdo ='ext_acuerdo';
        public function insertar_centro(){
            //ASIGNACION DE VALORES_________________________________________________________________________
            $this->informacion($_POST);
            $this->archivos($_FILES);
            strpos($this->Logo['type'], '/') !== false ? $ext=explode("/", $this->Logo['type']):$ext=explode("\\", $this->Logo['type']);
            strpos($this->Logo['tmp_name'], '/') !== false ? $nombre=explode("/", $this->Logo['tmp_name']) : $nombre=explode("\\", $this->Logo['tmp_name']);
            $nom     = array_pop($nombre);
            $nom=str_replace(".tmp","",$nom);
            $logo    = "view/img/escuelas/".$nom.".".$ext[1];
            strpos($this->acuerdo['type'], '/') !== false ? $ext=explode("/", $this->acuerdo['type']):$ext=explode("\\", $this->acuerdo['type']);
            strpos($this->acuerdo['tmp_name'], '/') !== false ? $nombre=explode("/", $this->acuerdo['tmp_name']):$nombre=explode("\\", $this->acuerdo['tmp_name']);
            $nom     = array_pop($nombre);
            $nom=str_replace(".tmp","",$nom);
            $acuerdo = "view/pdf/escuelas/".$nom.".".$ext[1];
            $sql="INSERT INTO centro (Codigo_centro, Nombre, Direccion ,Municipio , Tipo_centro,Telefono, N_acuerdo, estatus, logo, acuerdo)VALUES('$this->Codigo', '$this->Nombre', '$this->Direccion', '$this->Municipio', '$this->Tipo','$this->Telefono', '$this->N_acuerdo', '$this->Estatus','$logo', '$acuerdo')";
            $vid=$this->ejecutar($sql);
            if($vid>0){$this->respuesta=$this->respuesta."\n INSERCION REALIZADA SATISFACTORIAMENTE";}
            $this->crear_carpeta(__DIR__."/../view/img/escuelas");
            move_uploaded_file($this->Logo['tmp_name'] , __DIR__."/../".$logo);
            $this->crear_carpeta(__DIR__."/../view/pdf/escuelas");
            move_uploaded_file($this->acuerdo['tmp_name'] , __DIR__."/../".$acuerdo);
            print($this->respuesta);
            $this->cone = null;
            unset($this->cone);
        }
        public function lista_centros(){
            session_start();
            try {
                $sql="SELECT cen.*, concat(dir.departamento,' ',dir.municipio) as centro_municipio FROM centro as cen LEFT JOIN direcciones as dir ON dir.id=cen.Municipio";
                if($_SESSION['rango']=='Director(a) Municipal'){
                    $sql.= " WHERE cen.Municipio = ".$_SESSION['id_direccion'];
                }
                print_r(json_encode($this->capturar($sql)));
            }catch(PDOException $e){echo 'error: '.$e->getMessage();}
        }
        public function delete_centro(){
            $vid=$_POST['id'];
            $sql="SELECT logo,acuerdo FROM centro WHERE id_centro=$vid";
            $extencion=$this->capturar($sql);
            $sql="DELETE FROM centro WHERE id_centro = ".$vid;
            $this->ejecutar($sql);
            unlink(__DIR__."/../".$extencion[0]['logo']);
            unlink(__DIR__."/../".$extencion[0]['acuerdo']);
            print("EJECUCION REALIZADA SATISFACTORIAMENTE");
        }
        public function centros_filtrados(){
            try {
                $sql=$sql="SELECT cen.*, concat(dir.departamento,' ',dir.municipio) as centro_municipio FROM centro as cen LEFT JOIN direcciones as dir ON dir.id=cen.Municipio";
                //$sql=$sql="SELECT *, '".CONFIG['RF_CENTRO23']."' AS ruta, 'acuerdos/' AS r_acuerdo FROM ".CONFIG['DB_DATABASE'].".".CONFIG['TB_CENTRO']
                $sql.=" WHERE cen.".$this::codigo ." like '%".$_POST['codigo']."%' and cen.".$this::nombre ." like '%".$_POST['nombre']."%'";
                if(trim($_POST['tipo'])!=""){
                    $sql=$sql." and cen.".$this::tipo ." ='".$_POST['tipo']."'";
                }
                if(trim($_POST['estatus'])!=""){
                    $sql=$sql. " and cen." .$this::estatus ." ='".$_POST['estatus']."'";
                }
                $sql=$sql." and cen.".$this::acuerdo ." like '%".$_POST['acuerdo']."%' and cen.".$this::municipio ." like '%".$_POST['municipio']."%' ";
                print_r(json_encode($this->capturar($sql)));
            }catch(PDOException $e){echo 'error: '.$e->getMessage();}
        }
        public function edit_centro(){
            try {
                $this->vid=$_POST['id'];
                $sql="SELECT * FROM centro WHERE id_centro=".$this->vid;
                $res=$this->capturar($sql);
                $res[0]["pdf_acuerdo"]="acuerdos\\".$res[0]['acuerdo'];
                $res[0]['Foto']=CONFIG["RF_CENTRO"].$res[0]['logo'];
                print_r(json_encode($res));
            }catch(PDOException $e){echo 'error: '.$e->getMessage();}
        }
        public function update_centro(){
            $this->informacion($_POST);
            $imglogo    = "";
            $acuerdopdf = "";
            if(!empty($_FILES["Logo"]) || !empty($_FILES["acuerdo"])){
                $this->archivos($_FILES);
                $sql="SELECT logo,acuerdo FROM centro WHERE id_centro=$this->id";
                $archi=$this->capturar($sql);
                if(!empty($_FILES["acuerdo"])){
                    strpos($this->acuerdo['type'], '/') !== false ? $ext=explode("/", $this->acuerdo['type']):$ext=explode("\\", $this->acuerdo['type']);
                    strpos($this->acuerdo['tmp_name'], '/') !== false ? $acuerdo2=explode("/", $this->acuerdo['tmp_name']) : $acuerdo2=explode("\\", $this->acuerdo['tmp_name']);
                    $nom=array_pop($acuerdo2);
                    $nom=str_replace(".tmp","",$nom);
                    $acuerdopdf="view/pdf/escuelas/".$nom.".".$ext[1];
                    unlink(__DIR__.'/../'.$archi[0]["acuerdo"]);
                    move_uploaded_file($this->acuerdo['tmp_name'] , __DIR__."/../".$acuerdopdf);
                }
                if(!empty($_FILES["Logo"])){
                    strpos($this->Logo['type'], '/') !== false ? $ext=explode("/", $this->Logo['type']):$ext=explode("\\", $this->Logo['type']);
                    strpos($this->Logo['tmp_name'], '/') !== false ? $logo2=explode("/", $this->Logo['tmp_name']) : $logo2=explode("\\", $this->Logo['tmp_name']);
                    $nom=array_pop($logo2);
                    $nom=str_replace(".tmp","",$nom);
                    $imglogo="view/img/escuelas/".$nom.".".$ext[1];
                    unlink(__DIR__.'/../'.$archi[0]["logo"]);
                    move_uploaded_file($this->Logo['tmp_name'] , __DIR__."/../".$imglogo);
                }
            }
            $sql="UPDATE centro SET 
            Codigo_centro='$this->Codigo', 
            Nombre='$this->Nombre', 
            Direccion='$this->Direccion',
            Municipio='$this->Municipio', 
            Tipo_centro='$this->Tipo',
            Telefono='$this->Telefono', 
            N_acuerdo='$this->N_acuerdo',
            estatus='$this->Estatus'";
            if($imglogo!=""){$sql.=", logo='$imglogo'";}
            if($acuerdopdf!=""){$sql.=", acuerdo='$acuerdopdf'";}
            $sql.=" WHERE id_centro = $this->id";
            try {
                $this->vid=$this->ejecutar($sql);
                $this->respuesta=$this->respuesta."\n ACTUALIZACION REALIZADA SATISFACTORIAMENTE";
            } catch(PDOException $e){echo 'ERROR AL INSERTAR: '.$e->getMessage();}    
            print($this->respuesta);
            $this->cone = null;
            unset($this->cone);
        }
        public function centros_activos(){
            session_start();
            $sql="SELECT ".$this::id." , ".$this::codigo." , ".$this::nombre." , ".$this::tipo." , ".$this::municipio." FROM ".CONFIG['DB_DATABASE'].".".CONFIG['TB_CENTRO']." WHERE ".$this::estatus ." ='activo'";
            if(trim($_SESSION["rango"])=="Director" or trim($_SESSION["rango"])=="director"){
                $sql.=" and ".$this::id." = ".$_SESSION["id_centro"];
            }
            if(trim($_SESSION["rango"])=="Director(a) Municipal"){
                $sql.=" and ".$this::municipio." = ".$_SESSION["id_direccion"];
            }
            print_r(json_encode($this->capturar($sql)));
        }
        public function codigo_valido(){
            $sql="SELECT ".$this::id." , ".$this::codigo." , ".$this::nombre." , ".$this::tipo." , ".$this::municipio." FROM ".CONFIG['DB_DATABASE'].".".CONFIG['TB_CENTRO']." WHERE ".$this::codigo ."='".$_POST['codigo']."' and ".$this::id." != ".$_POST['id'];
            //print($sql);die();
            print_r(json_encode($this->capturar($sql)));
        }
        public function comprobar_existencia(){
            session_start();
            $sql="SELECT ".$this::id." , ".$this::estatus." FROM ".CONFIG['DB_DATABASE'].".".CONFIG['TB_CENTRO']." WHERE ".$this::id ."=".$_POST['id'];
            if(trim($_SESSION["rango"])=="Director"){
                $sql.=" and ".$this::id ."=".$_SESSION["id_centro"];
            }
            print_r(json_encode($this->capturar($sql)));
        }
        public function municipio(){
            $sql="SELECT id,departamento,municipio FROM direcciones";
            $obj=$this->capturar($sql);
            print_r(json_encode($obj));
        }
    }