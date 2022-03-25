$(document).ready(function(){

    $('#date-picker').dateRangePicker( 
        {
            separator : ' to ',
            getValue: function()
            {
                if ($('#reservation_start_date').val() && $('#reservation_end_date').val() ){
                    return $('#reservation_start_date').val() + ' to ' + $('#reservation_end_date').val();
                }
                else
                    return '';
            },
            setValue: function(s,s1,s2)
            {
                $('#reservation_start_date').val(s1);
                $('#reservation_end_date').val(s2);
            }
        }
    );
});


			