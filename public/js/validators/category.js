var Script = function () {

    $.validator.setDefaults({
        submitHandler: function() { document.getElementById("website").submit(); }
    });

    $().ready(function() {

        // validate signup form on keyup and submit
        $("#category").validate({
            rules: {
                title: {
                    required: true,
                    minlength: 2
                },
            },
            messages: {
                title: {
                    required: "Digite o título da Categoria",
                    minlength: "O título da categoria é muito curto."
                },
            }
        });
    });


}();