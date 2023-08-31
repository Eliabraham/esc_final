$(document).ready(function(){
    desactivarautocompletado();
    $("#btn_acceder").on({
        'click':function(){
            datos = get_data('acceso',this);
            data=datos.written;
            request_missing_data(datos['err']);
            $.ajax({
                type: "POST",
                url:  rt_usuarios,
                processData: false,
                contentType: false,
                data: data,
                cache:false,
            }).done(function(resp){
                resp = JSON.parse(resp);
                if(resp[0]==0){$.notify(resp[1], "info");}
                if(resp[0]==1){
                    $("body").html(resp[1]);
                }
            }).fail(function(jqXHR, textStatus){
                validate.error_ajax(jqXHR, textStatus)
            });
        }
    });
});
