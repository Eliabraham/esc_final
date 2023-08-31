<?php
    include_once("model.php");
    class Pmensual extends ProgramModel{
        public function crear_parte(){
            session_start();
            $escuela=$_SESSION["id_centro"];
            $this->informacion($_POST);
            $director=$_SESSION['id_docente'];
            $sql="INSERT INTO parte_mensual(id_centro, doc_mas, doc_fem, tot_doc, anno, mes, dias_trab, anno_ant, mes_ant, director) VALUES  ($escuela, '$this->ndocmas', '$this->ndocfem', '$this->ntotdoc', '$this->anno_parte', '$this->mesparte', '$this->ndiastrab', '$this->anno_parte_anterior', '$this->mesparteanterior',$director)";
            $idparte=$this->ejecutar($sql);
            print("PARTE MENSUAL GUARDADO SATISFACTORIAMENTE");
            $docente=json_decode($this->docentes);
            $nu=1;
            foreach ($docente as $data) {
                $sql = "INSERT INTO docente_parte_mensual (id_parte_mensual, numero, nombre, cargo, grado, n_alumnos, inasistencia_autorizadas, inasistencias_autorizadas, total_inasistencia, Observaciones) VALUES ($idparte[0], $nu, '$data[0]', '$data[1]', '$data[2]', $data[3], $data[4], $data[5], $data[6], '$data[7]')";
                #print($sql."\n");
                $this->ejecutar($sql);
                $nu++;
            } 
            print("\nDOCENTES GUARDADOS SATISFACTORIAMENTE\n");            
            $detalle=json_decode($this->detalles);
            //print_r($detalle);
            foreach ($detalle as $data) {
                $sql = "INSERT INTO detalle_parte_mensual (id_parte_mensual, grado, mat_con_hem, mat_con_var, mat_con_tot, mat_ant_hem, mat_ant_var, mat_ant_tot, mat_act_hem, mat_act_var, mat_act_tot, asis_med_hem, asis_med_var, asis_med_tot, tant_porc_hem, tant_porc_var, tant_porc_tot, inasistencia_hem, inasistencia_var, inasistencia_tot, ingreso_hem, ingreso_var, ingreso_tot, desertores_hem, desertores_var, desertores_tot, traslados_hem, traslados_var, traslados_tot) VALUES ($idparte[0], '$data[0]', $data[1], $data[2], $data[3], $data[4], $data[5], $data[6],$data[7], $data[8], $data[9], $data[10], $data[11], $data[12], $data[13], $data[14], $data[15], $data[16], $data[17], $data[18], $data[19], $data[20], $data[21], $data[22], $data[23], $data[24], $data[25], $data[26], $data[27])";
                #print($sql."\n");
                $this->ejecutar($sql);
            }
            print("DETALLES GUARDADOS SATISFACTORIAMENTE\n");
        }
        public function Lista_pmensual(){
            session_start();
            $sql="SELECT pm.*, cen.* FROM parte_mensual AS pm INNER JOIN centro AS cen ON cen.id_centro=pm.id_centro";
            if(trim($_SESSION["rango"])=="Director"){
                $sql.=" WHERE pm.id_centro = ".$_SESSION['id_centro'];
            }
            if(trim($_SESSION["rango"])=="Director(a) Municipal"){
                $sql.=" WHERE cen.Municipio = ".$_SESSION['id_direccion'];
            }
            #print($sql);
            $resp=$this->capturar($sql);
            print_r(json_encode($resp));
        }
        public function datos_mes_anterio(){
            $anno =  $_POST['anno'];
            $mes = $_POST['mes'];
            $sql="SELECT pm.id, dt.grado, dt.mat_act_hem, dt.mat_act_var, dt.mat_act_tot
            FROM parte_mensual AS pm 
            LEFT JOIN detalle_parte_mensual AS dt ON dt.id_parte_mensual = pm.id  
            WHERE pm.anno =$anno AND pm.mes = '$mes' AND dt.grado!='Subtotal'";
            $resp=$this->capturar($sql);
            print_r(json_encode($resp));
        }
        public function docentes_mes_anterio(){
            $anno =  $_POST['anno'];
            $mes = $_POST['mes'];
            $sql="SELECT pm.id, dc.nombre, dc.cargo, dc.grado
            FROM parte_mensual AS pm 
            LEFT JOIN docente_parte_mensual AS dc ON dc.id_parte_mensual = pm.id  
            WHERE pm.anno =$anno AND pm.mes = '$mes'";
            $resp=$this->capturar($sql);
            print_r(json_encode($resp));
        }
        public function l_centro(){
            session_start();
            $sql="SELECT id_centro, Codigo_centro, Nombre, Tipo_centro FROM centro";
            if($_SESSION['rango']=="Director(a) Municipal"){
                $sql.=" WHERE Municipio=".$_SESSION["id_direccion"];
            }
            $resp=$this->capturar($sql);
            print_r(Json_encode($resp));
        }
        public function eliminar_parte_mensual(){
            $sql="DELETE FROM parte_mensual WHERE id=".$_POST['id'];
            $del=$this->ejecutar($sql);
            if ($del[0]==1){print("ACCION REALIZADA SATISFACTORIAMENTE");}
            else{print($del[1][2]);}
        }
        public function filtrar_parte_mensual(){
            session_start();
            $sql="SELECT pm.*, cen.* FROM parte_mensual AS pm LEFT JOIN centro AS cen ON cen.id_centro=pm.id_centro WHERE pm.id > 0";
            if(trim($_SESSION["rango"])=="Director"){
                $sql.=" AND  pm.id_centro = ".$_SESSION['id_centro'];
            }
            if($_POST["centro"]!=""){
                $sql.=" AND pm.id_centro = '".$_POST['centro']."'";
            }
            if($_POST["anno"]!=""){
                $sql.=" AND pm.anno =".$_POST['anno'];
            }
            if($_POST["mes"]!=""){
                $sql.=" AND pm.mes = '".$_POST['mes']."'";
            }
            //print($sql);die();
            $resp=$this->capturar($sql);
            print_r(json_encode($resp));
        }
        public function lanno(){
            $sql="SELECT DISTINCT anno FROM parte_mensual";
            $resp=$this->capturar($sql);
            print_r(json_encode($resp));
        }
    }
