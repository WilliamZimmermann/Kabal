//date picker start

    if (top.location != location) {
        top.location.href = document.location.href ;
    }

//datetime picker start

$(".form_datetime").datetimepicker({
	language: 'pt',
	format: 'dd/mm/yyyy hh:ii',
    autoclose: true,
    todayBtn: true,
    pickerPosition: "bottom-left"
});

