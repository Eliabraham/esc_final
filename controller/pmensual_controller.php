<?php
    include_once("controller.php");
    include_once(__DIR__."/../model/pmensuales.php");
    class Pmensuales extends ControladorPrimario{
        public $md;
        public function crear_parte(){
            try{
                $this->md=new Pmensual;
                $this->md->crear_parte();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function Listar_parte(){
            try{
                $this->md=new Pmensual;
                $this->md->Lista_pmensual();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function  datos_mes_anterio(){
            try{
                $this->md=new Pmensual;
                $this->md->datos_mes_anterio();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function docentes_mes_anterio(){
            try{
                $this->md=new Pmensual;
                $this->md->docentes_mes_anterio();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function eliminar_parte_mensual(){
            try{
                $this->md=new Pmensual;
                $this->md->eliminar_parte_mensual();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function filtrar_reportes(){
            try{
                $this->md=new Pmensual;
                $this->md->filtrar_parte_mensual();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function l_centro(){
            try{
                $this->md=new Pmensual;
                $this->md->l_centro();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function lanno(){
            try{
                $this->md=new Pmensual;
                $this->md->lanno();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
    }
    //print_r($_POST);die();
    $clas=new Pmensuales;
    switch ($_POST["gv_action"])
    {
        case "guardar_parte"     : $clas->crear_parte(); break;
        case "lista"             : $clas->Listar_parte(); break;
        case "datos_mes_anterio" : $clas->datos_mes_anterio(); break;
        case "docentes_mes_anterio" : $clas->docentes_mes_anterio(); break;
        case "eliminar_parte_mensual" : $clas->eliminar_parte_mensual();break;
        case "filtrar_reportes"  : $clas->filtrar_reportes();break;
        case "l_centro"  : $clas->l_centro();break;
        case "lanno"      :$clas->lanno();break;
    }