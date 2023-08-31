<?php
    include_once("controller.php");
    include_once(__DIR__."/../model/docentes.php");
    include_once(__DIR__."/../model/asignacion.php");
    class Docentes extends ControladorPrimario{
        public $md;
        public function crear_docente(){
            try{
                $this->md=new ModDocente;
                $this->md->insertar_docente();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function listar_docentes(){
            try{
                $this->md=new ModDocente;
                $this->md->lista_docentes();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function filtrar_docente(){
            try{
                $this->md = new ModDocente;
                $this->md->docentes_filtrados();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function verificar_por_identidad(){
            try{
                $this->md = new ModDocente;
                $this->md->verificar_identidad_docente();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function eliminar_docente(){
            try{
                $this->md=new ModDocente;
                $this->md->delete_docente();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function editar_docente(){
            try{
                $this->md = new ModDocente;
                $this->md->edit_docente();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function actualizar_docente(){
            try{
                $this->md = new ModDocente;
                $this->md->update_docente();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function show_asignaciones(){
            try{
                $this->md = new Asignacion;
                $this->md->mostrar_centro_Asignado();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function list_est_pre(){
            try{
                $this->md = new Asignacion;
                $this->md->list_est_pre();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function filtrar_direcciones(){
            try{
                $this->md = new ModDocente;
                $this->md->filtrar_direcciones();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function asignar_direccion(){
            try{
                $this->md = new ModDocente;
                $this->md->asignar_direccion();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function lista_direcciones_asignadas(){
            try{
                $this->md = new ModDocente;
                $this->md->lista_direcciones_asignadas();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function asignar(){
            try{
                $this->md = new Asignacion;
                $this->md->asignar_centro();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function eliminar_asignacion(){
            try{
                $this->md = new Asignacion;
                $this->md->eliminar_centro_Asignado();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function modificar_asignacion(){
            try{
                $this->md = new Asignacion;
                $this->md->modificar_centro_asignado();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function ins_est_pre(){
            try{
                $this->md = new Asignacion;
                $this->md->add_est_pre();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function del_est_pre(){
            try{
                $this->md = new Asignacion;
                $this->md->del_est_pre();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function actualizar_datos_personales(){
            try{
                $this->md = new ModDocente;
                $this->md->datos_personales();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function Cambiar_Clave(){
            try{
                $this->md = new ModDocente;
                $this->md->Cambiar_Clave();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function verificar_existencia_email(){
            try{
                $this->md = new ModDocente;
                $this->md->verificar_existencia_email();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
    }
    //print_r($_POST);die();
    $clas=new Docentes;
    switch ($_POST["gv_action"])
    {
        case "crear_docente"        : $clas->crear_docente(); break;
        case "listar_docentes"      : $clas->listar_docentes(); break;
        case "filtrar_docentes"     : $clas->filtrar_docente(); break;
        case "verificar_existencia" : $clas->verificar_por_identidad(); break;
        case "eliminar_docente"     : $clas->eliminar_docente(); break;
        case "editar_docente"       : $clas->editar_docente(); break;
        case "actualizar_docente"   : $clas->actualizar_docente(); break;
        case "mostrar_asignaciones" : $clas->show_asignaciones(); break;
        case "lista_estructura_presupuestaria" : $clas->list_est_pre();break;
        case "filtrar_direcciones"  : $clas->filtrar_direcciones();break;
        case "asignar_direccion"    : $clas->asignar_direccion();break;
        case "lista_direcciones_asignadas"    : $clas->lista_direcciones_asignadas();break;
        case "asignar_centro"       : $clas->asignar(); break;
        case "eliminar_asignacion"  : $clas->eliminar_asignacion(); break;
        case "modificar_asignacion" : $clas->modificar_asignacion(); break;
        case "insertar_estructura_presupuestaria" : $clas->ins_est_pre(); break;
        case "eliminar_estructura_presupuestaria" : $clas->del_est_pre();break;
        case "actualizar_mis_datos" : $clas->actualizar_datos_personales();break;
        case "Cambiar_Clave" : $clas->Cambiar_Clave();break;
        case "verificar_existencia_email" : $clas->verificar_existencia_email();break;
    }