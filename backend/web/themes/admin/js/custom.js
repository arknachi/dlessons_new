$(document).ready(function () {
    $('.dropdown-toggle').dropdown();
    
    $('.datepicker').datepicker({
        dateFormat: 'mm/dd/yy'
    });

    $('.timepicker').timepicker({
        timeFormat: "hh:mm tt"
    });
});