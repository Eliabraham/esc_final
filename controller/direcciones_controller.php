<?php
    include_once("controller.php");
    include_once(__DIR__."/../model/direccion.php");
    class DireccionController extends ControladorPrimario{
        public $md;
        public $info;
        public function crear_direccion(){
            try{
                $this->md   = new ModDireccion;
                $this->md->insertar_direccion();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function listar_direcciones(){
            try{
                $this->md   = new ModDireccion;
                $this->md->listar_direccion();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function eliminar_direcciones(){
            try{
                $this->md = new ModDireccion;
                $this->md->del_direccion();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function editar_direcciones(){
            try{
                $this->md = new ModDireccion;
                $this->md->edit_direccion();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function actualizar_direccion(){
            try{
                $this->md = new ModDireccion;
                $this->md->update_direccion();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function filtrar_direcciones(){
            try{
                $this->md = new ModDireccion;
                $this->md->filtrar_direccion();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function crear_director(){
            try{
                $this->md = new ModDireccion;
                $this->md->crear_director();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function listar_docentes(){
            try{
                $this->md = new ModDireccion;
                $this->md->listar_docentes();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function listar_directores(){
            try{
                $this->md = new ModDireccion;
                $this->md->listar_directores();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function eliminar_asignacion(){
            try{
                $this->md = new ModDireccion;
                $this->md->eliminar_asignacion();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
    }
    //print_r($_POST);die();
    $clas=new DireccionController;
    switch ($_POST["gv_action"])
    {
        case "crear-direccion"      : $clas->crear_direccion(); break;
        case "listar-direcciones"   : $clas->listar_direcciones(); break;
        case "eliminar_direccion"   : $clas->eliminar_direcciones(); break;
        case "editar_direccion"     : $clas->editar_direcciones(); break;
        case "actualizar-direccion" : $clas->actualizar_direccion();break;
        case "filtrar_direccion"    : $clas->filtrar_direcciones();break;
        case "crear_director"       : $clas->crear_director();break;
        case "listar_docentes"      : $clas->listar_docentes();break;
        case "listar_directores"    : $clas->listar_directores();break;
        case 'eliminar_asignacion'  : $clas->eliminar_asignacion();break;
    }