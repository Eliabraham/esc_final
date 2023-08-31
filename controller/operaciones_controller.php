<?php
    include_once("controller.php");
    include_once(__DIR__."/../model/operacion.php");
    class Operaciones extends ControladorPrimario{
        public $md;
        public function crear_operacion(){
            try{
                $this->md=new Operacion;
                $this->md->insertar_operacion();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function lista_tarjetas(){
            try{
                $this->md=new Operacion;
                $this->md->lista_tarjetas();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function eliminar_opcion(){
            try{
                $this->md=new Operacion;
                $this->md->eliminar_opcion();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function filtrar_opcion(){
            try{
                $this->md=new Operacion;
                $this->md->filtrar_opcion();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function editar_operacion(){
            try{
                $this->md=new Operacion;
                $this->md->editar_operacion();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function eliminar_campo(){
            try{
                $this->md=new Operacion;
                $this->md->eliminar_campo();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function modificar_operacion(){
            try{
                $this->md=new Operacion;
                $this->md->modificar_operacion();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function mostrar_solicitudes_sa(){
            try{
                $this->md=new Operacion;
                $this->md->mostrar_solicitudes_sa();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function iniciar_filtros(){
            try{
                $this->md=new Operacion;
                $this->md->iniciar_filtros();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function finalizar_tramite(){
            try{
                $this->md=new Operacion;
                $this->md->finalizar_tramite();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
    }

    class Solicitudes extends ControladorPrimario{
        public $md;
        public function nombre_solicitudes(){
            try{
                $this->md=new Solicitud;
                $this->md->nombre_solicitudes();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function seleccionar_operacion(){
            try{
                $this->md=new Solicitud;
                $this->md->seleccionar_operacion();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function crear_solicitud(){
            try{
                $this->md=new Solicitud;
                $this->md->crear_solicitud();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function ingresar_cuerpo_solicitud(){
            try{
                $this->md=new Solicitud;
                $this->md->ingresar_cuerpo_solicitud();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function listar_solicitudes(){
            try{
                $this->md=new Solicitud;
                $this->md->listar_solicitudes();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function filtrar_solicitudes(){
            try{
                $this->md=new Solicitud;
                $this->md->filtrar_solicitudes();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function eliminar_solicitud(){
            try{
                $this->md=new Solicitud;
                $this->md->eliminar_solicitud();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function editar_solicitud(){
            try{
                $this->md=new Solicitud;
                $this->md->editar_solicitud();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function modificar_solicitud(){
            try{
                $this->md=new Solicitud;
                $this->md->modificar_solicitud();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function modificar_cuerpo_solicitud(){
            try{
                $this->md=new Solicitud;
                $this->md->modificar_cuerpo_solicitud();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function insertar_observacion(){
            try{
                $this->md=new Solicitud;
                $this->md->insertar_observacion();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function buscar_observaciones(){
            try{
                $this->md=new Solicitud;
                $this->md->buscar_observaciones();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function lista_estados(){
            try{
                $this->md=new Solicitud;
                $this->md->lista_estados();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
    }
    $clas=new Operaciones;
    $clas2=new Solicitudes;
    
    #print_r($_POST);die();
    switch ($_POST["gv_action"])
    {
        case "crear_operacion" : $clas->crear_operacion(); break;
        case "lista_tarjetas_operaciones" : $clas->lista_tarjetas(); break;
        case "eliminar_opcion" : $clas->eliminar_opcion(); break;
        case "filtrar_opcion"  : $clas->filtrar_opcion(); break;
        case "editar_operacion": $clas->editar_operacion(); break;
        case "eliminar_campo"  : $clas->eliminar_campo(); break;
        case "modificar_operacion"  : $clas->modificar_operacion(); break;
        case "mostrar_solicitudes_sa" : $clas->mostrar_solicitudes_sa(); break;
        case "iniciar_filtros" : $clas->iniciar_filtros(); break;
        case "finalizar_tramite" : $clas->finalizar_tramite();break;
        //______________________________________________________________________________________________________________________________________________________________________________________________________________________
        case "lista_nombre_operaciones" : $clas2->nombre_solicitudes();break;
        case "seleccionar_operacion" : $clas2->seleccionar_operacion();break;
        case "ingresar_solicitud" : $clas2->crear_solicitud();break;
        case "ingresar_cuerpo_solicitud" : $clas2->ingresar_cuerpo_solicitud();break;
        case "listar_solicitudes" : $clas2->listar_solicitudes();break;
        case "filtrar_solicitudes": $clas2->filtrar_solicitudes();break;
        case "eliminar_solicitud" : $clas2->eliminar_solicitud();break;
        case "editar_solicitud" : $clas2->editar_solicitud();break;
        case "modificar_solicitud" : $clas2->modificar_solicitud();break;
        case "modificar_cuerpo_solicitud" :$clas2->modificar_cuerpo_solicitud();break;
        case "insertar_observacion" : $clas2->insertar_observacion(); break;
        case "buscar_observaciones" : $clas2->buscar_observaciones(); break;
        case "lista_estados" : $clas2->lista_estados();break;
    }
    