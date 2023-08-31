<?php
    include_once("controller.php");
    include_once(__DIR__."/../model/centros.php");
    class Centros extends ControladorPrimario{
        public $md;
        public function crear_centro(){
            try{
                $this->md=new ModCentro;
                $this->md->insertar_centro();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function listar_centro(){
            try{
                $this->md=new ModCentro;
                $this->md->lista_centros();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function eliminar_centro(){
            try{
                $this->md=new ModCentro;
                $this->md->delete_centro();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function editar_centro(){
            try{
                $this->md = new ModCentro;
                $this->md->edit_centro();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function filtrar_centro(){
            try{
                $this->md = new ModCentro;
                $this->md->centros_filtrados();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function actualizar_centro(){
            try{
                $this->md = new ModCentro;
                $this->md->update_centro();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function centros_activos(){
            try{
                $this->md = new ModCentro;
                $this->md->centros_activos();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function validar_codigo(){
            try{
                $this->md = new ModCentro;
                $this->md->codigo_valido();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function comprobar_existencia(){
            try{
                $this->md = new ModCentro;
                $this->md->comprobar_existencia();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function municipio(){
            try{
                $this->md = new ModCentro;
                $this->md->municipio();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}   
        }
    }
    $clas=new Centros;
    switch ($_POST["gv_action"])
    {
        case "crear_centro"        : $clas->crear_centro(); break;
        case "listar_centros"      : $clas->listar_centro(); break;
        case "eliminar_centro"     : $clas->eliminar_centro(); break;
        case "editar_centro"       : $clas->editar_centro(); break;
        case "filtrar_centros"     : $clas->filtrar_centro(); break;
        case "modificar_centro"    : $clas->actualizar_centro(); break;
        case "centros_activos"     : $clas->centros_activos(); break;
        case "validar_codigo"      : $clas->validar_codigo(); break;
        case "validar_asignacion"  : $clas->comprobar_existencia(); break;
        case "municipios"  : $clas->municipio(); break;
    }