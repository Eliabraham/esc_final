<?php
  include_once("model.php");
  class Asignacion extends ProgramModel{
    public function mostrar_centro_Asignado(){
        $this->docente = $_POST['docente'];
        $sql="SELECT desg.*, cent.Codigo_centro as codcent, cent.Nombre as nomcent, cent.Direccion as dircent, cent.Municipio as muncent, cent.Tipo_centro as tipocent
        FROM designaciones AS desg 
        INNER JOIN centro AS cent ON cent.id_centro=desg.id_centro
        WHERE desg.id_docente = $this->docente AND desg.id_centro IS NOT NULL";
        session_start();
        if(trim($_SESSION["rango"])=="Director"){
            $sql.=" and desg.id_centro = ".$_SESSION["id_centro"] ;
        }
        if(trim($_SESSION["rango"])=="Director(a) Municipal"){
            $sql.=" and cent.Municipio = ".$_SESSION["id_direccion"] ;
        }
        print_r(json_encode($this->capturar($sql)));
    }
    public function list_est_pre(){
        $designacion=$_POST['asignacion'];
        $sql="SELECT * FROM estructura_presupuestaria WHERE id_designacion = $designacion";
        print_r(json_encode($this->capturar($sql)));
    }
    public function asignar_centro(){
        session_start();
        $this->docente           = $_POST['docente'];
        $this->centro            = $_POST['centro'];
        $this->estatus           = $_POST['estatus'];
        $this->puesto            = $_POST['puesto'];
        $this->condicion         = $_POST['condicion'];
        $this->horas             = $_POST['horas'];
        #$this->fechaNombramiento = $_POST['fechaNombramiento'];
        $fechaNombramiento = $_POST['fechaNombramiento'];
        $fechaObjeto = new DateTime($fechaNombramiento);
        $this->fechaNombramiento = $fechaObjeto->format("d-m-Y");
        #$this->fechaVencimiento  = $_POST['fechaVencimiento'];
        $fechaVencimiento = $_POST['fechaVencimiento'];
        $fechaObjeto = new DateTime($fechaVencimiento);
        $this->fechaVencimiento = $fechaObjeto->format("d-m-Y");
        $existencia=0;
        if($_SESSION['rango']=="Director"){
            if($this->centro!=$_SESSION['id_centro']){
                print("NO TIENE AUTORIDAD PARA ASIGNAR PERSONAL A ESTE CENTRO");die();
            }
        }
        if($_SESSION['rango']=="Director(a) Municipal"){
            $sql="SELECT id_centro FROM centro WHERE id_centro=$this->centro AND Municipio=".$_SESSION['id_direccion'];
            if($this->contar($sql)==0){
                print("NO TIENE AUTORIDAD PARA ASIGNAR PERSONAL A ESTE CENTRO");die();
            }
        }
        if(trim($this->puesto)=="Director"){
            $sql = "SELECT * FROM designaciones WHERE id_centro=$this->centro and puesto='$this->puesto' and (
                (fasignacion = '$this->fechaNombramiento') or 
                ('$this->fechaNombramiento' < fasignacion and '$this->fechaVencimiento' > fasignacion) or
                ('$this->fechaNombramiento' > fasignacion and '$this->fechaNombramiento' < fvencimiento)
            )";
            $existencia=$this->contar($sql);
        }
        if($existencia==0){
            $sql="INSERT INTO designaciones (id_docente, id_centro, puesto, condicion, estatus, horas, fasignacion,fvencimiento) VALUES ($this->docente, $this->centro, '$this->puesto' , '$this->condicion' , '$this->estatus' , '$this->horas' , '$this->fechaNombramiento' , '$this->fechaVencimiento')";
            $this->vid=$this->ejecutar($sql);
            if($this->vid[0]==1){$this->respuesta="\n INSERCION REALIZADA SATISFACTORIAMENTE";}
            else{$this->respuesta="\n".$this->vid[1][2];}
            $sql="SELECT id_usuario FROM usuarios WHERE id_docente=$this->docente";
            $calc=$this->contar($sql);
            if($calc==0){
                $sql = "SELECT id, Correo, CONCAT(Nombre1, ' ', Nombre2, ' ', Apellido1, ' ', Apellido2) AS NombreCompleto FROM docente WHERE id = $this->docente";
                $persona=$this->capturar($sql);
                $par = $persona[0]['Correo'];
                $nombre=$persona[0]['NombreCompleto'];
                $sql="INSERT INTO usuarios(nombre, usuario, clave, id_docente)VALUES('$nombre','$par','$par',$this->docente)";
                $this->ejecutar($sql);
                print("ACCION REALIZADA SATISFACTORIAMENTE");
            }
        }else{
            $data=$this->capturar($sql);
            print_r(json_encode([0,"YA HA SIDO ASIGNADO UN DIRECTOR A ESTA UNIDAD EDUCATIVA (desde: ".$data[0]['fasignacion']." Hasta: ".$data[0]['fvencimiento'].")"]));
        }
    }
    public function eliminar_centro_Asignado(){
        $this->vid = $_POST['id'];
        $sql ="DELETE FROM designaciones WHERE id =  $this->vid";
        try {
            $this->ejecutar($sql);
            $this->respuesta="EJECUCION REALIZADA SATISFACTORIAMENTE";
            print($this->respuesta);
        } catch(PDOException $e){echo 'ERROR AL EJECUTAR: '.$e->getMessage();}
    }
    public function modificar_centro_asignado(){
        $this->centro            = $_POST['centro'];
        $this->estatus           = $_POST['estatus'];
        $this->puesto            = $_POST['puesto'];
        $this->condicion         = $_POST['condicion'];
        $this->horas             = $_POST['horas'];
        #$this->fechaNombramiento = $_POST['fechaNombramiento'];
        $fechaNombramiento = $_POST['fechaNombramiento'];
        $fechaObjeto = new DateTime($fechaNombramiento);
        $this->fechaNombramiento = $fechaObjeto->format("d-m-Y");
        #$this->fechaVencimiento  = $_POST['fechaVencimiento'];
        $fechaVencimiento = $_POST['fechaVencimiento'];
        $fechaObjeto = new DateTime($fechaVencimiento);
        $this->fechaVencimiento = $fechaObjeto->format("d-m-Y");
        $this->vid               = $_POST['id'];
        $sql="UPDATE designaciones SET id_centro = '$this->centro', puesto = '$this->puesto', condicion= '$this->condicion', estatus= '$this->estatus', horas = '$this->horas', fasignacion = '$this->fechaNombramiento', fvencimiento= '$this->fechaVencimiento' WHERE id = $this->vid";
        try {
            $vid=$this->ejecutar($sql);
            if($vid[0]>0){$this->respuesta="ACTUALIZACION REALIZADA SATISFACTORIAMENTE";}
            else{$this->respuesta=$vid[1][2];}
            print($this->respuesta);
        } catch(PDOException $e){echo 'ERROR AL INSERTAR: '.$e->getMessage();}
    }
    public function add_est_pre(){
        $centro       = $_POST['codigo_centro'];
        $plaza        = $_POST['codigo_plaza'];
        $designacion  = $_POST['designacion'];
        $dependencia  = $_POST["dependencia"];
        $departamento = $_POST["departamento"];
        $municipio    = $_POST["municipio"];
        $horas        = $_POST["horas"];
        $sql="SELECT id FROM estructura_presupuestaria WHERE cod_centro = '$centro' and cod_plaza = '$plaza'";
        if($this->contar($sql)==0){
            $sql="INSERT INTO estructura_presupuestaria (id_designacion, dependencia, departamento, municipio, cod_centro, cod_plaza, horas) VALUES($designacion, '$dependencia', '$departamento', '$municipio', '$centro', '$plaza', '$horas')";
            try {
                $this->ejecutar($sql);
                $this->respuesta="EJECUCION REALIZADA SATISFACTORIAMENTE";
                print($this->respuesta);
            } catch(PDOException $e){echo 'ERROR AL EJECUTAR: '.$e->getMessage();}
        }else{
            print ("ESTA PLAZA EN ESTE CENTRO YA HA SIDO OCUPADA");
        }
    }
    public function del_est_pre(){
        $estructura=$_POST['estructura'];
        $sql="DELETE FROM estructura_presupuestaria WHERE id = $estructura";
        try {
            $this->ejecutar($sql);
            $this->respuesta="EJECUCION REALIZADA SATISFACTORIAMENTE";
            print($this->respuesta);
        } catch(PDOException $e){echo 'ERROR AL EJECUTAR: '.$e->getMessage();}
    }
}
