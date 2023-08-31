$(document).ready(function(){
    $("#m_docentes").on({
        "click":function(){
            $.ajax({
                type: "POST",
                url:  rt_usuarios,
                data:{gv_action:'menu_docente'},
                cache:false,
            }).done(function(resp){
                let r=(JSON.parse(resp));
                if(r[0]==0){$.notify(r[1], "warn");}
                if(r[0]==1){$("body").html(r[1]);}
            }).fail(function(jqXHR, textStatus){
                validate.error_ajax(jqXHR, textStatus);
            });
        }
    });
    $("#cargo").on({
        "change":function(){
            if(this.value!=""){
                $.ajax({
                    type: "POST",
                    url:  rt_usuarios,
                    data:{gv_action:'tomar_rol', rol:this.value},
                    cache:false,
                }).done(function(resp){
                    eval(resp);
                    $("#myModal").modal("toggle");
                }).fail(function(jqXHR, textStatus){
                    validate.error_ajax(jqXHR, textStatus)
                });
            }
        }
    })
    $("#salir").on({
        "click":function(){
            $.ajax({
                type: "POST",
                url:  rt_usuarios,
                data:{gv_action:'salir'},
                cache:false,
            })
            .done(function(resp){
                resp=JSON.parse(resp);
                $("body").html(resp);
            })
            .fail(function(jqXHR, textStatus){validate.error_ajax(jqXHR, textStatus);});
        }
    });
    $("#cer_mod").on({
        "click":function(){
            $("#myModal").modal("toggle");
        }
    });
    $("#sel_rol").on({
        "click":function(){
            $.ajax({
                type: "POST",
                url:  rt_usuarios,
                data:{gv_action:'cambiar_rol'},
                cache:false,
            }).done(function(resp){
                $("#cargo").html(resp);
            }).fail(function(jqXHR, textStatus){
                validate.error_ajax(jqXHR, textStatus)
            });
            $("#myModal").modal("toggle");
        }
    });
    $("#m_escuelas").on({
        "click":function(){
            $.ajax({
                type: "POST",
                url:  rt_usuarios,
                data:{gv_action:'menu_escuela'},
                cache:false,
            }).done(function(resp){
                let r=(JSON.parse(resp));
                if(r[0]==0){$.notify(r[1], "warn");}
                if(r[0]==1){$("body").html(r[1]);}
            }).fail(function(jqXHR, textStatus){
                validate.error_ajax(jqXHR, textStatus);
            })
        }
    });
    $("#m_parte_mensual").on({
        "click":function(){
            $.ajax({
                type: "POST",
                url:  rt_usuarios,
                data:{gv_action:'parte_mensual'},
                cache:false,
            }).done(function(resp){
                let r=JSON.parse(resp);
                if(r[0]==0){$.notify(r[1], "warn");}
                if(r[0]==1){$("body").html(r[1]);}
            }).fail(function(jqXHR, textStatus){
                validate.error_ajax(jqXHR, textStatus);
            });
        }
    });
    $("#m_direcciones").on({
        "click":function(){
            $.ajax({
                type: "POST",
                url:  rt_usuarios,
                data:{gv_action:'direcciones'},
                cache:false,
            }).done(function(resp){
                let r=JSON.parse(resp);
                if(r[0]==0){$.notify(r[1], "warn");}
                if(r[0]==1){$("body").html(r[1]);}
            }).fail(function(jqXHR, textStatus){
                validate.error_ajax(jqXHR, textStatus);
            });
        }
    });
    $("#m_datos_personales").on({
        "click":function(){
            $.ajax({
                type: "POST",
                url: rt_usuarios,
                data:{gv_action:'datos_personales'},
                cache:false,
            }).done(function(resp){
                resp=JSON.parse(resp);
                $("body").html(resp[1]);
                $("#txtIdentidad").val(resp[0][0]["Identidad"]);
                $("#txtNombre1").val(resp[0][0]["Nombre1"]);
                $("#txtNombre2").val(resp[0][0]["Nombre2"]);
                $("#txtApellido1").val(resp[0][0]["Apellido1"]);
                $("#txtApellido2").val(resp[0][0]["Apellido2"]);
                $("#txtCorreo").val(resp[0][0]["Correo"]);
                $("#txtEscalafon").val(resp[0][0]["Escalafon"]);
                //$("#txtFoto").val(resp[0][0]["Foto"]);
                $("#txtImprema").val(resp[0][0]["Imprema"]);
                $("#txtTelefono").val(resp[0][0]["Telefono"]);
                $("#txtclave").val(resp[0][0]["clave"]);
                $("#txtfecha_nacimeito").val(resp[0][0]["fecha_nacimeito"]);
                $("#txtsexo").val(resp[0][0]["sexo"]);
                $("#txttitulo").val(resp[0][0]["titulo"]);
                $("#txtusuario").val(resp[0][0]["usuario"]);
                $("#txtTelefono").val(resp[0][0]["Telefono"]);
                $("#txtTitulo").val(resp[0][0]["titulo"]);
            }).fail(function(jqXHR, textStatus){
                validate.error_ajax(jqXHR, textStatus);
            });
        }
    });
    $("#m_crear_procesos").on({
        "click":function(){
            $.ajax({
                type: "POST",
                url:  rt_usuarios,
                data:{gv_action:'crear_procesos'},
                cache:false,
            }).done(function(resp){
                let r=(JSON.parse(resp));
                if(r[0]==0){$.notify(r[1], "warn");}
                if(r[0]==1){$("body").html(r[1]);}
            }).fail(function(jqXHR, textStatus){
                validate.error_ajax(jqXHR, textStatus);
            })
        }
    });
})