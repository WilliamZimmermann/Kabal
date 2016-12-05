var Script = function () {

    $.validator.setDefaults({
        submitHandler: function() { document.getElementById("company").submit(); }
    });

    $().ready(function() {

        // validate signup form on keyup and submit
        $("#company").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
            },
            messages: {
                name: {
                    required: "Digite o nome da empresa",
                    minlength: "Nome da empresa muito curto"
                },
            }
        });
    });


}();