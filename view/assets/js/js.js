var rt_usuarios       ="controller/user_controller.php";
var rt_docentes       ="controller/docentes_controller.php";
var rt_centros        ="controller/centros_controller.php";
var rt_parte_mensual  ="controller/pmensual_controller.php";
var rt_operaciones    ="controller/operaciones_controller.php";
var rt_direcciones    ="controller/direcciones_controller.php";
function identidad(){
    $.ajax({
        type: "POST",
        url: rt_usuarios,
        data: {gv_action:"Mostrar_identidad"},
        cache: false,
    }).done(function(resp) {
        console.log(resp);
        $("#identidad").html(resp);
    }).fail(function(jqXHR, textStatus) {
        validate.error_ajax(jqXHR, textStatus);
    });
}
function desactivarautocompletado() {
    var inputs = $('.form-control');
    for (var i = 0; i < inputs.length; i++) {
      inputs[i].setAttribute('autocomplete', 'off');
    }
}
document.onkeydown = function(e){
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 116){
        return false;
    }
}
window.onbeforeunload = function() {
    return false;
};
function error_ajax(jqXHR, textStatus){
    if (jqXHR.status === 0) {alert(`Conexión fallida: error en la red (0)`);}
    else if (jqXHR.status == 404) {alert(`controlador o modelo no encontrado (404)`);}
    else if (jqXHR.status == 500) {alert('Error 500 de servidor');}
    else if (textStatus === 'parsererror') {alert(`falla en el parset jsom`);}
    else if (textStatus === 'timeout') {alert(`tiempo maximo excedido`);}
    else if (textStatus === 'abort') {alert('ajax abotado');}
    else{alert('error desconocido:\n' + jqXHR.responseText);}
}
function mostrar_menu(){
    $.ajax({
        type: "POST",
        url:  rt_usuarios,
        data: {gv_action:'volver_menu'},
        cache:false,
    }).done(function(resp){
        $("body").html(resp);
    }).fail(function(jqXHR, textStatus){
        validate.error_ajax(jqXHR, textStatus)
    });    
}
function solo_numero(n){
    key = n.keyCode || n.which;
    if(((key<48) || (key>57))&&(key!=44)){
        $.notify("este campo solo acepta valores numericos", "info");
        return false;
    }
}
function solo_letra(e){
    key = e.keyCode || e.which;
    if((key!=32)&&(key<65 || key>90)&&(key<97 || key>122)&&(key!=209)&&(key!=241)&&(key!=225)&&(key!=223)&&(key!=237)&&(key!=243)&&(key!=250)&&(key!=193)&&(key!=201)&&(key!=205)&&(key!=211)&&(key!=218)&&(key!=46)&&(key!=44))
    {
        $.notify("este campo solo acepta letras", "info");
        return false;
    }
}
function agregarImagen(a,b) {
    //let input = document.getElementById('imagen');
    let input = a;
    let file = input.files[0];
    let reader = new FileReader();
    reader.onload = function(e) {
        $(`#${b}`).html(`<img src="${e.target.result}" class="form-control"/>`);
    };
    reader.readAsDataURL(file);
}
function formato_dni(a){
    let b="";
    j=0; c=0;
    for(i=0;i<a.length;i++){
        if(a[i]!="-"){
            j++;
            if(j%4==0 && c<2)
            {b=`${b}${a[i]}-`; c++;}
            else
            {b=`${b}${a[i]}`;}
        }
    }
    return b;
}
function get_data(campos,a){
    let valores=new FormData();
    if(a){valores.append("gv_action" , $(a).val());}
    let faltantes=[];
    let collection = $(`.${campos}`);
    $.each(collection, function (ii, componente){
        let req = "no";
        if($(componente).hasClass('required')){req = "si";}
        if ($(componente).prop(`id`).substring(0,3)=="txt"){
            if($(componente).val().trim()!=""){
                valores.append($(componente).prop(`id`).substring(3),$(componente).val().trim());
            }else{
                if(req=="si"){faltantes.push($(componente).prop(`name`));}    
            }
        }
        if ($(componente).prop('id').substring(0, 3) == "arc") {
            valores.append($(componente).prop('id').substring(3), $(componente)[0].files[0]);
            if (req == "si" && $(componente)[0].files.length == 0) {faltantes.push($(componente).prop('name'));}
        }          
        if ($(componente).prop(`id`).substring(0,3)=="chk"){
            if($(componente).prop('checked')){
                if (valores[$(con).prop("id").substring(3)]!=undefined){
                    valores[$(con).prop("id").substring(3)]+=`:::${$(con).val()}`
                }else{
                    valores[$(con).prop("id").substring(3)]=$(con).val();
                }
            }
        }
    });
    return {written:valores, err:faltantes};
}
function request_missing_data(faltantes){
    if(faltantes!=""){
        $.notify(`Debe ingresar los siguientes campos:\n ${faltantes.join('\n')}`, `warn`);
        throw `ejecución detenida por datos vacios`;
    }
}
function for_ema(a){
    if(a.value.length>0){
        c=0; b=a.value.split("");
        for(i=0;i<b.length;i++){if(b[i]=="@"){c++;}}
        if((b[b.length-3]==".")||(b[b.length-4]==".")){c++;}
        if(c!=2){
            $.notify("EL CORREO INGRESADO NO TIENE UN FORMATO CORRECTO", "info");
            a.focus();
            throw `ejecución detenida por datos vacios`;
        }else{
            a.value=a.value.toLowerCase();
        }
    }
}
function mayini(e){//mayuscula inicial de cada palabra en un texto
    a=e.split("");
    t="";
    for(i=0;i<a.length;i++){
        if((i==0)||(a[i-1]==" ")){t+=(a[i]).toUpperCase();}
        else{t+=(a[i]).toLowerCase();}
    }
    return t;
}
