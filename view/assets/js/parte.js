$(document).ready(function(){
    desactivarautocompletado();
    listar();
    identidad();
    $(".filtro").on({
        "change":function(){filtrar();}
    });
    $("#btn_menu").on({
        'click':function(){
            mostrar_menu();
        }
    });
    $("#btn_add_parte").on({
        "click":function(){
            $("#exampleModal").modal("toggle");
        }
    });
    $("#addinfpm").on({
        "click":function(){
            let valores=[];
            let faltantes=[];
            let collection = $(".infparmen");
            for(i=0;i<collection.length;i++){
                let req = "no";
                if($(collection[i]).hasClass('required')){req = "si";}
                if(collection[i].value.trim()!=""){
                    valores.push(collection[i].value.trim());
                }else{
                    if(req=="si"){faltantes.push(collection[i].name);}    
                }
            }
            let st=$("#addsubtot").val();
            request_missing_data(faltantes);
            let ntr="<tr class='filasubtotal"+st+"'>";
            for(i=0;i<valores.length;i++){
                ntr+="<td>"+valores[i]+"</td>";
            }
            ntr+="<td><button onclick='eliminar_fila(this)' class='btn btn-sm btn-warning'><i class='fas fa-trash-alt'/></button></td></tr>";
            $("#tabinfpm > tbody").append(ntr);
            $(".infparmen").val("0");
            $("#txtgrado").val("");
        }
    });
    $("#addsubtot").on({
        "click": function() {
            let st = $("#addsubtot").val();
            let filas = $(`.filasubtotal${st}`);
            let primeraFila = filas.first();
            let totales = new Array(primeraFila.find('td').length - 1).fill(0); // Se resta 1 para excluir la celda "Subtotal"
            filas.each(function() {
                $(this).find('td').each(function(index) {
                    if (index > 0) { // Se omite la primera celda "Subtotal"
                        let contenido = parseFloat($(this).text());
                        totales[index - 1] += isNaN(contenido) ? 0 : contenido;
                    }
                });
            });
            let nuevaFila = $("<tr></tr>").css({"background-color":"aquamarine", "color":"black"});
            let celdaSubtotal = $("<td></td>").text("Subtotal");
            nuevaFila.append(celdaSubtotal);
            totales.forEach(function(total, index) {
                if (index === totales.length - 1) {
                    let celdaEliminar = $("<td></td>").html('<button class="btn btn-sm btn-danger" onclick="eliminar_subtotal(this)" name="'+st+'"><i class="fas fa-trash-alt"></i></button>');
                    nuevaFila.append(celdaEliminar);
                } else {
                    let celdaTotal = $("<td></td>").text(total.toFixed(2));
                    nuevaFila.append(celdaTotal);
                }
            });
            $("#tabinfpm tbody").append(nuevaFila);
            let currentValue = parseFloat($("#addsubtot").val());
            let incrementedValue = currentValue + 1;
            $("#addsubtot").val(incrementedValue);
        }
    });
    $('#myTabs a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });
    $('#clsmodal').on({"click":function(){
        $("#exampleModal").modal("toggle");
    }})
    $("#guardarpartemensual").on({
        "click":function(){
            let datos = get_data('parte',this);
            let parte = datos.written;
            request_missing_data(datos['err']);
            let docentes = recorrer_tabla("tabinfdoc");
            let detparte = recorrer_tabla("tabinfpm");
            var docentesJson = JSON.stringify(docentes);
            var detparteJson = JSON.stringify(detparte);
            parte.append('docentes', docentesJson);
            parte.append('detalles', detparteJson);
            $.ajax({
                type: "POST",
                url: rt_parte_mensual,
                data: parte,
                processData: false,
                contentType: false,
                cache: false,
            }).done(function(resp) {
                $.notify(resp,"Info");
                $(".parte, .infparmen, .tabprof").val("");
                $("#tabinfdoc>tbody, #tabinfpm>tbody").html("");
                listar();
            }).fail(function(jqXHR, textStatus) {
                validate.error_ajax(jqXHR, textStatus);
            });
        }
    });
    $("#addinfprof").on({
        "click":function(){
            let valores=[];
            let faltantes=[];
            let collection = $(".tabprof");
            for(i=0;i<collection.length;i++){
                let req = "no";
                if($(collection[i]).hasClass('required')){req = "si";}
                if(collection[i].value.trim()!=""){
                    valores.push(collection[i].value.trim());
                }else{
                    if(req=="si"){faltantes.push(collection[i].name);}    
                }
            }
            request_missing_data(faltantes);
            let ntr="<tr>";
            for(i=0;i<valores.length;i++){
                ntr+="<td>"+valores[i]+"</td>";
            }
            ntr+="<td><button onclick='eliminar_fila(this)' class='btn btn-sm btn-warning'><i class='fas fa-trash-alt'/></button></td></tr>";
            $("#tabinfdoc > tbody").append(ntr);
            $(".tabprof").val("");
            $("#ltdocente").focus();
        }
    });
    $("#btn_reporte").on({
        "click":function(){
            envio();
        }
    });
    $("#txtndocmas, #txtndocfem").on({
        "keyup":function(){
            let dm = parseFloat($("#txtndocmas").val().trim());
            if(dm=="" || isNaN(dm)){dm=0;}
            let df = parseFloat($("#txtndocfem").val().trim());
            if(df=="" || isNaN(df)){df=0;}
            $("#txtntotdoc").val(dm+df);
        }
    });
    $("#txtanno_parte_anterior, #txtmesparteanterior").on({
        "change":function(){
            if($("#txtanno_parte_anterior").val()!="" && $("#txtmesparteanterior").val()!=""){
                $.ajax({
                    type: "POST",
                    url: rt_parte_mensual,
                    data: {gv_action:'datos_mes_anterio',anno:$("#txtanno_parte_anterior").val(),mes:$("#txtmesparteanterior").val()},
                    cache: false,
                }).done(function(resp) {
                    $("#grados").html("");
                    if(resp.length>4){
                        resp=JSON.parse(resp);
                        let lista ="";
                        for(i=0;i < resp.length; i++){
                            lista+=`<option>${resp[i].grado} - ${resp[i].mat_act_hem} - ${resp[i].mat_act_var} - ${resp[i].mat_act_tot}.</option>`;
                        }
                        $("#grados").html(lista);
                    }
                    else{
                        $.notify("NO SE ENCONTRARON DATOS DE ESTE PERIODO\nDEBERA SUMINISTRARLOS MANUALMENTE","Info");
                    }
                }).fail(function(jqXHR, textStatus) {
                    validate.error_ajax(jqXHR, textStatus);
                });

                $.ajax({
                    type: "POST",
                    url: rt_parte_mensual,
                    data: {gv_action:'docentes_mes_anterio',anno:$("#txtanno_parte_anterior").val(),mes:$("#txtmesparteanterior").val()},
                    cache: false,
                }).done(function(resp) {
                    $("#ldocentes").html("");
                    if(resp.length>4){
                        resp=JSON.parse(resp);
                        let lista ="";
                        for(i=0;i < resp.length; i++){
                            lista+=`<option>${resp[i].nombre} - ${resp[i].cargo} - ${resp[i].grado}.</option>`;
                        }
                        $("#ldocentes").html(lista);
                    }
                }).fail(function(jqXHR, textStatus) {
                    validate.error_ajax(jqXHR, textStatus);
                });
                
            }
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
    $('#txtgrado').on({
        'change': function() {
            let seleccionado = $(this).val();
            seleccionado = seleccionado.split("-");
            this.value=seleccionado[0].trim()
            $("#txtmantni").val(seleccionado[1].trim());
            $("#txtmantva").val(seleccionado[2].trim());
            $("#txtmantto").val(seleccionado[3].trim());
            calcular_matricula_consolidada_varones();
            calcular_matricula_consolidada_hembras();
            calcular_matricula_anterior_total();
        }
    });
    $('#ltdocente').on({
        'change': function() {
            let seleccionado = $(this).val();
            seleccionado = seleccionado.split("-");
            this.value = seleccionado[0].trim();
            $("#ltcargo").val(seleccionado[1].trim());
            $("#ltgrado").val(seleccionado[2].trim());
        }
    });
    $("#txtmantva").on({
        "keyup":function(){
            calcular_matricula_consolidada_varones();
            calcular_matricula_anterior_total();
        }
    });
    $("#txtmantni").on({
        "keyup":function(){
            calcular_matricula_consolidada_hembras();
            calcular_matricula_anterior_total();
        }
    });
    $("#txtmaacni").on({
        "keyup":function(){
            calcular_matricula_consolidada_hembras();
            calcular_matricula_actual_total();
            calcular_asistencia_media_hembras();
            calcular_asistencia_media_total();
            calcular_tanto_porciento_hembras();
            calcular_tanto_porciento_total();
        }
    });
    $("#txtmaacva").on({
        "keyup":function(){
            calcular_matricula_consolidada_varones();
            calcular_matricula_actual_total();
            calcular_asistencia_media_varones();
            calcular_asistencia_media_total();
            calcular_tanto_porciento_varones();
            calcular_tanto_porciento_total();
        }
    });
    $("#txtinani").on({
        "keyup":function(){
            calcular_asistencia_media_hembras();
            calcular_asistencia_media_total();
            calcular_tanto_porciento_hembras();
            calcular_tanto_porciento_total();
            calcular_inasistencia_total();
        }
    });
    $("#txtinava").on({
        "keyup":function(){
            calcular_asistencia_media_varones();
            calcular_asistencia_media_total();
            calcular_tanto_porciento_varones();
            calcular_tanto_porciento_total();
            calcular_inasistencia_total();
        }
    });
    $("#txtndiastrab").on({
        "keyup":function(){
            calcular_asistencia_media_hembras();
            calcular_asistencia_media_varones();
            calcular_asistencia_media_total();
            calcular_tanto_porciento_hembras();
            calcular_tanto_porciento_varones();
            calcular_tanto_porciento_total();
        }
    });
    $("#txtingni, #txtingva").on({
        "keyup":function(){
            calcular_total_ingresos();   
        }
    });
    $("#txtdeserni, #txtdeserva").on({
        "keyup":function(){
            calcular_total_desertores();   
        }
    });
    $("#txttrasladosni, #txttrasladosva").on({
        "keyup":function(){
            calcular_total_traslados();   
        }
    });
    $("#ina_aut, #ina_no_aut").on({
        "keyup":function(){
            $("#tot_ina").val(parseInt($("#ina_aut").val())+parseInt($("#ina_no_aut").val()));
        }
    });
    $("#btn_faltantes").on({
        "click":function(){$("#f_faltantes").submit();}
    })
})
function calcular_matricula_consolidada_varones(){
    $("#txtmaacva").val().trim()!="" ? mat_act_var=parseFloat($("#txtmaacva").val()) : mat_act_var=0; 
    $("#txtmantva").val().trim()!="" ? mat_ant_var=parseFloat($("#txtmantva").val()) : mat_ant_var=0;
    matricula_consolidada_varones= mat_act_var+mat_ant_var;
    $("#txtmcv").val(matricula_consolidada_varones);
    calcular_matricula_consolidada_total();
}
function calcular_matricula_consolidada_hembras(){
    $("#txtmaacni").val().trim()!="" ? mat_act_hem=parseFloat($("#txtmaacni").val()) : mat_act_hem=0; 
    $("#txtmantni").val().trim()!="" ? mat_ant_hem=parseFloat($("#txtmantni").val()) : mat_ant_hem=0;
    matricula_consolidada_hembras= mat_act_hem+mat_ant_hem;
    $("#txtmcn").val(matricula_consolidada_hembras);
    calcular_matricula_consolidada_total();
}
function calcular_matricula_consolidada_total(){
    $("#txtmcv").val().trim()!="" ? mat_con_var=parseFloat($("#txtmcv").val()) : mat_act_var=0;
    $("#txtmcn").val().trim()!="" ? mat_con_hem=parseFloat($("#txtmcn").val()) : mat_ant_hem=0;
    total_matricula_consolidada=mat_con_var+mat_con_hem;
    $("#txtmct").val(total_matricula_consolidada);
}
function calcular_matricula_anterior_total(){
    $("#txtmantva").val().trim()!="" ? mat_ant_var=parseFloat($("#txtmantva").val()) : mat_ant_var=0;
    $("#txtmantni").val().trim()!="" ? mat_ant_hem=parseFloat($("#txtmantni").val()) : mat_ant_hem=0;
    total_matricula_anterior=mat_ant_var+mat_ant_hem;
    $("#txtmantto").val(total_matricula_anterior);
};
function calcular_matricula_actual_total(){
    $("#txtmaacva").val().trim()!="" ? mat_act_var=parseFloat($("#txtmaacva").val()) : mat_act_var=0;
    $("#txtmaacni").val().trim()!="" ? mat_act_hem=parseFloat($("#txtmaacni").val()) : mat_act_hem=0;
    total_matricula_actual=mat_act_var+mat_act_hem;
    $("#txtmaacto").val(total_matricula_actual);
};
function calcular_asistencia_media_hembras(){
    $("#txtmaacni").val().trim()!=""    ? mat_act_hem=parseFloat($("#txtmaacni").val())       : mat_act_hem=0;
    $("#txtinani").val().trim()!=""     ? ina_hem=parseFloat($("#txtinani").val())            : ina_hem=0;
    $("#txtndiastrab").val().trim()!="" ? dias_trabajados=parseFloat($("#txtndiastrab").val()):dias_trabajados=0;
    asistencia_media_hembras=mat_act_hem-(ina_hem/dias_trabajados);
    $("#txtasismedni").val(asistencia_media_hembras.toFixed(2));
}
function calcular_asistencia_media_varones(){
    $("#txtmaacva").val().trim()!=""    ? mat_act_var=parseFloat($("#txtmaacva").val())       : mat_act_var=0;
    $("#txtinava").val().trim()!=""     ? ina_var=parseFloat($("#txtinava").val())            : ina_var=0;
    $("#txtndiastrab").val().trim()!="" ? dias_trabajados=parseFloat($("#txtndiastrab").val()):dias_trabajados=0;
    asistencia_media_varones=mat_act_var-(ina_var/dias_trabajados);
    $("#txtasismedva").val(asistencia_media_varones.toFixed(2));
}
function calcular_asistencia_media_total(){
    $("#txtasismedva").val().trim()!=""?asistencia_media_varones=parseFloat($("#txtasismedva").val()):asistencia_media_varones=0;
    $("#txtasismedni").val().trim()!=""?asistencia_media_hembras=parseFloat($("#txtasismedni").val()):asistencia_media_hembras=0;
    total_asistencia_media=asistencia_media_varones+asistencia_media_hembras;
    $("#txtasismedto").val(total_asistencia_media.toFixed(2));
}
function calcular_tanto_porciento_hembras(){
    $("#txtasismedni").val().trim()!="" ? asistencia_media_hembras=parseFloat($("#txtasismedni").val()):asistencia_media_hembras=0;
    $("#txtmaacni").val().trim()!="" ? mat_act_hem=parseFloat($("#txtmaacni").val()):mat_act_hem=0;
    tanto_pc_hembras=(asistencia_media_hembras*100)/mat_act_hem;
    $("#txttanporni").val(tanto_pc_hembras.toFixed(2));
}
function calcular_tanto_porciento_varones(){
    $("#txtasismedva").val().trim()!="" ? asistencia_media_varones=parseFloat($("#txtasismedva").val()):asistencia_media_varones=0;
    $("#txtmaacva").val().trim()!="" ? mat_act_var=parseFloat($("#txtmaacva").val()):mat_act_var=0;
    tanto_pc_varones=(asistencia_media_varones*100)/mat_act_var;
    $("#txttanporva").val(tanto_pc_varones.toFixed(2));
}
function calcular_tanto_porciento_total(){
    $("#txtasismedto").val()!="" ? asis_med_tot = parseFloat($("#txtasismedto").val()) : asis_med_tot = 0;
    $("#txtmaacto").val()!="" ? mat_act_tot = parseFloat($("#txtmaacto").val()): mat_act_tot = 0; 
    tpct=(asis_med_tot*100)/mat_act_tot
    $("#txttanporto").val(tpct.toFixed(2));
}
function calcular_inasistencia_total(){
    $("#txtinani").val().trim()!=""?inasistencia_varones=parseFloat($("#txtinani").val()):inasistencia_varones=0;
    $("#txtinava").val().trim()!=""?inasistencia_hembras=parseFloat($("#txtinava").val()):inasistencia_hembras=0;
    total_inasistencia=inasistencia_varones+inasistencia_hembras;
    $("#txtinato").val(total_inasistencia);
}
function calcular_total_ingresos(){
   $("#txtingni").val().trim() !="" ? ingresos_hembras = parseFloat($("#txtingni").val()) : ingresos_hembras = 0;
   $("#txtingva").val().trim() !="" ? ingresos_varones = parseFloat($("#txtingva").val()) : ingresos_varones = 0;
   total_ingresos = ingresos_hembras + ingresos_varones;
   $("#txtingto").val(total_ingresos);
}
function calcular_total_traslados(){
    $("#txttrasladosni").val().trim() !="" ? traslados_hembras = parseFloat($("#txttrasladosni").val()) : traslados_hembras = 0;
    $("#txttrasladosva").val().trim() !="" ? traslados_varones = parseFloat($("#txttrasladosva").val()) : traslados_varones = 0;
    totaltraslados = traslados_hembras + traslados_varones;
    $("#txttrasladosto").val(totaltraslados);
}
function calcular_total_desertores(){
   $("#txtdeserni").val().trim()!="" ? desercione_hembras = parseFloat($("#txtdeserni").val()) : desercione_hembras = 0;
   $("#txtdeserva").val().trim()!="" ? desercione_varones = parseFloat($("#txtdeserva").val()) : desercione_varones = 0;
   total_deserciones = desercione_hembras + desercione_varones;
   $("#txtdeserto").val(total_deserciones);
};
function eliminar_subtotal(a){
    if(confirm("DESEA ELIMINAR ESTA FILA")){
        $(a).parent().parent().remove();
        let st = $("#addsubtot").val();
        let ac = a.name;
        alert(st +"<->" +ac);
        if(st==(ac-1)){
            let currentValue = parseFloat($("#addsubtot").val());
            let incrementedValue = currentValue - 1;
            $("#addsubtot").val(incrementedValue);
        }else{
            let collection = $(`.filasubtotal${ac}`);
            for(i=0;i<collection.length;i++){
                $(collection[i]).removeClass(`filasubtotal${ac}`);
                $(collection[i]).addClass(`filasubtotal${parseFloat(ac)+1}`);
            };
        }
    }
}
function eliminar_fila(a){
    if(confirm("DESEA ELIMINAR ESTA FILA")){
        $(a).parent().parent().remove();
    }
}
function limpiar_tabla(){
    if(confirm("DESEA limpiar los datos cargados")){
        $("#tabinfpm tbody").html("");
    }
}
function recorrer_tabla(a){
    let columnas = [];
    $(`#${a} tbody tr`).each(function() {
        let fila = $(this);
        let celdas = fila.find("td:not(:last-child)"); // Excluye la última columna
        let columnaActual = [];
        celdas.each(function() {
            var contenido = $(this).text();
            columnaActual.push(contenido);
        });
        columnas.push(columnaActual);
    });
    return columnas;
}
function listar(){
    $.ajax({
        type: "POST",
        url: rt_parte_mensual,
        data: {gv_action:"lista"},
        //processData: false,
        //contentType: false,
        cache: false,
    }).done(function(resp) {
        resp=JSON.parse(resp);
        mostrar_parte_mensual(resp);
    }).fail(function(jqXHR, textStatus) {
        validate.error_ajax(jqXHR, textStatus);
    });

    $.ajax({
        type: "POST",
        url: rt_parte_mensual,
        data: {gv_action:'l_centro'},
        cache: false,
    }).done(function(resp) {
        $("#centro").html("");
        if(resp.length>4){
            resp=JSON.parse(resp);
            lista="<option value=''>--</option>";
            for(i=0;i < resp.length; i++){
                lista+=`<option value="${resp[i].id_centro}">${resp[i].Nombre} - ${resp[i].Tipo_centro} - ${resp[i].Codigo_centro}</option>`;
            }
            $("#centro").html(lista);
        }
    }).fail(function(jqXHR, textStatus) {
        validate.error_ajax(jqXHR, textStatus);
    });
    lanno();
}
function envio(){
    $("#formulario_reporte").submit();
}
function eliminar_parte(a){
    if (confirm("DESEA ELIMINAR ESTE REGISTRO DE PARTE MENSUAL")){
        $.ajax({
            type: "POST",
            url: rt_parte_mensual,
            data: {gv_action:"eliminar_parte_mensual", id:a},
            cache: false,
        }).done(function(resp) {
            $.notify(resp,"Info");
            listar();
        }).fail(function(jqXHR, textStatus) {
            validate.error_ajax(jqXHR, textStatus);
        });
    }
}
function mostrar_parte_mensual(resp){
    vhtml=``;
    for(i=0 ; i < resp.length ; i++){
        vhtml=`${vhtml}<br/><div class="card">
            <div class="card-body">
                <div class="form-check">
                    <button type="button" name="${resp[i]['id']}" onclick="eliminar_parte(this.name)" class="btn btn-danger btn-sm" style="position:relative; left:98%">
                        <i class="fas fa-trash"></i>
                    </button>
                    <input class="form-check-input" value="${resp[i]['id']}" name="lista_reporte[]" type="checkbox" form="formulario_reporte" id="checkboxInput${resp[i]['id']}">
                    <label class="form-check-label" for="checkboxInput${resp[i]['id']}">    
                        <h5 class="card-title">${resp[i]['Nombre']}: ${resp[i]['mes']} - ${resp[i]['anno']}</h5>
                    </label>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Código del Centro:</strong> ${resp[i]['Codigo_centro']}</li>
                            <li class="list-group-item"><strong>Nombre:</strong> ${resp[i]['Nombre']}</li>
                            <li class="list-group-item"><strong>Tipo de Centro:</strong> ${resp[i]['Tipo_centro']}</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Año:</strong> ${resp[i]['anno']}</li>
                            <li class="list-group-item"><strong>Mes:</strong> ${resp[i]['mes']}</li>
                            <li class="list-group-item"><strong>Días Trabajados:</strong> ${resp[i]['dias_trab']}</li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Docentes Femeninos:</strong> ${resp[i]['doc_fem']}</li>
                            <li class="list-group-item"><strong>Docentes Masculinos:</strong> ${resp[i]['doc_mas']}</li>
                            <li class="list-group-item"><strong>Total de Docentes:</strong> ${resp[i]['tot_doc']}</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>`;
    }
    $("#tab_parte").html(vhtml);
}
function filtrar(){
    let municipio = $("#municipio").length ? $("#municipio").val() : "";
    let centro = $("#centro").length ? $("#centro").val() : "";
    let anno = $("#anio").length ? $("#anio").val() : "";
    let mes = $("#mes").length ? $("#mes").val() : "";
    $.ajax({
        type: "POST",
        url : rt_parte_mensual,
        data:{
            gv_action:"filtrar_reportes",
            municipio:municipio,
            centro:centro,
            anno:anno,
            mes:mes
        },
        cache:false,
    }).done(function(resp){
        resp=JSON.parse(resp);
        mostrar_parte_mensual(resp);
    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus)
    });
}
function lanno(){
    $.ajax({
        type: "POST",
        url : rt_parte_mensual,
        data:{gv_action:"lanno"},
        cache:false,
    }).done(function(resp){
        resp=JSON.parse(resp);
        lista="<option value=''>--</option>";
        for(let i=0;i<resp.length;i++){
            lista+="<option>"+resp[i].anno+"</option>";
        }
        $("#anio").html(lista);
    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus)
    });
}