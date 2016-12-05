var Script = function () {

    $.validator.setDefaults({
        submitHandler: function() { document.getElementById("website").submit(); }
    });

    $().ready(function() {

        // validate signup form on keyup and submit
        $("#website").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
            },
            messages: {
                name: {
                    required: "Digite o nome do website",
                    minlength: "Nome do website muito curto"
                },
            }
        });
    });


}();