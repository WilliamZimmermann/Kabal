var Script = function () {

    $.validator.setDefaults({
        submitHandler: function() { document.getElementById("user").submit(); }
    });

    $().ready(function() {

        // validate signup form on keyup and submit
        $("#user").validate({
            rules: {
                password: {
                    required: true,
                    minlength: 6
                },
                confirm_password: {
                    required: true,
                    minlength: 6,
                    equalTo: "#password"
                },
            },
            messages: {
                password: {
                    required: "Por favor, digite uma senha",
                    minlength: "Sua senha precisa ter no mínimo 6 dígitos"
                },
                confirm_password: {
                    required: "Por favor, digite uma senha",
                    minlength: "Sua senha precisa ter no mínimo 6 dígitos",
                    equalTo: "As senhas não coincidem"
                },
            }
        });
    });


}();