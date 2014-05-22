


function get_password() {
    var pass = $.post(
            base_url + '/obtener_password',
            {"_token": $(document).find('input[name=_token]').val()}
    );
    pass.done(function(data) {
        $('#Usarpass').empty().append(data['password']);
    });
}

function usar_password() {
    var pass = $('#Usarpass').text();
    $("#Password").val(pass);
    $("#Password_confirmation").val(pass);
}

function comprobar_dominio(dominio,callback) {
    var result = $.post(
            base_url + '/dominio/comprobar',
            {"_token": $(document).find('input[name=_token]').val(),"dominio": dominio}
    );
    result.done(function(data){
        callback(data);
    });
}