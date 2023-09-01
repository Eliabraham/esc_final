$(document).ready(function(){
    desactivarautocompletado();
    listar();
    mostrar_centros();
    identidad();
    $(".mayini").on({
        "keyup":function(){
            this.value=mayini(this.value);
        }
    });
    $("#btn_menu").on({
        'click':function(){
            mostrar_menu();
        }
    });
    $("#btn_add_docente").on({
        'click':function(){
            $("#btn_modificar").hide();
            $("#btn_guardar").show();
            $("#datadoc").val("");
            $("#exampleModal").modal("toggle");
            $("#txtIdentidad").focus();
            let elemento = document.getElementById("arcFoto");
            elemento.className = "datadoc required";
        }
    });
    $("#btn_reporte").on({
        "click":function(){
            envio();
        }
    });
    $("#txtIdentidad").on({
        "keyup":function(){this.value=formato_dni(this.value)},
        "blur":function(){
            if(this.value.trim()!=""){
                $.ajax({
                    type: "POST",
                    url:  rt_docentes,
                    data: {gv_action:'verificar_existencia', identidad:this.value.trim()},
                    cache:false,
                }).done(function(resp){
                    resp=JSON.parse(resp);                    
                    if(resp[0]==1){
                    }
                }).fail(function(jqXHR, textStatus){
                    validate.error_ajax(jqXHR, textStatus)
                });
            }
        }
    });    
    $("#btn_cancelar").on({
        'click':function(){
            $("#datadoc").val("");
            $("#exampleModal").modal("toggle");
        }
    });
    $("#txtPnombre, #txtSnombre, #txtPapellido, #txtSapellido").on({
        "keypress":function(){return solo_letra(event);}
    });
    $("#btn_guardar").on({
        "click":function(){
            datos = get_data('datadoc',this);
            data=datos.written;
            request_missing_data(datos['err']);
            $.ajax({
                type: "POST",
                url:  rt_docentes,
                processData: false,
                contentType: false,
                data: data,
                cache:false,
            }).done(function(resp){
                console.log(resp);
                $.notify(resp, "info");
                listar();
                $(".datadoc").val("");
                $("#vista_foto").html("");
                $("#txtIdentidad").focus();
            }).fail(function(jqXHR, textStatus){
                validate.error_ajax(jqXHR, textStatus)
            });
        }
    });
    $('#txtFechaNacimiento').on({
        'change': function(){
            var fechaNacimiento = $(this).val();
            var edad = calcularEdad(fechaNacimiento);
            $('#txtEdad').val(edad);
        }
    });
    $("#txtTelefono").on({
        "keypress":function(){
            return solo_numero(event);
        }
    });
    $("#txtCorreo").on({
        "blur":function(){
            $.ajax({
                type: "POST",
                url:  rt_docentes,
                data: {gv_action:'verificar_existencia_email', email:this.value.trim()},
                cache:false,
            }).done(function(resp){
                if(resp>0){
                    $.notify("este correo ya fue registrado con un docente anterior", "info");
                    $("#txtCorreo").val("");
                    $("#txtCorreo").focus();
                }
            }).fail(function(jqXHR, textStatus){
                validate.error_ajax(jqXHR, textStatus);
            });
            return for_ema(this);
        }
    });
    $("#arcFoto").on({
        'change':function(){
            agregarImagen(this, 'vista_foto');
        }
    });
    $("#ch_sel_todo").on({
        "change":function(){
            let checkboxes = $(":checkbox[name='lista_reporte[]']");
            if($(this).prop("checked")==false){
                checkboxes.each(function() {
                    $(this).prop("checked", false);
                })
            }else{
                checkboxes.each(function() {
                    $(this).prop("checked", true);
                })
            }
        }
    }); 
    $(".filtro").on({
        "keyup":function(){filtrar();},
        "change":function(){filtrar();}
    })
    $("#btn_modificar").on({
        'click':function(){
            datos = get_data('datadoc',this);
            data  = datos.written;
            data.append("id",this.name);
            request_missing_data(datos['err']);
            $.ajax({
                type: "POST",
                url:  rt_docentes,
                processData: false,
                contentType: false,
                data: data,
                cache:false,
            }).done(function(resp){
                $.notify(resp, "info");
                filtrar();
                $("#exampleModal").modal("toggle");
            }).fail(function(jqXHR, textStatus){
                validate.error_ajax(jqXHR, textStatus)
            });
        }
    });
    $("#btnGuardarAsignacion").on({
        "click":function(){
            let falta = '';
            let fd = new FormData();
            fd.append('gv_action', 'asignar_centro');
            fd.append('docente',this.name);
            if ($("#txtCentro").val().trim() != ""){fd.append('centro', $("#txtCentro").val());}
            else{falta += '\nDebe ingresar un valor en el campo "centro"';}
            if ($("#txtEstatus").val().trim() != ""){fd.append('estatus', $("#txtEstatus").val());}
            else {falta += '\nDebe ingresar un valor en el campo "estatus"';}
            if ($("#txtPuesto").val().trim() != ""){fd.append('puesto', $("#txtPuesto").val());}
            else {falta += '\nDebe ingresar un valor en el campo "puesto"';}
            if ($("#txtCondicion").val().trim() != ""){fd.append('condicion', $("#txtCondicion").val());}
            else {falta += '\nDebe ingresar un valor en el campo "condicion"';}
            if ($("#txtHorasClase").val().trim() != ""){fd.append('horas', $("#txtHorasClase").val());}
            else{falta += '\nDebe ingresar un valor en el campo "horas"';}
            if ($("#txtNombramiento").val().trim() != ""){fd.append('fechaNombramiento', $("#txtNombramiento").val());}
            else{ falta += '\nDebe ingresar un valor en el campo "fecha de nombramiento"';}
            if ($("#txtFechaVencimiento").val().trim() != ""){fd.append('fechaVencimiento', $("#txtFechaVencimiento").val());}
            else{falta += '\nDebe ingresar un valor en el campo "fecha de vencimiento"';}
            if (falta !== '') {
              $.notify(falta, "warn");
              throw new Error("¡faltan datos!");
            }else{
                $.ajax({
                    url: rt_docentes,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: fd,
                    cache: false
                }).done(function(resp) {
                    $.notify(resp, "info");
                    mostrar_asignaciones($("#btnGuardarAsignacion").attr("name"));
                    $("#txtCentro, #txtEstatus, #txtPuesto, #txtCondicion, #txtHorasClase, #txtNombramiento, #txtFechaVencimiento").val("");
                }).fail(function(jqXHR, textStatus) {
                    validate.error_ajax(jqXHR, textStatus);
                });
            }
        }
    })
    $("#txtCentro").on({
        "blur":function(){
            if (this.value.trim()!=""){
                var formData = new FormData();
                formData.append('gv_action', 'validar_asignacion');
                formData.append('id',this.value);
                $.ajax({
                    type: "POST",
                    url:  rt_centros,
                    processData: false,
                    contentType: false,
                    data: formData,
                    cache:false,
                }).done(function(resp){
                    if(resp=="[]"){
                        $.notify("ESTE CENTRO NO SE ENCUENTRA EN LA BASE DE DATOS, \n SELECCIONE UN CENTRO DE LA LA LISTA DESPLEGABLE", "WARN");
                        $("#txtCentro").val("").focus();
                        throw new Error('Ejecución detenida por centro no valido');
                    }else{
                        resp=JSON.parse(resp);
                        if (resp[0]["estatus"]!="Activo"){
                            $.notify("ESTE CENTRO NO SE ENCUENTRA ACTIVO \n POR ENDE NO PUEDE SER ASIGNADO DOCENTE", "WARN");
                            $("#txtCentro").val("").focus();
                            throw new Error('Ejecución detenida por centro inactivo');
                        }
                    }
                }).fail(function(jqXHR, textStatus){
                    validate.error_ajax(jqXHR, textStatus)
                });
            }
        }
    });
    $("#btnLimpiar").on({
        "click":function(){
            $("#txtCentro, #txtEstatus, #txtPuesto, #txtCondicion, #txtHorasClase, #txtNombramiento, #txtFechaVencimiento").val("");
        }
    })
    $("#btncancelarmodasignacion").on({
        "click":function(){
            $("#txtCentro, #txtEstatus, #txtPuesto, #txtCondicion, #txtHorasClase, #txtNombramiento, #txtFechaVencimiento").val("");
            $("#btnActualizarAsignacion, #btncancelarmodasignacion").hide();
            $("#btnGuardarAsignacion, #btnLimpiar").show();
        }
    });
    $("#btnActualizarAsignacion").on({
        "click":function(){
            let falta = '';
            let fd = new FormData();
            fd.append('gv_action', 'modificar_asignacion');
            fd.append('id',this.name);
            if ($("#txtCentro").val().trim() != ""){fd.append('centro', $("#txtCentro").val());}
            else{falta += '\nDebe ingresar un valor en el campo "centro"';}
            if ($("#txtEstatus").val().trim() != ""){fd.append('estatus', $("#txtEstatus").val());}
            else {falta += '\nDebe ingresar un valor en el campo "estatus"';}
            if ($("#txtPuesto").val().trim() != ""){fd.append('puesto', $("#txtPuesto").val());}
            else {falta += '\nDebe ingresar un valor en el campo "puesto"';}
            if ($("#txtCondicion").val().trim() != ""){fd.append('condicion', $("#txtCondicion").val());}
            else {falta += '\nDebe ingresar un valor en el campo "condicion"';}
            if ($("#txtHorasClase").val().trim() != ""){fd.append('horas', $("#txtHorasClase").val());}
            else{falta += '\nDebe ingresar un valor en el campo "horas"';}
            if ($("#txtNombramiento").val().trim() != ""){fd.append('fechaNombramiento', $("#txtNombramiento").val());}
            else{ falta += '\nDebe ingresar un valor en el campo "fecha de nombramiento"';}
            if ($("#txtFechaVencimiento").val().trim() != ""){fd.append('fechaVencimiento', $("#txtFechaVencimiento").val());}
            else{falta += '\nDebe ingresar un valor en el campo "fecha de vencimiento"';}
            if (falta !== '') {
              $.notify(falta, "warn");
              throw new Error("¡faltan datos!");
            }else{
                $.ajax({
                    url: rt_docentes,
                    type: 'POST',
                    processData: false,
                    contentType: false,
                    data: fd,
                    cache: false
                }).done(function(resp) {
                    $.notify(resp, "info");
                    mostrar_asignaciones($("#btnGuardarAsignacion").attr("name"));
                    $("#txtCentro, #txtEstatus, #txtPuesto, #txtCondicion, #txtHorasClase, #txtNombramiento, #txtFechaVencimiento").val("");
                }).fail(function(jqXHR, textStatus) {
                    validate.error_ajax(jqXHR, textStatus);
                });
            }
        }
    });
    $("#acptar_cambio_clave").on({
        "click":function(){
            falta="";
            usuario=$("#txtusuario").val().trim();
            clave=$("#txtclave").val().trim();
            if($("#txtusuario").val().trim()==""){falta+='Debe ingresar un Valor de Usuario';}
            if($("#txtusuarioconfirmado").val().trim()==""){falta+='\nDebe ingresar la Confirmación del Usuario';}
            if($("#txtclave").val().trim()==""){falta+='\nDebe Ingresar un Valor Para La Clave';}
            if($("#txtclaveconfirmada").val().trim()==""){falta+='\nDebe ingresar la confirmación de La Clave';}
            if(falta=="" && ($("#txtusuario").val().trim()==$("#txtusuarioconfirmado").val().trim()) && ($("#txtclave").val().trim()==$("#txtclaveconfirmada").val().trim())){
                $.ajax({
                    type: "POST",
                    url:  rt_docentes,
                    data: {
                        gv_action:'Cambiar_Clave',
                        usuario:usuario,
                        clave:clave,
                        docente:this.name
                    },
                    cache:false,
                }).done(function(resp){
                    $.notify(resp,"info");
                }).fail(function(jqXHR, textStatus){
                    validate.error_ajax(jqXHR, textStatus)
                });
            }else{
                if(falta!=""){$.notify(falta, "info");}
                if($("#txtclave").val().trim()!=$("#txtclaveconfirmada").val().trim()){
                    $.notify("LAS CLAVES NO COINCIDEN", "info");
                }
                if($("#txtusuario").val().trim()!=$("#txtusuarioconfirmado").val().trim()){
                    $.notify("LOS USUARIOS NO COINCIDEN", "info");
                }
            }
        }
    });
});
function calcularEdad(fechaNacimiento)
{
    let hoy = new Date();
    let fechaNac = new Date(fechaNacimiento);
    let edad = hoy.getFullYear() - fechaNac.getFullYear();
    let mes = hoy.getMonth() - fechaNac.getMonth();
    if (mes < 0 || (mes === 0 && hoy.getDate() < fechaNac.getDate())) {
      edad--;
    } 
    return edad;
}
function listar()
{
    $.ajax({
        type: "POST",
        url:  rt_docentes,
        data: {gv_action:'listar_docentes'},
        cache:false,
    }).done(function(resp){
       acciones_especiales(resp);
       mostrar_tabla(resp);
    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus)
    });
}
function acciones_especiales(resp) {
    let docentes = JSON.parse(resp);
    for (let i = 0; i < docentes.length; i++) {
        if (docentes[i]["rango_propio"] === "SysAdmin") {
            let sc = `<script>
                function asignar_direccion(a){
                    $.ajax({
                        type: "POST",
                        url : rt_docentes,
                        data:{gv_action :'filtrar_direcciones'},
                        cache:false,
                    }).done(function(resp){
                        let opciones="<option value=''>LISTA DE DIRECCIONES</option>";
                        resp=JSON.parse(resp);
                        for(ii=0;ii<resp.length;ii++){
                            opciones=opciones+"<option value='"+resp[ii]['id']+"'>"+resp[ii]['departamento']+"--"+resp[ii]['municipio']+"--"+resp[ii]['ubicacion']+"</option>";
                        }
                        $("#txtdireccion_asignada").html(opciones);
                        $("#txtfecha_asignacion,#txtfecha_culminacion,#txtdireccion_asignada").val("");
                        mostrar_direcciones_asignadas(a);
                    }).fail(function(jqXHR, textStatus){
                        validate.error_ajax(jqXHR, textStatus)
                    });
                    $("#acep_asig_dir").prop("name",a.id)
                    $("#divdirecciones").modal("toggle");
                }

                function mostrar_direcciones_asignadas(a){
                    $.ajax({
                        type: "POST",
                        url : rt_docentes,
                        data:{gv_action :'lista_direcciones_asignadas', id:a.id},
                        cache:false,
                    }).done(function(resp){
                        resp=JSON.parse(resp);
                        let ht = '<h6>LISTA DE ASIGNACIONES DE DIRECCION PREVIAS</h6><h6>ESTAS SON EDITABLES DESDE EL FORMULARIO DE DIRECCIONES MUNICIPALES</h6>';
                        for (var ii = 0; ii < resp.length; ii++) {
                            ht += '<div class="card">';
                            ht += '<div class="card-body">';
                            ht += '<p class="card-text">' + resp[ii]['departamento'] + ', ' + resp[ii]['municipio'] + '</p>';
                            ht += '<p class="card-text">Desde: ' + resp[ii]['fasignacion']  + ' Hasta: ' + resp[ii]['fvencimiento'] + '</p>';
                            ht += '</div>';
                            ht += '</div>';
                        }
                        $("#lis_dir_asi").html(ht);
                    }).fail(function(jqXHR, textStatus){
                        validate.error_ajax(jqXHR, textStatus)
                    });
                }

                function guardar_asignacion(a){
                    let direccion_asignada=$("#txtdireccion_asignada").val().trim().split('--');
                    let fecha_asignacion=$("#txtfecha_asignacion").val().trim();
                    let fecha_culminacion=$("#txtfecha_culminacion").val().trim();
                    if(direccion_asignada === "" || fecha_asignacion === "" || fecha_culminacion === ""){
                        $.notify("DEBE INGRESAR TODOS LOS CAMPOS", "info");
                        throw "ejecución detenida por datos vacios";
                    }
                    $.ajax({
                        type: "POST",
                        url:  rt_docentes,
                        data: {gv_action:'asignar_direccion', direccion_asignada:direccion_asignada[0], cargo:direccion_asignada[1], fecha_asignacion:fecha_asignacion, fecha_culminacion:fecha_culminacion, docente:a.name},
                        cache:false,
                    }).done(function(resp){
                        resp=JSON.parse(resp);
                        $.notify(resp[1], "info");
                        if(resp[0]==="1"){$("#divdirecciones").modal("toggle");}
                    }).fail(function(jqXHR, textStatus){
                        validate.error_ajax(jqXHR, textStatus)
                    });
                }
            </script>`;

            
            let divdir=`<div class="modal fade" id="divdirecciones" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <div class="text-center">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Asignación de Direcciones</h1>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="container">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="direccion_asignada">Direccion a Asignar</label>
                                            <select name="direccion_asignada" id="txtdireccion_asignada" class="form-control form-control-sm direccion">
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="fecha_asignacion">Fecha de Asignación</label>
                                            <input type="date" class="form-control form-control-sm direccion" name="Fecha de Asignación" id="txtfecha_asignacion"/>
                                        </div>
                                        <div class="form-group">
                                            <label for="fecha_entrega">Fecha de Culminación</label>
                                            <input type="date" class="form-control form-control-sm direccion" name="Fecha de Culminacion" id="txtfecha_culminacion"/>
                                        </div>
                                    </div>
                                    <button class="btn btn-sm btn-primary" id="acep_asig_dir" onclick="guardar_asignacion(this)">Aceptar</button>
                                </div>
                                <div id="lis_dir_asi"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>`;
            $("body").append(sc);
            $("body").append(divdir);
        }
    }
}
function mostrar_tabla(resp){
    let docentes = JSON.parse(resp);
    let tarjetas = '';
    for (let i = 0; i < docentes.length; i++) {
        tarjetas += `<div class="card container border border-primary p-3">
            <div class="d-flex justify-content-between">
                <input class="check" type="checkbox" value="${docentes[i]['id']}" name="lista_reporte[]" form="formulario_reporte"/>        
                <h5 class="card-title">${docentes[i]['Nombre1']} ${docentes[i]['Nombre2']} ${docentes[i]['Apellido1']} ${docentes[i]['Apellido2']}</h5>
                <div>`;
                    if(docentes[i]["rango_propio"].indexOf('SysAdmin') !== -1){
                        tarjetas += `<button type="button" class="btn btn-primary btn-sm" id="${docentes[i]['id']}" onclick="editar_clave_docente(this)">
                            <i class="fa fa-key"></i> 
                        </button>`;
                    }
                    tarjetas += `<button class="btn btn-primary btn-sm" id="${docentes[i]['id']}" onclick="editar_docente(this)"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-primary btn-sm" onclick="asignar_centro(this)" id="${docentes[i]['id']}">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
                            <path d="M7.293 1.146a.5.5 0 0 1 .414 0l6 2.5a.5.5 0 0 1 .293.455v9.8a.5.5 0 0 1-.5.5H9.5a.5.5 0 0 1-.5-.5V11H7v2.255a.5.5 0 0 1-.5.5H2.5a.5.5 0 0 1-.5-.5v-9.8a.5.5 0 0 1 .293-.455l6-2.5a.5.5 0 0 1 .414 0zm-5 3.22L1.07 5.125 8 7.78l6.93-2.655L9 4.366V1.5H7v2.866L1.293 4.126z"/>
                            <path d="M13.5 1h-3a.5.5 0 0 0-.5.5V4h1V2h2V1.5a.5.5 0 0 0-.5-.5z"/>
                        </svg>
                    </button>`;
                    /*if(docentes[i]["rango_propio"].indexOf('SysAdmin') !== -1){
                        tarjetas += ` <button class="btn btn-primary btn-sm" id="${docentes[i]['id']}" onclick="asignar_direccion(this)"><i class="fas fa-user-tie"></i>
                        </button>`;
                    }*/
                    if (!docentes[i]['rango'] || docentes[i]['rango'].indexOf('SysAdmin') === -1) {
                        tarjetas += `<button class="btn btn-danger btn-sm" id="${docentes[i]['id']}" onclick="eliminar_docente(this)"><i class="fas fa-trash-alt"></i></button>`;
                    }
                      
                tarjetas += `</div>
            </div>
            <div class="row">
                <div class="col">
                    <div class="image-container" style="height: 200px; overflow: auto;">
                        <img class="col-12" src="${docentes[i]['Foto']}"/>
                    </div>
                </div>
                <div class="col">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Identidad: ${docentes[i]['Identidad']}</li>
                        <li class="list-group-item">Imprema: ${docentes[i]['Imprema']}</li>
                        <li class="list-group-item">Escalafón: ${docentes[i]['Escalafon']}</li>
                    </ul>
                </div>
                <div class="col">
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Estatus: ${docentes[i]['Status']}</li>
                    <li class="list-group-item">Teléfono: ${docentes[i]['Telefono']}</li>
                    <li class="list-group-item">Correo: ${docentes[i]['Correo']}</li>
                </ul>
            </div>
        </div>
    </div>`;}
    $("#tarjetas").html(tarjetas);
}
function filtrar(){
    $.ajax({
        type: "POST",
        url : rt_docentes,
        data:{
            gv_action :'filtrar_docentes',identidad :$("#fil_Identidad").val(),
            nombre    :$("#fil_Nombre").val(),
            escalafon :$("#fil_Escalafon").val(),
            estatus   :$("#filStatus").val(),
            imprema   :$("#fil_Imprema").val()
        },
        cache:false,
    }).done(function(resp){
        console.log(resp);
        mostrar_tabla(resp);
    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus)
    });
}
function envio(){
    $("#formulario_reporte").submit();
}
function eliminar_docente(a){
    if (confirm("DESEA ELIMINAR ESTE REGISTRO")){
        $.ajax({
            type: "POST",
            url:  rt_docentes,
            data: {gv_action:'eliminar_docente', id:a.id},
            cache:false,
        }).done(function(resp){
            $.notify(resp,"info");
            filtrar();
        }).fail(function(jqXHR, textStatus){
            validate.error_ajax(jqXHR, textStatus)
        }); 
    }
}
function editar_docente(a){
    let elemento = document.getElementById("arcFoto");
    elemento.className = "datadoc";
    $.ajax({
        type: "POST",
        url:  rt_docentes,
        data: {gv_action:'editar_docente', id:a.id},
        cache:false,
    }).done(function(resp){
        resp=JSON.parse(resp);
        $("#vista_foto").html(`<div>
            <img class="form-control" src="${resp[0]['Foto']}"/>
        </div>`);
        $("#txtSexo").val(resp[0]['sexo']);
        $("#txtFechaNacimiento").val(resp[0]['fecha_nacimeito']);
        $("#txtTitulo").val(resp[0]['titulo']);
        $("#txtIdentidad").val(resp[0]['Identidad']);
        $("#txtPnombre").val(resp[0]['Nombre1']);
        $("#txtSnombre").val(resp[0]['Nombre2']);
        $("#txtPapellido").val(resp[0]['Apellido1']);
        $("#txtSapellido").val(resp[0]['Apellido2']);
        $("#txtEscalafon").val(resp[0]['Escalafon']);
        $("#txtImprema").val(resp[0]['Imprema']);
        $("#txtTelefono").val(resp[0]['Telefono']);
        $("#txtCorreo").val(resp[0]['Correo']);
        $("#txtStatus").val(resp[0]['Status']);
        $("#btn_modificar").prop("name",a.id);
        $("#btn_modificar").show();
        $("#btn_guardar").hide();
        var edad = calcularEdad($("#txtFechaNacimiento").val());
        $('#txtEdad').val(edad);
        $("#exampleModal").modal("toggle");
    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus)
    });
}
function asignar_centro(a){
    $.ajax({
        type: "POST",
        url:  rt_docentes,
        data: {gv_action:'editar_docente', id:a.id},
        cache:false,
    }).done(function(resp){
        resp=JSON.parse(resp);
        $("#asig_cent_identidad").text(`Documento de Identidad: ${resp[0]['Identidad']}`);
        $("#asig_cent_nombre").text(`Nombres: ${resp[0]['Nombre1']} ${resp[0]['Nombre2']}`);
        $("#asig_cent_apellido").text(`Apellidos: ${resp[0]['Apellido1']} ${resp[0]['Apellido2']}`);
        $("#asig_cent_escalafon").text(`Escalafon: ${resp[0]['Escalafon']}`); 
        $("#asig_cent_estatus").text(`Estatus: ${resp[0]['Status']}`);
        $("#asig_cent_telf").text(`Telefono: ${resp[0]['Telefono']}`);
        $("#asig_cent_email").text(`Email: ${resp[0]['Correo']}`);
        $("#asig_cent_foto").html(`
        <div>
            <img class="form-control" style="border:none" src="${resp[0]['Foto']}"/>
        </div>`);
        resp[0]['Status'] != 'activo' ? $("#btnGuardarAsignacion").css("display", "none") : $("#btnGuardarAsignacion").css("display", "inline-block");

    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus)
    });
    mostrar_asignaciones(a.id);
    $("#btnGuardarAsignacion").prop({"name":a.id});
    $("#divhistorial").modal("show");
    $("#btnGuardarAsignacion").show();
    $("#btnActualizarAsignacion, #btncancelarmodasignacion").hide();
}
function mostrar_asignaciones(a){
    $.ajax({
        type: "POST",
        url:  rt_docentes,
        data: {gv_action:'mostrar_asignaciones', docente:a},
        cache:false,
    }).done(function(resp){
        resp = JSON.parse(resp);
        let a="";
        let trg="";
        let opc=[];
        let fechaActual = new Date();
        for (i=0;i<resp.length;i++){
            console.log(resp);
            trg+=`<br><div class="card text-white bg-secondary">
                <div class="card-header text-center">
                    <input type="hidden" id="idcentro${i}" value="${resp[i]['id_centro']}"/>
                    <h5 class="card-title" style="float:left;">Centro: ${resp[i]['codcent']} - ${resp[i]['nomcent']} - ${resp[i]['tipocent']}- ${resp[i]['muncent']}</h5>
                    <button name="${resp[i]["id"]}"  onclick="eliminar_asignacion(this)" class="btn btn-danger btn-sm"><i class="fa fa-trash"></i></button>
                    <button name="${i}" id="${resp[i]["id"]}"  onclick="modificar_asignacion(this)" class="btn btn-primary btn-sm float-right"><i class="fas fa-edit"></i></button>
                    <button type="button" class="btn btn-primary btn-sm" id="${resp[i]["id_docente"]}" onclick="editar_clave_docente(this)">
                        <i class="fa fa-key"></i> 
                    </button>
                </div>
                <div class="card-body row">
                    <div class="col-md-4">
                        <p class="card-text"><b>Condicion:</b><br><label id="condicion${i}">${resp[i]['condicion']}</label></p>
                        <p class="card-text" id=""><b>Fecha de Asignación:</b><br><label id="fasignacion${i}"/>${resp[i]['fasignacion']}</label></p>
                    </div>
                    <div class="col-md-4">
                        <p class="card-text"><b>Estatus:</b><br><label id="estatus${i}">${resp[i]['estatus']}</label></p>
                        <p class="card-text" id=""><b>Fecha de Vencimiento:</b><br><label id="fvencimiento${i}"/>${resp[i]['fvencimiento']}</label></p>
                    </div>
                    <div class="col-md-4">
                        <p class="card-text"><b>Cargo:</b><br><label id="cargo${i}">${resp[i]['puesto']}</label></p>
                        <p class="card-text" id=""><b>Horas asignadas:</b><br><label id="horas${i}"/>${resp[i]['horas']}</label></p>
                    </div>
                    <div class="table-responsive">
                        <h5 class="card-title">Estructura Presupuestaria</h5>
                        <table class="table round table-hover table-sm table-striped table-bordered table-white" id="tab_presupuestaria${resp[i]["id"]}">
                            <thead class="text-white">
                                <tr>
                                    <td><label for="dependencia${i}">Dependencia</label></td>
                                    <td><label for="departamento${i}">Departamento</label></td>
                                    <td><label for="municipio${i}">Municipio</label></td>
                                    <td><label for="codigo_centro${i}">Codigo de Centro</label></td>
                                    <td><label for="codigo_plaza${i}">Código de Plaza</label></td>
                                    <td><label for="horas${i}">Horas</label></td>`;
                                    let fechaAsignacion = convertirFecha(resp[i]['fasignacion']);
                                    let fechaVencimiento = convertirFecha(resp[i]['fvencimiento']);
                                    if (fechaActual >= fechaAsignacion && fechaActual <= fechaVencimiento) {
                                        trg = trg+`<td rowspan="2"><button class="btn btn-primary btn-sm" onclick="agregar_estructura_presupuestaria(this)" name="${i}" id="${resp[i]["id"]}"/><i class="fa fa-plus"></i></button></td>`;
                                    }
                                    trg += `
                                </tr>
                                <tr>
                                    <td>
                                        <select id="dependencia${i}" class="form-control form-control-sm" id="Dependencia">
                                            <option value="">--</option>
                                            <option>Gubernamental</option>
                                            <option>ONG</option>
                                            <option>Otro</option>
                                        </select>
                                    </td>
                                    <td><input id="departamento${i}" type="search" class="form-control form-control-sm"/></td>
                                    <td><input id="municipio${i}" type="search" class="form-control form-control-sm"/></td>
                                    <td><input id="codigo_centro${i}" type="search" class="form-control form-control-sm"/></td>
                                    <td><input id="codigo_plaza${i}" type="search" class="form-control form-control-sm"/></td>
                                    <td><input id="horas_est_pre${i}" type="search" class="form-control form-control-sm"/></td>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>`;
           opc.push(resp[i]["id"]);
        }
        $("#div_asignaciones").html(trg);
        listar_estructuras_presupuestarias(opc);
    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus)
    });  
}
function convertirFecha(fecha) {
    let partes = fecha.split("-");
    let fechaObjeto = new Date(partes[2], partes[1] - 1, partes[0]);
    return fechaObjeto;
}
function listar_estructuras_presupuestarias(opc){
    for(i=0; i<opc.length;i++){
        $.ajax({
            type: "POST",
            url:  rt_docentes,
            async: false,
            data: {gv_action:'lista_estructura_presupuestaria', asignacion:opc[i]},
            cache:false,
        }).done(function(resp){
            resp=JSON.parse(resp);
            let bt="";
            for(let ii=0;ii<resp.length;ii++){
                bt=`${bt}<tr>
                    <td class="text-white">${resp[ii]["dependencia"]}</td>
                    <td class="text-white">${resp[ii]["departamento"]}</td>
                    <td class="text-white">${resp[ii]["municipio"]}</td>
                    <td class="text-white">${resp[ii]["cod_centro"]}</td>
                    <td class="text-white">${resp[ii]["cod_plaza"]}</td>
                    <td class="text-white">${resp[ii]["horas"]}</td>
                    <td><button id="${opc[i]}" name="${resp[ii]["id"]}" onclick="eliminar_est_pre(this)" class="btn btn-danger btn-sm" title="Eliminar"><i class="fas fa-trash fa-sm"></i></button></td>
                </tr>`;
            }
            $(`#tab_presupuestaria${opc[i]} tbody`).html(bt);
        }).fail(function(jqXHR, textStatus){
            validate.error_ajax(jqXHR, textStatus)
        }); 
    }
}
function mostrar_centros(){
    $.ajax({
        type: "POST",
        url:  rt_centros,
        data: {gv_action:'centros_activos'},
        cache:false,
    }).done(function(resp){
        //console.log(resp);
        resp = JSON.parse(resp);
        let a="";
        for (i=0;i<resp.length;i++){
            a=`${a}<option value="${resp[i]['id_centro']}">${resp[i]['Codigo_centro']} - ${resp[i]['Nombre']} - ${resp[i]['Tipo_centro']} - ${resp[i]['Municipio']}"</option>`;
        }
        $("#lcentros").html(a);
    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus)
    }); 
}
function eliminar_asignacion(a){
    if (confirm('¿DESEA ELIMINAR ESTA ASIGNACION?')){
        $.ajax({
            type: "POST",
            url:  rt_docentes,
            data: {gv_action:'eliminar_asignacion', id:a.name},
            cache:false,
        }).done(function(resp){
            $.notify(resp,"info");
            mostrar_asignaciones($("#btnGuardarAsignacion").attr('name'));
        }).fail(function(jqXHR, textStatus){
            validate.error_ajax(jqXHR, textStatus)
        }); 
    }
}
function modificar_asignacion(a){
    $("#txtCondicion").val($(`#condicion${a.name}`).text());
    $("#txtCentro").val($(`#idcentro${a.name}`).val());
    $("#txtPuesto").val($(`#cargo${a.name}`).text());
    $("#txtEstatus").val($(`#estatus${a.name}`).text());
    let fechaNombramiento = $(`#fasignacion${a.name}`).text();
    let fechaNombramientoParts = fechaNombramiento.split("-");
    let fechaNombramientoFormatted = fechaNombramientoParts[2] + "-" + fechaNombramientoParts[1] + "-" + fechaNombramientoParts[0];
    let fechaVencimiento = $(`#fvencimiento${a.name}`).text();
    let fechaVencimientoParts = fechaVencimiento.split("-");
    let fechaVencimientoFormatted = fechaVencimientoParts[2] + "-" + fechaVencimientoParts[1] + "-" + fechaVencimientoParts[0];
    $("#txtNombramiento").val(fechaNombramientoFormatted);
    $("#txtFechaVencimiento").val(fechaVencimientoFormatted);
    $("#txtHorasClase").val($(`#horas${a.name}`).text());
    $("#btnGuardarAsignacion, #btnLimpiar").hide();
    $("#btnActualizarAsignacion, #btncancelarmodasignacion").show();
    $("#btnActualizarAsignacion").prop({"name":a.id});
    $("#txtCentro").focus();
}
function agregar_estructura_presupuestaria(a){
    let fd = new FormData();
    fd.append('gv_action', 'insertar_estructura_presupuestaria');
    fd.append('designacion',a.id);
    let falta = "";
    if($(`#dependencia${a.name}`).val().trim()!=""){
        fd.append("dependencia",$(`#dependencia${a.name}`).val());
    }else{falta=`${falta}\n debe ingresar el campo dependencia`;}
    if($(`#departamento${a.name}`).val().trim()!=""){
        fd.append("departamento",$(`#departamento${a.name}`).val());
    }else{falta=`${falta}\n debe ingresar el campo: departamento`;}
    if($(`#municipio${a.name}`).val().trim()!=""){
        fd.append("municipio",$(`#municipio${a.name}`).val());
    }else{falta=`${falta}\n debe ingresar el campo: municipio`;}
    if($(`#codigo_centro${a.name}`).val().trim()!=""){
        fd.append("codigo_centro",$(`#codigo_centro${a.name}`).val());
    }else{falta=`${falta}\n debe ingresar el campo: codigo presupuestario de centro educativo`;}
    if($(`#codigo_plaza${a.name}`).val().trim()!=""){
        fd.append("codigo_plaza",$(`#codigo_plaza${a.name}`).val());
    }else{falta=`${falta}\n debe ingresar el campo: codigo plaza docente`;}
    if($(`#horas_est_pre${a.name}`).val().trim()!=""){
        fd.append("horas",$(`#horas_est_pre${a.name}`).val());
    }else{falta=`${falta}\n debe ingresar el campo: horas`;}
    if(falta!=""){
        $.notify(falta, "warn");
        throw new Error('Ejecución detenida por datos faltantes');
    }
    $.ajax({
        type: "POST",
        url:  rt_docentes,
        processData: false,
        contentType: false,
        data: fd,
        async: false,
        cache:false,
    }).done(function(resp){
        $.notify(resp, "info");
        $(`#dependencia${a.name}`).val("");
        $(`#departamento${a.name}`).val("");
        $(`#municipio${a.name}`).val("");
        $(`#codigo_centro${a.name}`).val("");
        $(`#codigo_plaza${a.name}`).val("");
        $(`#horas_est_pre${a.name}`).val("");
    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus);
    });
    listar_estructuras_presupuestarias([a.id]);
}
function eliminar_est_pre(a){
    if(confirm("DESEA ELIMINAR ESTA ESTRUCTURA PRESUPUESTARIA")){
        $.ajax({
            type: "POST",
            url:  rt_docentes,
            async: false,
            data: {gv_action:'eliminar_estructura_presupuestaria', estructura:a.name},
            cache:false,
        }).done(function(resp){
            $.notify(resp, "info");
        }).fail(function(jqXHR, textStatus){
            validate.error_ajax(jqXHR, textStatus)
        });
        listar_estructuras_presupuestarias([a.id]);     
    }
}
function editar_clave_docente(a){
    $("#camb_clave").modal("show");
    $("#acptar_cambio_clave").prop({"name":a.id});
}