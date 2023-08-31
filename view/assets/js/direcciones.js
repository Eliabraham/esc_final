$(document).ready(function(){
    desactivarautocompletado();
    listar_direcciones();
    identidad();
    $("#txtEmailDireccion").on({
        "blur":function(){
            for_ema(this);
        }
    });
    $("#btn_reporte").on({
        "click":function(){
            $("#formulario_reporte").submit();
        }
    });
    $(".mayini").on({
        "keyup":function(){
            this.value=mayini(this.value);
        }
    });
    $("#txtTelefonoDireccion").on({
        "keypress":function(){
            return solo_numero(event);
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
    $("#btn_menu").on({
        'click':function(){
            mostrar_menu();
        }
    });
    $("#btn_add_direccion").on({
        'click':function(){
            $("#btn_modificar").hide();
            $("#btn_guardar").show();
            $(".direccion").val("");
            $("#exampleModal").modal("toggle");
            $("#txtDepartamento").focus();
        }
    });
    $("#btn_cancelar").on({
        'click':function(){
            $(".direccion").val("");
            $("#exampleModal").modal("toggle");
        }
    });
    $("#btn_cancelar_director").on({
        'click':function(){
            $(".direccion").val("");
            $("#modaldirectores").modal("toggle");
        }
    });
    $("#btn_guardar").on({
        "click":function(){
            datos = get_data('direccion',this);
            data=datos.written;
            request_missing_data(datos['err']);
            $.ajax({
                type: "POST",
                url:  rt_direcciones,
                processData: false,
                contentType: false,
                data: data,
                cache:false,
            }).done(function(resp){
                $.notify(resp, "info");
                $(".direccion").val("");
                listar_direcciones();
                $.notify("AL FINALIZAR CIERRE ESTE FORMULARIO", "info");
                $("#txtDepartamento").focus();
            }).fail(function(jqXHR, textStatus){
                validate.error_ajax(jqXHR, textStatus)
            });
        }
    });
    $("#btn_modificar").on({
        "click":function(){
            datos = get_data('direccion',this);
            datos.written.append('direccion',this.name);
            data=datos.written;
            request_missing_data(datos['err']);
            $.ajax({
                type: "POST",
                url: rt_direcciones,
                processData: false,
                contentType: false,
                data: data,
                cache:false,
            }).done(function(resp){
                $.notify(resp, "info");
                $(".direccion").val("");
                listar_direcciones();
                $("#exampleModal").modal("toggle");
            }).fail(function(jqXHR, textStatus){
                validate.error_ajax(jqXHR, textStatus);
            });
        }
    })
    $(".filtro").on({
        "keyup":function(){filtrar_direcciones()},
        "change":function(){filtrar_direcciones()}
    });
    $("#txtdocIdentidad").on({
        "keyup":function(){this.value=formato_dni(this.value);},
        "keypress":function(){return solo_numero(event);}
    });
    $(".texto").on({"keypress":function(){return solo_letra(event);}});
    $(".numero").on({"keypress":function(){return solo_numero(event);}});
});
function listar_direcciones(){
    $.ajax({
        type: "POST",
        url:  rt_direcciones,
        data: {gv_action:'listar-direcciones'},
        cache:false,
        async:false,
    }).done(function(resp){
        resp=JSON.parse(resp);
        mostrar_direcciones(resp);
    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus)
    });
}
function editar_direccion(a){
    fd=new FormData();
    fd.append('gv_action','editar_direccion');
    fd.append('direccion',a.id);
    $.ajax({
        type: "POST",
        url:  rt_direcciones,
        processData: false,
        contentType: false,
        data: fd,
        cache:false,
    }).done(function(resp){
        resp=JSON.parse(resp);
        $("#txtDireccionGeografica").val(resp[0]['ubicacion']);
        $("#txtDepartamento").val(resp[0]['departamento']);
        $("#txtEmailDireccion").val(resp[0]['email']);
        $("#txtMunicipio").val(resp[0]['municipio']);
        $("#txtTelefonoDireccion").val(resp[0]['telefono']);
        $("#btn_modificar").attr({"name":a.id});
    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus)
    });
    $("#btn_modificar").show();
    $("#btn_guardar").hide();
    $(".direccion").val("");
    $("#exampleModal").modal("toggle");
    
}
function eliminar_direccion(a){
    if(confirm("¿DESEA ELIMINAR ESTA DIRECCION?")){
        fd=new FormData();
        fd.append('gv_action','eliminar_direccion');
        fd.append('direccion',a.id);
        $.ajax({
            type: "POST",
            url:  rt_direcciones,
            processData: false,
            contentType: false,
            data: fd,
            cache:false,
        }).done(function(resp){
            $.notify(resp, "info");
            listar_direcciones();
        }).fail(function(jqXHR, textStatus){
            validate.error_ajax(jqXHR, textStatus)
        });
    }
}
function mostrar_direcciones(a){
    let tarjeta="";
    for(i=0;i<a.length;i++){
        tarjeta=`${tarjeta}<div class="card col-12 ">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <input class="check" type="checkbox" value="${a[i]['id']}" name="lista_reporte[]" form="formulario_reporte"/>
                    <h5 class="card-title">
                        DIRECCION MUNICIPAL:
                    </h5>
                    <div>
                        <button class="btn btn-primary btn-sm" id="${a[i]['id']}" onclick="editar_direccion(this)"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-danger btn-sm" id="${a[i]['id']}" onclick="eliminar_direccion(this)"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-4">
                        <p>Ubicacion Geografica:
                            ${a[i]['departamento']}
                            ${a[i]['municipio']}
                        </p>
                        <p>${a[i]['ubicacion']}</p>
                        <p><a href="mailto:${a[i]['email']}">${a[i]['email']}</a></p>
                        <p><a href="tel:${a[i]['telefono']}">${a[i]['telefono']}</a></p>
                    </div>
                    <div class="col-8 table-responsive" style="height:170px; overflow:auto">
                        <table class="table round table-hover table-sm table-striped table-bordered" id="tbl${a[i]['id']}">
                            <thead>
                                <tr>
                                    <td>Nombre</td>
                                    <td>Asignacion</td>
                                    <td>Retiro</td>
                                    <td rowspan="2"><button name="${a[i]['id']}" class="btn btn-primary btn-sm" onclick="guardar_diretor(this)">+</button></td>
                                </tr>
                                <tr>
                                    <td>
                                        <input type="search" list="ldir${a[i]['id']}" class="form-control form-control-sm" id="dir${a[i]['id']}"/>
                                        <datalist id="ldir${a[i]['id']}" class="candicatos"></datalist>
                                    </td>
                                    <td><input type="date" class="form-control form-control-sm" id="ing${a[i]['id']}"/></td>
                                    <td><input type="date" class="form-control form-control-sm" id="ven${a[i]['id']}"/></td>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>`; 
    }
    $("#tarjetas").html(tarjeta);
    listar_docentes();
    listar_directores();
}
function filtrar_direcciones(){
    datos = get_data('filtro');
    datos.written.append('gv_action','filtrar_direccion');
    data=datos.written;
    request_missing_data(datos['err']);
    $.ajax({
        type: "POST",
        url:  rt_direcciones,
        processData: false,
        contentType: false,
        data: data,
        cache:false,
    }).done(function(resp){
        resp=JSON.parse(resp);
        mostrar_direcciones(resp);
    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus)
    });
}
function asignar_director(a){
    let direccion=a.id
    direccion=direccion.split('||');
    $("#tmunicipio").text(`Municipio: ${direccion[1]}`);
    $("#tdepartamento").text(`Departamento: ${direccion[0]}`);
    $("#btn_guardar_director").prop("name",direccion[2]);
    $("#MyModal").modal("toggle");
    
}
function listar_docentes(){
    $.ajax({
        type: "POST",
        url:  rt_direcciones,
        data: {gv_action:"listar_docentes"},
        cache:false,
        async:false,
    }).done(function(resp){
        resp=JSON.parse(resp);
        let lista="<option value=''>--</option>";
        for(i=0;i<resp.length;i++){
            lista+="<option value='"+resp[i]['id']+"||"+resp[i]['Correo']+"'>"+resp[i]['Nombre1']+" "+resp[i]['Nombre2']+" "+resp[i]['Apellido1']+" "+resp[i]['Apellido2']+"</option>"
        }
        $(".candicatos").html(lista);
    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus)
    });
}
function listar_directores(){
    setTimeout(() => {
        $.ajax({
            type: "POST",
            url:  rt_direcciones,
            data: {gv_action:"listar_directores"},
            cache:false,
            async:false,
        }).done(function(resp){
            resp=JSON.parse(resp);
            let claves = Object.keys(resp);
            let valores = Object.values(resp);
            for(i=0;i<claves.length;i++){
                $(`#tbl${claves[i]}>tbody`).remove();
                $(`#tbl${claves[i]}`).append(valores[i]);
            }
        }).fail(function(jqXHR, textStatus){
            validate.error_ajax(jqXHR, textStatus)
        });
    }, 100);
}
function guardar_diretor(a){
    direccion = $(a).prop("name");
    if(
        $(`#dir${direccion}`).val().trim()!="" ||
        $(`#ing${direccion}`).val().trim()!="" ||
        $(`#ven${direccion}`).val().trim()!=""
    ){
        $.ajax({
            type: "POST",
            url:  rt_direcciones,
            data:{
                gv_action:'crear_director', 
                direccion:direccion, 
                director:$(`#dir${direccion}`).val(), 
                fasignacion:$(`#ing${direccion}`).val(), 
                fvencimiento:$(`#ven${direccion}`).val()},
            cache:false,
            async:false,
        }).done(function(resp){
            $.notify(resp, "info");
            listar_directores();
            $(`#dir${direccion}`).val("");
            $(`#ing${direccion}`).val("");
            $(`#ven${direccion}`).val("");
        }).fail(function(jqXHR, textStatus){
            validate.error_ajax(jqXHR, textStatus)
        });
    }else{
        $.notify("falta un dato para realizar esta accion", "info");
    }
}
function eliminar_asignacion(a){
    if (confirm("desea eliminar esta asignación")){
        $.ajax({
            type: "POST",
            url:  rt_direcciones,
            data:{
                gv_action:'eliminar_asignacion', 
                asignacion:$(a).prop("id")
            },
            cache:false,
            async:false,
        }).done(function(resp){
        $.notify(resp, "info");
        listar_directores();
        }).fail(function(jqXHR, textStatus){
            validate.error_ajax(jqXHR, textStatus)
        });       
    }
}