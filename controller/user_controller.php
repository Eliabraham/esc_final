<?php
    include_once("controller.php");
    include_once(__DIR__."/../model/usurios.php");
    class Usuarios extends ControladorPrimario{
        public $md;
        public function iniciar(){
            try{
                include_once($this::encabezado);
                include_once($this::cuerpo);
                include_once($this::acceso);
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function login(){
            $this->md = new ModUsuario;
            if($this->md->cont_usuarios()!=0){
                $asignaciones = $this->md->iniciar_sesion_usuarios();
                if($asignaciones[0]>0){
                    session_start();
                    $_SESSION['id_usuario']=$asignaciones[1][0]['id_usuario'];
                    $_SESSION['id_docente']=$asignaciones[1][0]['id_docente'];
                    $html = file_get_contents($this::menu_sysadmin);
                    $_SESSION["menu"]=$html;
                    $html.="<script id='modelar'>
                        $('.menu-item').hide();
                        $('#modelar').remove();
                    </script>";
                    $opcion="<option value=''>--</option>";
                    for ($i=0; $i < count($asignaciones[2]) ; $i++) { 
                        $opcion.="<option value='"
                        .$asignaciones[2][$i]['id']."--"
                        .$asignaciones[2][$i]['id_centro']."--"
                        .$asignaciones[2][$i]['id_direccion']."--"
                        .$asignaciones[2][$i]['puesto']."--"
                        .$asignaciones[2][$i]['cenmuni']."--"
                        .$asignaciones[2][$i]['dir_muni']."'>"
                        .$asignaciones[2][$i]['puesto'];
                        if($asignaciones[2][$i]['id_centro']!=null){
                            $opcion.=" -- ".$asignaciones[2][$i]['Codigo_centro']." -- ".$asignaciones[2][$i]['Tipo_centro']." -- ".$asignaciones[2][$i]['Nombre'];
                        }
                        if($asignaciones[2][$i]['id_direccion']!=null){
                            $opcion.=" -- ".$asignaciones[2][$i]['dir_muni'];
                        }
                        $opcion.="</opcion>";
                    }
                    $_SESSION['opcion']=$opcion;
                    print_r(json_encode([1,$html,$_SESSION['opcion']]));
                }else{
                    print_r(json_encode($asignaciones));
                }
            }else{
                if($_POST['usuario']=="Gerencia" and $_POST['clave']=="Honduras2023"){
                    $html = file_get_contents($this::menu_sysadmin);
                    $html.='<script id="ocultar">
                    setTimeout(
                        function(){
                            $("#m_escuelas,#m_parte_mensual,#m_direcciones,#m_datos_personales,#ocultar,#m_crear_procesos,#sel_rol").remove();
                            $("#m_docentes, #salir").removeClass("menu-item-7").addClass("menu-item-2");
                        },100);
                    </script>';
                    session_start();
                    $resp = [1,$html];
                    $_SESSION['id_usuario']="G";
                    $_SESSION['id_docente']="";
                    $_SESSION["rango"]="SysAdmin";
                    $_SESSION["menu"]=$html;
                }else{$resp=[0,"NO EXISTEN USUARIOS REGISTRADOS AUN EN EL SISTEMA"];}
                print_r(json_encode($resp));
            }
        }
        public function acceso_docentes(){
            session_start();
            if ($_SESSION['id_usuario']!="G"){
                if ($_SESSION["rango"]=="SysAdmin" || trim($_SESSION["rango"])=="Director" || trim($_SESSION["rango"])=="Director(a) Municipal"){
                    print_r(json_encode([1,file_get_contents($this::menu_docentes)]));
                }
            }else{
                print_r(json_encode([1,file_get_contents($this::menu_docentes)]));
            }
        }
        public function mostrar_menu(){
            SESSION_START();
            $this->establecer_menu();
        }
        public function establecer_menu(){
            $director="<script id='modelar_menu'>
                $('#m_direcciones').remove();
                nmenu=$('.menu-item').length;
                $('.menu-item').addClass('menu-item-'+nmenu);
                $('#modelar_menu').remove();
                $('#sel_rol').remove();
            </script>";
            $muni="<script id='modelar_menu'>
                $('#m_direcciones').remove();
                nmenu=$('.menu-item').length;
                $('.menu-item').addClass('menu-item-'+nmenu);
                $('#sel_rol').remove();
                $('#modelar_menu').remove();
            </script>";
            $admin="<script id='modelar_menu'>
                nmenu=$('.menu-item').length;
                $('.menu-item').addClass('menu-item-'+nmenu);
                $('#modelar_menu').remove();
                $('#sel_rol').remove();
            </script>";
            print($_SESSION["menu"]);
            if(trim($_SESSION["rango"])=="Director"){print($director);}
            if(trim($_SESSION["rango"])=="Director(a) Municipal"){print($muni);}
            if(trim($_SESSION["rango"])=="SysAdmin"){print($admin);}
        }
        public function salir(){
            session_start();
            session_destroy();
            $resp=[file_get_contents($this::acceso)];
            print_r(json_encode($resp));
        }
        public function rol(){
            $erol=explode("--",$_POST['rol']);
            //print_r(json_encode($erol));
            session_start();        
            if($erol[0]!=""){$_SESSION['id_asignacion'] =$erol[0];}
            if($erol[1]!=""){$_SESSION['id_centro']     =$erol[1];}
            if($erol[2]!=""){$_SESSION['id_direccion']  =$erol[2];}
            if($erol[3]!=""){$_SESSION['rango']         =$erol[3];}
            if($erol[4]!=""){$_SESSION['cenmuni']       =$erol[4];}
            if($erol[5]!=""){$_SESSION['dir_muni']      =$erol[5];}
            if(isset($erol[6])!=""){$_SESSION['puesto'] =$erol[6];}
            if($erol[3]=="SysAdmin"){
                $str='$(".menu-item").show().addClass("menu-item-7")';
            }
            if($erol[3]=="Director(a) Municipal"){
                $str='$("#m_direcciones").remove();';
                $str.='$(".menu-item").show().removeClass("menu-item-7");';
                $str.='nmenu=$(".menu-item").length;';
                $str.='$(".menu-item").addClass("menu-item-6")';
            }
            if($erol[3]=="Director"){
                $str='$("#m_direcciones").remove();';
                $str.='$(".menu-item").show().removeClass("menu-item-7");';
                $str.='nmenu=$(".menu-item").length;';
                $str.='$(".menu-item").addClass("menu-item-6")';
                $this->md = new ModUsuario;
                $_SESSION["codigo_escuela"]=$this->md->codigo_escuela();
            }
            $str.=";$('#sel_rol').remove()";
            print($str);
        }
        public function cambiar_rol(){
            session_start();
            print($_SESSION["opcion"]);
        }
        public function acceso_direcciones(){
            session_start();
            if ($_SESSION['id_usuario']!="G"){
                if ($_SESSION["rango"]=="SysAdmin"){
                    print_r(json_encode([1,file_get_contents($this::menu_direcciones)]));
                }
            }else{
                print_r(json_encode([1,file_get_contents($this::menu_direcciones)]));
            }
        }
        public function acceso_escuelas(){
            session_start();
            #print_r($_SESSION);die();
            if (trim($_SESSION["rango"])=="SysAdmin"){
                print_r(json_encode([1,file_get_contents($this::menu_escuelas)]));
            }
            if (trim($_SESSION["rango"])=="Director"){
                $sc="<script id='formatear'>
                    $('#fil_codigo').val('".$_SESSION['codigo_escuela']."');
                    setTimeout(function(){
                        filtrar();
                        $('.filtro').parent().remove();
                        $('#btn_add_centro').remove();
                        $('#ch_sel_todo').remove();
                        $('#formatear').remove();
                    },500);
                </script>";
                print_r(json_encode([1,file_get_contents($this::menu_escuelas).$sc]));
            }
            if (trim($_SESSION["rango"])=="Director(a) Municipal"){
                $sc="<script id='formatear'>
                    setTimeout(function(){
                        con=$('#fil_municipio').parent().hide();
                        $('#fil_municipio').remove();
                        $(con).append(`<input type='hide' id='fil_municipio'/>`);
                        $('#fil_municipio').val('".$_SESSION['id_direccion']."');
                        $('#txtMunicipio').val(".$_SESSION['id_direccion'].")
                        $('#txtMunicipio').attr('disabled',true);
                        $('#formatear').remove();
                    },150);
                </script>";
                print_r(json_encode([1,file_get_contents($this::menu_escuelas).$sc]));
            }
        }
        public function Datos_Personales(){
            session_start();
            $this->md = new ModUsuario;
            $this->datos=$this->md->datos_personales($_SESSION["id_usuario"]);
            print_r(json_encode([$this->datos,file_get_contents($this::menu_datos_personales)]));
        }
        public function acceso_parte_mensual(){
            session_start();
            if ($_SESSION['id_usuario']!="G"){
                if ($_SESSION["rango"]=="SysAdmin"){
                    $sc="<script id='formatear'>
                        setTimeout(function(){
                            $('#btn_add_parte').remove();
                            $('#formatear').remove();
                        },500);
                    </script>";
                    print_r(json_encode([1,file_get_contents($this::menu_parte_mensual).$sc]));
                }
                if (trim($_SESSION["rango"])=="Director"){
                    $sc="<script id='formatear'>
                        setTimeout(function(){
                            $('#centro').parent().remove();
                            $('#btn_faltantes').remove();
                            $('#formatear').remove();
                        },500);
                    </script>";
                    print_r(json_encode([1,file_get_contents($this::menu_parte_mensual).$sc]));
                }
                if (trim($_SESSION["rango"])=="Director(a) Municipal"){
                    $sc="<script id='formatear'>
                        setTimeout(function(){
                            $('#btn_add_parte,#btn_faltantes').remove();
                            $('#formatear').remove();
                        },200);
                    </script>";
                    print_r(json_encode([1,file_get_contents($this::menu_parte_mensual).$sc]));
                }
            }else{

                print_r(json_encode([1,file_get_contents($this::menu_parte_mensual)]));
            }
        }
        public function Crear_procesos(){
            session_start();
            if ($_SESSION["rango"]=="SysAdmin"){
                print_r(json_encode([1,file_get_contents($this::crear_operaciones)]));
            }
            if(trim($_SESSION["rango"])=="Director"){
                $sc="<script id='formato'>
                    $('#txtsol_centro').parent().remove();
                    $('.fc,#formato').remove();
                </script>";
                print_r(json_encode([1,file_get_contents($this::usar_operaciones).$sc]));
            }
            if(trim($_SESSION["rango"])=="Director(a) Municipal"){
                $sc="<script id='formato'>
                    $('#formato').remove();
                </script>";
                print_r(json_encode([1,file_get_contents($this::usar_operaciones).$sc]));
            }
        }
        public function cla_menu(){
            session_start();
            if($_SESSION["rango"]=="Director(a) Municipal"){
                print("$('m_direcciones').remove();");
            };
        }
        public function centros_solicitudes_dm(){
            try{
                $this->md = new ModUsuario;
                $this->md->centros_solicitudes_dm();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        public function Mostrar_identidad(){
            try{
                $this->md = new ModUsuario;
                $this->md->mostrar_identidad();
            }
            catch (Exception $e){echo 'error: '.$e->getMessage();}
        }
        /*public function seleccionar_rol(){
            session_start();
            if (strpos($_POST["cargo"], '-') !== false){
                $partes = explode("-", $_POST['cargo']);
                $_SESSION["rango"]=$partes[0];
                if (trim($partes[0])=="Director"){
                    $_SESSION["escuela"]=$partes[3];
                    $_SESSION["codigo_escuela"]=trim($partes[1]);
                    $_SESSION["tipo_escuela"]=trim($partes[4]);
                }
            }
            else{$_SESSION['rango']=$_POST["cargo"];}
            $this->establecer_menu();
        }
        */
    }
    $clas=new Usuarios;
    switch ($_POST["gv_action"]) {
        case "acceder"              : $clas->iniciar(); break;
        case "ingresar"             : $clas->login(); break;
        case "menu_docente"         : $clas->acceso_docentes(); break;
        case "volver_menu"          : $clas->mostrar_menu(); break;
        case "salir"                : $clas->salir(); break;
        case "tomar_rol"            : $clas->rol(); break;
        case "cambiar_rol"          : $clas->cambiar_rol(); break;
        case "direcciones"          : $clas->acceso_direcciones(); break;
        case "menu_escuela"         : $clas->acceso_escuelas(); break;
        case "datos_personales"     : $clas->Datos_Personales(); break;
        case "parte_mensual"        : $clas->acceso_parte_mensual(); break;
        case "crear_procesos"       : $clas->Crear_procesos(); break;
        case "clasificar_menu"      : $clas->cla_menu(); break;
        case "centros_solicitudes_dm": $clas->centros_solicitudes_dm();break;
        case "Mostrar_identidad" ; $clas->Mostrar_identidad();break;
        #case "seleccionar_posicion" : $clas->seleccionar_rol(); break;
    }
