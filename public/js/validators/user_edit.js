var Script = function () {

    $.validator.setDefaults({
        submitHandler: function() { document.getElementById("user").submit(); }
    });

    $().ready(function() {

        // validate signup form on keyup and submit
        $("#user").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                email: {
                    required: true,
                    email: true
                },
            },
            messages: {
                name: {
                    required: "Digite o nome do website",
                    minlength: "Nome do website muito curto"
                },
                email: "Por favor, digite um endereço de email válido",
            }
        });
    });


}();