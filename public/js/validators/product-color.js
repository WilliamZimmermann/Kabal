var Script = function () {

    $.validator.setDefaults({
        submitHandler: function() { document.getElementById("color").submit(); }
    });

    $().ready(function() {

        // validate signup form on keyup and submit
        $("#color").validate({
            rules: {
                name: {
                    required: true,
                    minlength: 2
                },
                hexa:{
                	required: true,
                	minlength: 6
                }
            },
            messages: {
                name: {
                    required: "Digite o nome da cor",
                    minlength: "O nome da cor está muito curto."
                },
                hexa: {
                    required: "Digite o hexadecimal da cor",
                    minlength: "O hexadecimal da cor está muito pequeno."
                },
            }
        });
    });


}();