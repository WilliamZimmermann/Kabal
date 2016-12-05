var Script = function () {

    $.validator.setDefaults({
        submitHandler: function() { document.getElementById("website").submit(); }
    });

    $().ready(function() {

        // validate signup form on keyup and submit
        $("#page").validate({
            rules: {
                title: {
                    required: true,
                    minlength: 2
                },
                description: {
                    required: true,
                    minlength: 4
                },
            },
            messages: {
                title: {
                    required: "Digite o título da Página",
                    minlength: "O título da página é muito curto."
                },
                description: {
                    required: "Digite uma descrição curta sobre a página como referência.",
                    minlength: "Descrição está muito curta."
                },
            }
        });
    });


}();