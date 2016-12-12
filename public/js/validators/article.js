var Script = function () {

    $.validator.setDefaults({
        submitHandler: function() { document.getElementById("article").submit(); }
    });

    $().ready(function() {

        // validate signup form on keyup and submit
        $("#article").validate({
            rules: {
                title: {
                    required: true,
                    minlength: 2
                },
                description: {
                    required: true,
                    minlength: 4
                },
                author: {
                    required: true,
                    minlength: 2
                },
                publicationDate: {
                    required: true,
                    minlength: 10
                },
            },
            messages: {
                title: {
                    required: "Digite o título do Artigo",
                    minlength: "O título da artigo é muito curto."
                },
                description: {
                    required: "Digite uma descrição curta sobre o artigo como referência.",
                    minlength: "A descrição está muito curta."
                },
                author: {
                    required: "Digite o nome do autor do Artigo.",
                    minlength: "O nome do autor está muito curto."
                },
                publicationDate: {
                    required: "A data de publicação é obrigatória.",
                    minlength: "Data de publicação inválida."
                },
            }
        });
    });


}();