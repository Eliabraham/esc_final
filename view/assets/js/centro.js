$(document).ready(function(){
    listar();
    desactivarautocompletado();
    municipios();
    identidad();
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
    })
    $("#btn_add_centro").on({
        'click':function(){
            $("#btn_modificar").prop("name","-1").hide();
            $("#btn_guardar, #pdf_acuerdo, #lf, #txtFoto").show();
            $("#centro").val("");
            $("#vista_Logo").html("");
            let elemento = document.getElementById("arcLogo");
            elemento.className = "centro required";
            elemento = document.getElementById("arcacuerdo");
            elemento.className = "centro required" ;
            $("#exampleModal").modal("toggle");
        }
    });
    $("#btn_guardar").on({
        'click':function(){
            datos = get_data('centro',this);
            data=datos.written;
            request_missing_data(datos['err']);
            $.ajax({
                type: "POST",
                url:  rt_centros,
                processData: false,
                contentType: false,
                data: data,
                cache:false,
            }).done(function(resp){
                $.notify(resp, "info");
                const selectMunicipio = document.getElementById('txtMunicipio');
                let mun=$("#txtMunicipio").val();
                $(".centro").val("");
                if (selectMunicipio.disabled) {$("#txtMunicipio").val(mun);} 
                $("#vista_Logo").html("");
                listar();
            }).fail(function(jqXHR, textStatus){
                validate.error_ajax(jqXHR, textStatus)
            });
        }
    })
    $("#btn_modificar").on({
        'click':function(){
            datos = get_data('centro',this);
            data=datos.written;
            data.append("id",this.name);
            request_missing_data(datos['err']);
            $.ajax({
                type: "POST",
                url:  rt_centros,
                processData: false,
                contentType: false,
                data: data,
                cache:false,
            }).done(function(resp){
                $.notify(resp, "info");
                $("#centro").val("");
                $("#vista_Logo").html("");
                listar();
                $("#exampleModal").modal("toggle");
            }).fail(function(jqXHR, textStatus){
                validate.error_ajax(jqXHR, textStatus)
            });
        }
    })
    $("#btn_cancelar").on({
        'click':function(){
            limpiar();
            $("#exampleModal").modal("toggle");
        }
    });
    $("#btn_menu").on({
        'click':function(){
            mostrar_menu();
        }
    });
    $(".filtro").on({
        "keyup":function(){filtrar();},
        "change":function(){filtrar();}
    });
    $("#txtCodigo").on({
        "blur":function(){
            $.ajax({
                type: "POST",
                url:  rt_centros,
                data: {"codigo":this.value, "gv_action":"validar_codigo", "id":$("#btn_modificar").attr('name')},
                cache:false,
            }).done(function(resp){
                if (resp!="[]"){
                    resp=JSON.parse(resp);
                    $.notify(`ESTE CODIGO YA HA SIDO ASIGNADO AL CENTRO DEUCATIVO:${resp[0]['Nombre']}`, "info");
                    $(".filtro").val("");
                    $("#fil_codigo").val($("#txtCodigo").val());
                    $("#fil_nombre").val(resp[0]['Nombre']);
                    filtrar();
                    $("#exampleModal").modal("toggle");
                }
            }).fail(function(jqXHR, textStatus){
                validate.error_ajax(jqXHR, textStatus)
            });
        }
    });
    $("#arcLogo").on({
        'change':function(){
            agregarImagen(this, 'vista_Logo');
        }
    });
});
function municipios(){
    $.ajax({
        type: "POST",
        url : rt_centros,
        data:{gv_action:'municipios'},
        cache:false,
    }).done(function(resp){
        console.log(resp.length);
        if(resp.length<5){
            alert("ANTES DE CREAR UN CENTRO EDUCATIVO DEBE CREAR LAS DIRECCIONES MUNICIPALES\nESTOS SERAN LOS MUNICIPIOS A LOS QUE SE LE ASIGNARAN LOS CENTROS EDUCATIVOS");
        }
        resp=JSON.parse(resp);
        ht="<option value=''>--</option>";
        $.each(resp, function(indice, elemento){
            ht=`${ht}<option value="${elemento.id}">${elemento.departamento} -- ${elemento.municipio}</option>`;
        });
        $("#txtMunicipio, #fil_municipio").html(ht);
    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus)
    });
}
function envio(){
    $("#formulario_reporte").submit();
}
function filtrar(){
    $.ajax({
        type: "POST",
        url : rt_centros,
        data:{
            gv_action:'filtrar_centros',
            codigo   :$("#fil_codigo").val(),
            nombre   :$("#fil_nombre").val(),
            tipo     :$("#fil_tipo").val(),
            estatus  :$("#fil_estatus").val(),
            acuerdo  :$("#fil_acuerdo").val(),
            municipio:$("#fil_municipio").val()
        },
        cache:false,
    }).done(function(resp){
        mostrar_tabla(resp);
    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus)
    });
}
function capturar(fd, btn){
    let falta="";
    if($("#txtCodigo").val().trim()!=""){fd.append('codigo', $("#txtCodigo").val());}
    else{falta=`${falta} \n debe ingresar un valor en el campo: codigo`};
    
    if($("#txtNombre").val().trim()!=""){fd.append('nombre', $("#txtNombre").val());}
    else{falta=`${falta} \n debe ingresar un valor en el campo: nombre`};
    
    if($("#txtTipo").val().trim()!=""){fd.append('tipo', $("#txtTipo").val());}
    else{falta=`${falta} \n debe ingresar un valor en el campo: tipo`};

    if($("#txtMunicipio").val().trim()!=""){fd.append('municipio', $("#txtMunicipio").val());}
    else{falta=`${falta} \n debe ingresar un valor en el campo: Municipio`};
    
    if($("#txtDireccion").val().trim()!=""){fd.append('direccion', $("#txtDireccion").val());}
    else{falta=`${falta} \n debe ingresar un valor en el campo: direccion`};
    
    if($("#txtTelefono").val().trim()!=""){fd.append('telefono', $("#txtTelefono").val());}
    else{falta=`${falta} \n debe ingresar un valor en el campo: telefono`};
    
    if($("#txtN_acuerdo").val().trim()!=""){fd.append('acuerdo', $("#txtN_acuerdo").val());}
    else{falta=` ${falta} \n debe ingresar un valor en el campo: Numero de Acuerdo`};

    if($("#txtEstatus").val().trim()!=""){fd.append('estatus', $("#txtEstatus").val());}
    else{falta=` ${falta} \n debe ingresar un valor en el campo: Estatus`};
    
    let fileInput = document.getElementById('txtFoto');
    if (fileInput.files.length === 0){
        if(btn=="nuevo"){falta=` ${falta} \n debe ingresar una fotografia`;}
    } else {fd.append('foto',$('#txtFoto')[0].files[0]);}

    fileInput = document.getElementById('pdf_acuerdo');
    if (fileInput.files.length === 0) {fd.append('pdf_acuerdo','');
    } else {fd.append('pdf_acuerdo',$('#pdf_acuerdo')[0].files[0]);}
    if(falta!=""){
        $.notify(falta, "warn");
        throw new Error('Ejecución detenida por datos faltantes');
    }
    return fd;
}
function limpiar(){
    $(".form-control").val("");
    $("#img_escuela, #pdf").parent().remove();
}
function listar(){
    $.ajax({
        type: "POST",
        url:  rt_centros,
        data: {gv_action:'listar_centros'},
        cache:false,
    }).done(function(resp){
       mostrar_tabla(resp);
    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus)
    });
}
function eliminar_centro(a){
    if (confirm("DESEA ELIMINAR ESTE REGISTRO")){
        $.ajax({
            type: "POST",
            url:  rt_centros,
            data: {gv_action:'eliminar_centro', id:a.id},
            cache:false,
        }).done(function(resp){
            $.notify(resp,"info");
            filtrar();
        }).fail(function(jqXHR, textStatus){
            validate.error_ajax(jqXHR, textStatus)
        }); 
    }
}
function editar_centro(a){
    $.ajax({
        type: "POST",
        url:  rt_centros,
        data: {gv_action:'editar_centro', id:a.id},
        cache:false,
    }).done(function(resp){
        resp=JSON.parse(resp);
        limpiar();
        $("#btn_modificar").prop("name",resp[0]["id_centro"]);
        $("#txtCodigo").val(resp[0]["Codigo_centro"]);
        $("#txtNombre").val(resp[0]["Nombre"]);
        $("#txtDireccion").val(resp[0]["Direccion"]);
        $("#txtMunicipio").val(resp[0]["Municipio"]);
        $("#txtTipo").val(resp[0]["Tipo_centro"]);
        $("#txtTelefono").val(resp[0]["Telefono"]);
        $("#txtN_acuerdo").val(resp[0]["N_acuerdo"]);
        $("#txtEstatus").val(resp[0]["estatus"]);
        if(resp[0]['pdf_acuerdo'].length > 2){
            $("#pdf_acuerdo").hide();
            $(".form-row").append(`<div>
            <button type="button" class="close cls" aria-label="Cerrar" onclick="cerrar_pdf_centro()">
                <span aria-hidden="true">&times;</span>
            </button>
            <a id="pdf" href="${resp[0]['pdf_acuerdo']}" target="_blank"/>PDF ACUERDO</div>`);
        }
        if(resp[0]['Foto'].length>2){
            $("#txtFoto, #lf").hide();
            $(".form-row").append(`<div>
            <button type="button" class="close cls" aria-label="Cerrar" onclick="cerrar_img_centro()">
                <span aria-hidden="true">&times;</span>
            </button>
            <img src="${resp[0]['Foto']}" class="col-12" id="img_escuela"/></div>`);
        }
        $("#btn_modificar").show();
        $("#btn_guardar").hide();
        let elemento = document.getElementById("arcLogo");
        elemento.className = "centro";
        elemento = document.getElementById("arcacuerdo");
        elemento.className = "centro";
        $("#exampleModal").modal("toggle");
    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus)
    }); 
}
function cerrar_img_centro(){
    $("#img_escuela").parent().remove();
    $("#lf, #txtFoto").show();
}
function mostrar_tabla(resp){
    let centros = JSON.parse(resp);
    console.log(centros);
    let fila = "";
    for (i = 0; i < centros.length; i++) {
        fila = `${fila}<div class="container">
          <div class="row">
            <div class="col-12">
              <div class="card h-100">
                <div class="card-body">
                  <div class="d-flex justify-content-between">
                        <label style="display: inline-block; vertical-align: middle;">
                            <input type="checkbox" class="check" value="${centros[i]['id_centro']}" name="lista_reporte[]" form="formulario_reporte" style="float: left; margin-right: 10px;"/>
                            <h5 class="card-title" style="display: inline-block; margin: 0;">${centros[i]['Nombre']}</h5>
                        </label>
                        <div>
                      <button class="btn btn-primary btn-sm" id="${centros[i]['id_centro']}" onclick='editar_centro(this)'><i class="fas fa-edit"></i></button>
                      <button class="btn btn-danger btn-sm" id="${centros[i]['id_centro']}" onclick='eliminar_centro(this)'><i class="fas fa-trash-alt"></i></button>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col">
                      <div class="image-container" style="height: 200px; overflow: auto;">
                        <img class="col-12" src="${centros[i]['logo']}"/>
                      </div>
                    </div>
                    <div class="col">
                      <p class="card-text">Código: ${centros[i]['Codigo_centro']}</p>
                      <p class="card-text">Tipo de Centro: ${centros[i]['Tipo_centro']}</p>
                      <p class="card-text">Estatus: ${centros[i]['estatus']}</p>
                      <p class="card-text"><a href="${centros[i]['acuerdo']}" target="_blank">Acuerdo</a></p>
                    </div>
                    <div class="col">
                      <p class="card-text">N° Acuerdo: ${centros[i]['N_acuerdo']}</p>
                      <p class="card-text">Municipio: ${centros[i]['centro_municipio']}</p>
                      <p class="card-text">Dirección: ${centros[i]['Direccion']}</p>
                      <p class="card-text">Teléfono: ${centros[i]['Telefono']}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        `;
      }
      
    $("#tbl_centros").html(fila);
}
function cerrar_pdf_centro(){
    $("#pdf").parent().remove();
    $("#pdf_acuerdo").show();
}
