$(document).ready(function(){
    listar();
    lista_solicitudes();
    iniciar_filtro_solicitudes();
    identidad();
    $("#btn_menu").on({
        'click':function(){
            mostrar_menu();
        }
    });
    $("#btn_add_operacion").on({
        "click":function(){
            $("#exampleModal").modal("show");
            $(".proceso, #campo, #tipo, #valores, #descripcion").val("");
            $("#modificar_operacion").hide();
            $("#guardar_operacion").show();
            $("#tbl_campos tbody").html("");
        }
    });
    $("#agregar_campo").on({
        "click":function(){
            camp=$("#campo").val().trim();
            tipo=$("#tipo").val().trim();
            valo=$("#valores").val().trim();
            desc=$("#descripcion").val().trim();
            if((camp=="") || (tipo=="")||(tipo=="lista" && valo=="")||(tipo=="tabla" && valo=="")){
                $.notify("el nombre del campo o el tipo esta vacio \n el tipo lista y el tipo tabla deben tener\nal menos un valor  especificado","Info");
            }else{
                let tr=`<tr>
                    <td>${camp}</td>
                    <td>${tipo}</td>
                    <td>${valo}</td>
                    <td>${desc}</td>
                    <td>
                        <button type="button" class="btn btn-danger btn-sm" onclick="retirar(this)">
                            <i class="fa fa-minus"></i>
                        </button>
                    </td>
                </tr>`;
                $("#tbl_campos>tbody").append(tr);
                $("#tipo").val("");
                $("#valores").val("");
                $("#descripcion").val("");
                $("#campo").val("").focus();
            }
        }
    });
    $("#guardar_operacion").on({
        "click":function(){
            let datos = get_data('proceso',this);
            let proceso = datos.written;
            request_missing_data(datos['err']);
            let detalles = recorrer_tabla("tbl_campos");
            var detallesJson = JSON.stringify(detalles);
            proceso.append('detalles', detallesJson);
            $.ajax({
                type: "POST",
                url: rt_operaciones,
                data: proceso,
                processData: false,
                contentType: false,
                cache: false,
            }).done(function(resp) {
                $.notify(resp, "info");
                listar();
                $(".proceso, #campo, #tipo, #valores, #descripcion").val("");
                $("#tbl_campos>tbody").html("");
                $("#txtnombre_proceso").focus();
            }).fail(function(jqXHR, textStatus) {
                validate.error_ajax(jqXHR, textStatus);
            });
        }
    });
    $("#txtfilAccion").on({
        "keyup":function(){
            $.ajax({
                type: "POST",
                url: rt_operaciones,
                data: {gv_action:'filtrar_opcion', nombre:$("#txtfilAccion").val()},
                cache: false,
            }).done(function(resp){
                mostrar_lista(resp);
            }).fail(function(jqXHR, textStatus) {
                validate.error_ajax(jqXHR, textStatus);
            });            
        }
    });
    $("#modificar_operacion").on({
        "click":function(){
            let datos = get_data('proceso',this);
            let proceso = datos.written;
            request_missing_data(datos['err']);
            let detalles = recorrer_tabla("tbl_campos");
            var detallesJson = JSON.stringify(detalles);
            proceso.append('detalles', detallesJson);
            proceso.append('id',this.name);
            $.ajax({
                type: "POST",
                url: rt_operaciones,
                data: proceso,
                processData: false,
                contentType: false,
                cache: false,
            }).done(function(resp) {
                $.notify(resp, "info");
                listar();
                $(".proceso, #campo, #tipo, #valores, #descripcion").val("");
                $("#exampleModal").modal("hide");
            }).fail(function(jqXHR, textStatus) {
                validate.error_ajax(jqXHR, textStatus);
            });
        }
    });
    $("#cancelar_operacion").on({
        "click":function(){
            $("#exampleModal").modal("hide");
            $(".proceso, #campo, #tipo, #valores, #descripcion").val("");
            $("#modificar_operacion").hide();
            $("#guardar_operacion").show();
        }
    });
    $('#myTabs a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });
    $(".filsolicitud").on({
        "change":function(){lista_solicitudes();},
        "keyup":function(){lista_solicitudes();},
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
function retirar(a){
    if(confirm("¿DESEA ELIMINAR ESTE CAMPO?")){
        $(a).parent().parent().remove();
    }
}
function envio(){
    $("#formulario_reporte").submit();
}
function recorrer_tabla(a){
    let columnas = [];
    $(`#${a} tbody tr`).each(function() {
        let fila = $(this);
        let celdas = fila.find("td:not(:last-child)"); // Excluye la última columna
        let columnaActual = [];
        celdas.each(function() {
            var contenido = $(this).text().trim();
            columnaActual.push(contenido);
        });
        columnas.push(columnaActual);
    });
    return columnas;
}
function listar(){
    $.ajax({
        type: "POST",
        url: rt_operaciones,
        data: {gv_action:'lista_tarjetas_operaciones'},
        cache: false,
    }).done(function(resp) {
        mostrar_lista(resp);
    }).fail(function(jqXHR, textStatus) {
        validate.error_ajax(jqXHR, textStatus);
    });
}
function mostrar_lista(resp){
    resp=JSON.parse(resp);
    let td="";
    for(i=0;i<resp.length;i++){
        td=`${td}<br/><div class="card">
            <div class="card-body">
                <div class="form-check">
                    <button class="btn btn-primary btn-sm" name="${resp[i]['id']}" onclick="editar_operacion(this.name)" style="position:relative; left:94%">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" name="${resp[i]['id']}" onclick="eliminar_operacion(this.name)" class="btn btn-danger btn-sm" style="position:relative; left:94%">
                        <i class="fas fa-trash"></i>
                    </button>
                    <label class="form-check-label" for="checkboxInput${resp[i]['id']}">    
                        <h5 class="card-title">${resp[i]['nombre_proceso']}</h5>
                    </label>
                </div>
                <div class="row">
                    <div class="col-6">
                        <p>${resp[i]['descripcion_proceso']}</p>
                        <p>${resp[i]['link_plantilla']}</p>
                    </div>
                    <div class="col-6">
                        <div id="solicitudes${resp[i]['id']}" name="${resp[i]['id']}" class="solicitudes">
                        </div>
                    </div>
                </div>
            </div>
        </div>`;
    }
    $("#tarjetas").html(td);
}
function eliminar_operacion(a){
    if (confirm("DESEA ELIMINAR ESTA OPERACION")){
        $.ajax({
            type: "POST",
            url: rt_operaciones,
            data: {gv_action:'eliminar_opcion', id:a},
            cache: false,
        }).done(function(resp){
            $.notify(resp,'info');
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
        data: {gv_action:'editar_operacion', id:a},
        cache: false,
    }).done(function(resp){
        resp=JSON.parse(resp);
        console.log(resp);
        $("#exampleModal").modal("show");
        $("#txtnombre_proceso").val(resp[0][0]['nombre_proceso']);
        $("#txtdescripcion_proceso").val(resp[0][0]['descripcion_proceso']);
        $("#txtlink_ficha").val(resp[0][0]['link_plantilla']);
        $("#modificar_operacion").prop({"name":a}).show();
        $("#guardar_operacion").hide();       let tr="";
        for(let i=0;i<resp[1].length;i++){
            tr=`${tr}<tr>
                <td>${resp[1][i]['campo']}</td>
                <td>${resp[1][i]['tipo']}</td>
                <td>${resp[1][i]['valores']}</td>
                <td>${resp[1][i]['descripcion']}</td>
                <td>
                    <button type="button" class="btn btn-danger btn-sm" name="${resp[1][i]['id']}" onclick="retirar_bd(this)">
                        <i class="fa fa-minus"></i>
                    </button>
                </td>
            </tr>`;
        }
        $("#tbl_campos > tbody").html(tr);
    }).fail(function(jqXHR, textStatus) {
        validate.error_ajax(jqXHR, textStatus);
    });
}
function retirar_bd(a){
    id=a.name;
    if (confirm("DESEA ELIMINAR ESTE CAMPO")){
        $(a).parent().parent().remove();
    }
}
function lista_solicitudes(){
    $.ajax({
        type: "POST",
        url: rt_operaciones,
        data:{
            gv_action:'mostrar_solicitudes_sa',
            centro:$("#filCentro").val(),
            tipo:$("#filacciones").val(),
            estado:$("#filestado").val(),
            numero:$("#filnumero").val(),
        },
        cache: false,
    }).done(function(mens){
        mens=JSON.parse(mens);
        console.log(mens);
        let td="";
        $.each(mens, function(indice, elemento){
            td=`${td}<br/><div class="card">
                <div class="card-head">
                    <button class="btn btn-primary btn-sm" name="${elemento['numero solicitud']}" onclick="mostrar_solicitud(this.name)" style="position:relative; left:90%">
                        <i class="fas fa-edit"></i>
                    </button><input class="form-check-input" value="${elemento['numero solicitud']}" name="lista_reporte[]" type="checkbox" form="formulario_reporte" id="checkboxInput${elemento['numero solicitud']}">
                    <label class="form-check-label" for="checkboxInput${elemento['numero solicitud']}">
                        <h5 class="card-title">Numero de Solicitud: ${elemento['numero solicitud']} ${elemento['nombre_proceso']}</h5>
                    </label>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-6" style="display:inline-block;">
                            <p>${elemento['causa_solicitud']}  ${elemento['estado_solicitud']} ${elemento['fecha_solicitud']}</p>
                            <p>${elemento['nombre_centro']} ${elemento['direccion_centro']} ${elemento['municipio_centro']} </p>
                            <p>${elemento['Titulo']} ${elemento['nombre_completo']} </p>
                            <p>${elemento['Correo']} ${elemento['Telefono']}</p>
                            <p>${elemento['puesto']} ${elemento['condicion']} ${elemento['estatus']} ${elemento['telefono_centro']}</p>
                        </div>
                        <div class="col-6" style="display:inline-block;" id="notas${elemento['numero solicitud']}">
                            <table class="table">`;
                                if(elemento.estado_solicitud=="recepcion"){
                                    td+=`<tr>
                                    <td>
                                        <textarea class="form-control form-control-sm"  style="max-height: 60px; resize: none; overflow-y: auto;" id="obser${elemento['numero solicitud']}"></textarea>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary" style="height: 50px; width:100%" onclick="guardar_observacion(${elemento['numero solicitud']})">
                                            <i class="far fa-envelope"></i>
                                        </button>
                                    </td>
                                </tr>`;
                                }
                                td+=`<tr>
                                    <td colspan="2">
                                        <div id="div${elemento['numero solicitud']}" name="${elemento['numero solicitud']}" style="max-height: 120px; overflow:auto; display:block;" class="lista_observaciones">
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>`;
        });
        $("#lista_solicitudes").html(td);
        buscar_observaciones();
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
function mostrar_solicitud(a){
    $.ajax({
        type: "POST",
        url: rt_operaciones,
        data:{gv_action:'editar_solicitud', id:a},
        async:false,
        cache: false,
    }).done(function(mens) {
        mens=JSON.parse(mens);
        console.log(mens);
        $("#mymodal").modal("show");
        ht=`<div>${mens[0][0]['descripcion_proceso']}</div>`;
        $.each(mens[1], function(indice, elemento) {
            /*if(elemento['tipo']=="texto" || elemento['tipo']=="lista"){
                for(i=0;i<mens[2].length;i++){
                    if(elemento["campo"]==mens[2][i]['campo']){
                        valor=mens[2][i]['valor'];
                    }
                }
                ht=`${ht}<div class="form-group">
                    ${elemento["campo"]}
                    <div class="form-control form-control-sm">${valor}</div>
                </div>`;
            }*/
            if(elemento['tipo']=="texto" || elemento['tipo']=="lista"){
                /*for(i=0;i<mens[2].length;i++){
                    if(elemento["campo"]==mens[2][i]['campo']){
                        valor=mens[2][i]['valor'];
                    }
                }*/
                ht=`${ht}<div class="form-group">
                    ${elemento["campo"]}
                    <div class="form-control form-control-sm">${mens[2][indice]['valor']}</div>
                </div>`;
            }
            if(elemento['tipo']=="archivo"){
                /*for(i=0;i<mens[2].length;i++){
                    if(elemento["campo"].replace(" ", "_")==mens[2][i]['campo']){
                        valor=mens[2][i]['valor'];
                    }
                }*/
                ht=`${ht}<div class="form-group">
                ${elemento["campo"]}
                <a href="${mens[2][indice]['valor']}" target="_blank">${mens[2][indice]['valor']}</a>
                </div>`;
            } 
        });
        if(mens[0][0]['status']=="recepcion"){
            ht=`${ht}<div class="form-group">
            Resolución:
            <textarea id="resolucion" max-length="300" class="form-control form-control-sm"></textarea></div>
            <button class="btn btn-sm btn-primary" id="btnenviar" onclick="finalizar_tramite(${a},this)" value="Aprobado" style="width:200px">Aprobar</button>
            <button class="btn btn-sm btn-danger" id="btnenviar" onclick="finalizar_tramite(${a},this)" value="rechazado" style="width:200px">Rechazar </button>`;
        }else{
            ht=`${ht}<div class="form-group">
            Estado: ${mens[0][0]['status']} \n
            Resolucion: ${mens[0][0]['resolucion']}
            </div>`;
        }
        ht=`${ht}<button class="btn btn-sm btn-secondary" id="btnenviar" onclick="cancelar_accion()" style="width:200px">Volver</button>`;
        $("#txtcausa").text(mens[0][0].causa);
        $("#txtnombre_proceso_solicitado").text(mens[0][0].nombre_proceso);
        $("#mostrar_campos").html(ht);
    }).fail(function(jqXHR, textStatus) {
        validate.error_ajax(jqXHR, textStatus);
    });
}
function iniciar_filtro_solicitudes(){
    $.ajax({
        type: "POST",
        url: rt_operaciones,
        data:{
            gv_action:'iniciar_filtros'
        },
        cache: false,
    }).done(function(mens) {
        mens=JSON.parse(mens);
        ht="<option value=''>--</option>";
        for(i=0;i<mens[0].length;i++){
            ht+=`<option value="${mens[0][i]['id_centro']}">${mens[0][i]['Codigo_centro']} - ${mens[0][i]['Nombre']} - ${mens[0][i]['Tipo_centro']} - ${mens[0][i]['Municipio']}</option>`;
        }
        $("#filCentro").html(ht);
        ht="<option value=''>--</option>";
        for(i=0;i<mens[1].length;i++){
            ht+=`<option value='${mens[1][i]['id']}'>${mens[1][i]['nombre_proceso']}</option>`;
        }
        $("#filacciones").html(ht);
        ht="<option value=''>--</option>";
        for(i=0;i<mens[2].length;i++){
            ht+=`<option>${mens[2][i]['status']}</option>`;
        }
        $("#filestado").html(ht);
    }).fail(function(jqXHR, textStatus) {
        validate.error_ajax(jqXHR, textStatus);
    });
}
function cancelar_accion(){
    $("#mymodal").modal("hide");
}
function finalizar_tramite(a,b){
    if($("#resolucion").val()!=""){
        estado=$(b).val();
        $.ajax({
            type: "POST",
            url: rt_operaciones,
            data: {
                gv_action:"finalizar_tramite",
                solicitud:a, 
                resolucion:$("#resolucion").val(),
                estado:estado,
            },
            cache: false,
        }).done(function(resp) {
            $.notify(resp, "info");
            $("#mymodal").modal("hide");
        }).fail(function(jqXHR, textStatus) {
            validate.error_ajax(jqXHR, textStatus);
        });
    }else{
        $.notify("DEBE EMITIR UNA RESOLUCION DONDE INDIQUE LA DECISION TOMADA", "info");
    }
}