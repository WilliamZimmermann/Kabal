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
                password: {
                    required: true,
                    minlength: 5
                },
                confirm_password: {
                    required: true,
                    minlength: 6,
                    equalTo: "#password"
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
                password: {
                    required: "Por favor, digite uma senha",
                    minlength: "Sua senha precisa ter no mínimo 6 dígitos"
                },
                confirm_password: {
                    required: "Por favor, digite uma senha",
                    minlength: "Sua senha precisa ter no mínimo 6 dígitos",
                    equalTo: "As senhas não coincidem"
                },
                email: "Por favor, digite um endereço de email válido",
            }
        });
    });


}();