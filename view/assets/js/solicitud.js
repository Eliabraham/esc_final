$(document).ready(function(){
    lista_posibilidades();
    lista_estados();
    listar();
    centros_solicitudes();
    identidad();
    $("#btn_menu").on({
        'click':function(){mostrar_menu();}
    });
    $("#btn_add_operacion").on({
        "click":function(){
            $("#mostrar_campos").html("");
            $("#exampleModal").modal("show");
        }
    });
    $("#txtnombre_proceso").on({
        "change":function(){
            $.ajax({
                type: "POST",
                url: rt_operaciones,
                data: {gv_action:'seleccionar_operacion', id:this.value},
                cache: false,
            }).done(function(resp){
                resp=JSON.parse(resp);
                let ht=``;
                $.each(resp, function(indice, elemento) {
                    if(indice==0){
                        ht=`${ht}<div>${elemento[0].descripcion_proceso}</div>
                        <div><a href="${elemento[0].link_plantilla}" target="_blank">Plantilla</a></div>`;
                    }
                    if(indice==1){
                        $.each(elemento, function(indice2, elemento2) {
                            if(elemento2['tipo']=="texto"){
                                ht=`${ht}<div class="form-group">
                                ${elemento2["campo"]}
                                <input type='text' name='${elemento2["campo"]}' id='txt${elemento2["campo"]}' class="form-control form-control-sm detalle required"/>
                                </div>`;}
                            if(elemento2['tipo']=="archivo"){
                                ht=`${ht}<div class="form-group">
                                ${elemento2["campo"]}
                                <input type='file' name='${elemento2["campo"]}' id='arc${elemento2["campo"]}' class="form-control form-control-sm detalle required"/>
                                </div>`;} 
                            if(elemento2['tipo']=="lista"){
                                opciones=elemento2['valores'].split(",");
                                ht=`${ht}<div class="form-group">
                                ${elemento2["campo"]}
                                <select name='${elemento2["campo"]}' id='txt${elemento2["campo"]}' class="form-control form-control-sm detalle required">
                                <option value=''>--</option>`;
                                $.each(opciones, function(ind, opt){
                                    ht=`${ht}<option>${opt}</option>`;    
                                });
                                ht=`${ht}</select>
                                </div>`;
                            }
                        })
                    }
                });
                ht=`${ht}<button class="btn btn-sm btn-primary" id="btnenviar" onclick="enviar_solicitud()" style="width:100px">Enviar</button>`;
                $("#mostrar_campos").html(ht);
            }).fail(function(jqXHR, textStatus) {
                validate.error_ajax(jqXHR, textStatus);
            });
        }
    });
    $(".filtro").on({
        "change":function(){filtrar();},
        "keyup":function(){filtrar();}
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
    $("#btn_reporte").on({
        "click":function(){
            envio();
        }
    });
});
function lista_posibilidades(){
    $.ajax({
        type: "POST",
        url: rt_operaciones,
        data: {gv_action:'lista_nombre_operaciones'},
        cache: false,
    }).done(function(resp) {
        resp=JSON.parse(resp);
        opt="<option value=''>--</option>";
        $.each(resp, function(indice, elemento) {
            opt=`${opt}<option value="${elemento.id}">${elemento.nombre_proceso}</option>`;
        });
        $("#txtnombre_proceso,#filtipo").html(opt);
    }).fail(function(jqXHR, textStatus) {
        validate.error_ajax(jqXHR, textStatus);
    });
}
function lista_estados(){
    $.ajax({
        type: "POST",
        url: rt_operaciones,
        data: {gv_action:'lista_estados'},
        cache: false,
    }).done(function(resp) {
        resp=JSON.parse(resp);
        opt="<option value=''>--</option>";
        $.each(resp, function(indice, elemento) {
            opt=`${opt}<option>${elemento.status}</option>`;
        });
        $("#filestado").html(opt);
    }).fail(function(jqXHR, textStatus) {
        validate.error_ajax(jqXHR, textStatus);
    });
}
function envio(){
    $("#formulario_reporte").submit();
}
function enviar_solicitud(){
    solicitud=get_data("proceso");
    detalles=get_data("detalle");
    request_missing_data(solicitud['err']);
    request_missing_data(detalles['err']);
    let inf = [];
    sol=solicitud.written;
    sol.append("gv_action","ingresar_solicitud");
    det=detalles.written;
    det.append("gv_action","ingresar_cuerpo_solicitud");
    $.ajax({
        type: "POST",
        url: rt_operaciones,
        processData: false,
        contentType: false,
        data:sol,
        cache: false,
        async:false,
    }).done(function(resp) {
        if (resp.includes("PROBLEMA")){
            $.notify(resp,"warn");
        } else {
            det.append("id_solicitud",resp);
            $.ajax({
                type: "POST",
                url: rt_operaciones,
                processData: false,
                contentType: false,
                data:det,
                async:false,
                cache: false,
            }).done(function(mens) {
                $.notify(mens,"info");
                listar();
            }).fail(function(jqXHR, textStatus) {
                validate.error_ajax(jqXHR, textStatus);
            });
        }
    }).fail(function(jqXHR, textStatus) {
        validate.error_ajax(jqXHR, textStatus);
    });
}
function listar(){
    $.ajax({
        type: "POST",
        url: rt_operaciones,
        data:{gv_action:'listar_solicitudes'},
        async:false,
        cache: false,
    }).done(function(resp){
        //console.log(resp);
        resp=JSON.parse(resp);
        mostrar_lista(resp);
    }).fail(function(jqXHR, textStatus) {
        validate.error_ajax(jqXHR, textStatus);
    });
}
function mostrar_lista(resp){
    ht="";
    $.each(resp, function(indice, elemento){
        ht += `<div class="card col-12">
            <div class="card-header">
                <input class="check" type="checkbox" value="${elemento.id}" name="lista_reporte[]" form="formulario_reporte"/>        
                Solicitud: <strong>ID:</strong>${elemento.id} <strong>Tipo:</strong> ${elemento.nombre_proceso} <strong>Estado:</strong> ${elemento.status} ${elemento.fecha_solicitud}`;
                if(elemento.status=='recepcion'){
                    ht +=`<button class="btn btn-primary btn-sm" onclick="editar_operacion(${elemento.id})" style="position:relative; left:40%">
                    <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" onclick="eliminar_operacion(${elemento.id})" class="btn btn-danger btn-sm" style="position:relative; left:40%">
                        <i class="fas fa-trash"></i>
                    </button>`;
                }
            ht +=`</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-6">
                        <p class="card-text"><strong>Causa:</strong> ${elemento.causa}</p>
                        <p class="card-text"><strong>Solicitante:</strong> ${elemento.identidad} <br/> ${elemento.nombre_completo} <br/>${elemento.titulo}<br/> ${elemento.Correo} <br/> ${elemento.Telefono}</p>`;
                        if(elemento.status!='recepcion'){
                            ht +=`<p class="card-text">${elemento.resolucion}</p>`;
                        }
                    ht +=`</div>
                    <div class="col-6 d-flex"> <!-- Agregamos la clase "d-flex" aquí -->
                        <table class="table">`;
                        if(elemento.status=='recepcion'){
                            ht +=`<tr>
                                <td>
                                    <textarea class="form-control form-control-sm"  style="max-height: 60px; resize: none; overflow-y: auto;" id="obser${elemento.id}"></textarea>
                                </td>
                                <td>
                                    <button class="btn btn-primary" style="height: 50px; width:100%" onclick="guardar_observacion(${elemento.id})">
                                        <i class="far fa-envelope"></i>
                                    </button>
                                </td>
                            </tr>`;
                        }
                        ht +=`<tr><td colspan="2">
                                    <div id="div${elemento.id}" name="${elemento.id}" style="max-height: 120px; overflow:auto; display:block;" class="lista_observaciones">
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>`;
    });
    $("#tarjetas").html(ht);
    buscar_observaciones();
}
function filtrar(){
    if($("#filcentro").length>0){centro=$("#filcentro").val();}else{centro="";}
    $.ajax({
        type: "POST",
        url: rt_operaciones,
        data: {
            gv_action:'filtrar_solicitudes',
            Numero:$("#filnumero").val(),
            tipo:$("#filtipo").val(),
            estado:$("#filestado").val(),
            centro:centro,
        },
        cache: false,
    }).done(function(resp) {
        resp=JSON.parse(resp);
        mostrar_lista(resp);
    }).fail(function(jqXHR, textStatus) {
        validate.error_ajax(jqXHR, textStatus);
    });
}
function eliminar_operacion(a){
    if (confirm("DESEA ELIMINIAR ESTA SOLICITUD DEL REGISTRO")){
        $.ajax({
            type: "POST",
            url: rt_operaciones,
            data:{gv_action:'eliminar_solicitud', id:a},
            async:false,
            cache: false,
        }).done(function(mens) {
            $.notify(mens,"info");
            listar();
        }).fail(function(jqXHR, textStatus) {
            validate.error_ajax(jqXHR, textStatus);
        });
    }
}
function editar_operacion(a){
    $.ajax({
        type: "POST",
        url: rt_operaciones,
        data:{gv_action:'editar_solicitud', id:a},
        async:false,
        cache: false,
    }).done(function(mens) {
        mens=JSON.parse(mens);
        $("#exampleModal").modal("show");
        ht=`<div>${mens[0][0]['descripcion_proceso']}</div>
        <div><a href="${mens[0][0]['link_plantilla']}">Plantilla</a></div>`;
        $.each(mens[1], function(indice, elemento) {
            if(elemento['tipo']=="texto"){
                valor="no asignado";
                for(i=0;i<mens[2].length;i++){
                    if(elemento["campo"].replace(" ", "_")==mens[2][i]['campo']){
                        valor=mens[2][i]['valor'];
                    }
                }
                ht=`${ht}<div class="form-group">
                ${elemento["campo"]}
                <input type='text' value="${valor}" name='${elemento["id"]}' id='txt${elemento["campo"]}' class="form-control form-control-sm detalle"/>
                </div>`;}
            if(elemento['tipo']=="archivo"){
                for(i=0;i<mens[2].length;i++){
                    if(elemento["campo"].replace(" ", "_")==mens[2][i]['campo']){
                        valor=mens[2][i]['valor'];
                    }
                }
                ht=`${ht}<div class="form-group">
                ${elemento["campo"]}
                <input type='file' name='${elemento["id"]}' id='arc${elemento["campo"]}' class="form-control form-control-sm detalle "/>
                <a href="${valor}" target="_blank">${valor}</a>
                </div>`;} 
            if(elemento['tipo']=="lista"){
                opciones=elemento['valores'].split(",");
                valor="no asignado";
                for(i=0;i<mens[2].length;i++){
                    if(elemento["campo"].replace(" ", "_")==mens[2][i]['campo']){
                        valor=mens[2][i]['valor'];
                    }
                }
                ht=`${ht}<div class="form-group">
                ${elemento["campo"]}
                <select name='${elemento["id"]}' id='txt${elemento["campo"]}' class="form-control form-control-sm detalle">
                <option value>${valor}</option>`;
                $.each(opciones, function(ind, opt){
                    if(opt!=valor){ht=`${ht}<option>${opt}</option>`;}
                });
                ht=`${ht}</select>
                </div>`;
            }
        });
        ht=`${ht}<button class="btn btn-sm btn-primary" id="btnenviar" onclick="actualizar_solicitud(${mens[3]})" style="width:100px">Actualizar</button><button class="btn btn-sm btn-secondary" id="btnenviar" onclick="cancelar_actualizacion()" style="width:100px">Cancelar</button>`;
        $("#txtcausa").val(mens[0][0].causa);
        $("#txtnombre_proceso").val(mens[0][0].id_tipo_solicitud).prop("readonly");
        $("#mostrar_campos").html(ht);
    }).fail(function(jqXHR, textStatus) {
        validate.error_ajax(jqXHR, textStatus);
    });
}
function cancelar_actualizacion(){
    $(".modal").modal("hide");
}
function actualizar_solicitud(a){
    detalles=get_data("detalle");
    det=detalles.written;
    det.append("gv_action","modificar_cuerpo_solicitud");
    det.append("solicitud",a);
    $.ajax({
        type: "POST",
        url: rt_operaciones,
        data:{
            gv_action:"modificar_solicitud",
            causa:$("#txtcausa").val(),
            id:a
        },
        cache: false,
        async:false,
    }).done(function(resp) {
        $.ajax({
            type: "POST",
            url: rt_operaciones,
            processData: false,
            contentType: false,
            data:det,
            async:false,
            cache: false,
        }).done(function(mens) {
            listar();
            $("#mostrar_campos").html("");
            $("#exampleModal").modal("hide");
        }).fail(function(jqXHR, textStatus) {
            validate.error_ajax(jqXHR, textStatus);
        });
    }).fail(function(jqXHR, textStatus) {
        validate.error_ajax(jqXHR, textStatus);
    });
}
function guardar_observacion(a){
    $.ajax({
        type: "POST",
        url: rt_operaciones,
        data:{
            gv_action:'insertar_observacion',
            solicitud:a,
            observacion:$(`#obser${a}`).val()
        },
        cache: false,
    }).done(function(mens) {
        buscar_observaciones();
        $(`#obser${a}`).val("");
    }).fail(function(jqXHR, textStatus) {
        validate.error_ajax(jqXHR, textStatus);
    });
}
function buscar_observaciones(){
    let lista = $(".lista_observaciones");
    $(".lista_observaciones").html("");
    let nombresModulos = lista.map(function() {
        return $(this).attr("name");
    }).get().join(",");
    $.ajax({
        type: "POST",
        url: rt_operaciones,
        data:{gv_action:'buscar_observaciones',lista:nombresModulos},
        cache: false,
    }).done(function(mens){
        mens=JSON.parse(mens);
        $.each(mens, function(indice, elemento){
            let ht="";
            $.each(elemento, function(indiceii, elementoii){
                ht=`${ht}<div class="card">
                    <div class="card-body">
                        <div>
                            ${elementoii.Observacion}</br>
                        </div>
                        <div><sub>${elementoii.fecha} ${elementoii.nombre_completo} ${elementoii.telefono} ${elementoii.Correo} </sub></div>
                    </div>
                </div>`;
                $(`#div${elementoii.id_solicitud}`).append(ht);
            });
        });
    }).fail(function(jqXHR, textStatus) {
        validate.error_ajax(jqXHR, textStatus);
    });
}
function centros_solicitudes(){
    if($("#filcentro").length>0){
        $.ajax({
            type: "POST",
            url: rt_usuarios,
            data:{gv_action:'centros_solicitudes_dm'},
            cache: false,
        }).done(function(mens){
            mens=JSON.parse(mens);
            //console.log(mens);
            ht="<option value=''>--</option>";
            for(i=0;i<mens.length;i++){
                console.log(mens[i]);
                ht+="<option value='"+mens[i].id_centro+"'> "+mens[i].Codigo_centro+" "+mens[i].Nombre+"</option>";
            }
            $("#filcentro,#txtsol_centro").html(ht);
        }).fail(function(jqXHR, textStatus) {
            validate.error_ajax(jqXHR, textStatus);
        });
        
    }
}