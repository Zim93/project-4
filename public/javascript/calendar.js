$(document).ready(function(){

    function countDays(start, end) {
        return Math.abs(moment(start).diff(moment(end), 'd')) + 1;
    }

    const toDisable = $("#disabled-date").data('dates');
    const dateTable = [];

    toDisable.forEach(function(event){
        let interval = countDays(event["start_date"], event["end_date"]);

        for(i=0;i < interval; i++){
            let start = moment(event["start_date"]);
            start = start.add(i,'days').format('YYYY-MM-DD');
            dateTable.push(start);
        }
    });

    $('#date-picker').dateRangePicker(
        {
            separator : ' to ',
            beforeShowDay: function(t)
            {
                const date = moment(t.toJSON()).format('YYYY-MM-DD');

                var valid = !(dateTable.includes(date));  //disable dates
                var _class = '';
                var _tooltip = valid ? '' : 'Date indisponible';
                return [valid,_class,_tooltip];
            },
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


    //Gestio du formulaires de disponibilités 

    $('.add-range').click(function(e){
        e.preventDefault();
        let nbr = $(this).data('displayed');
        $(this).attr('data-displayed',parseInt(nbr) + 1);


    });

    $('.remove-range').click(function(e){
        e.preventDefault();
        let nbr =  $('.add-range').data('displayed');
        $('.add-range').attr('data-displayed',parseInt(nbr) - 1);


    });

    $('.date-picker').dateRangePicker(
        {
            separator : ' to ',
            beforeShowDay: function(t)
            {
                const date = moment(t.toJSON()).format('YYYY-MM-DD');

                var valid = !(dateTable.includes(date));  //disable dates
                var _class = '';
                var _tooltip = valid ? '' : 'Date indisponible';
                return [valid,_class,_tooltip];
            },
            getValue: function()
            {
                if ($('.reservation_start_date').val() && $('.reservation_end_date').val() ){
                    return $('.reservation_start_date').val() + ' to ' + $('.reservation_end_date').val();
                }
                else
                    return '';
            },
            setValue: function(s,s1,s2)
            {
                $('.reservation_start_date').val(s1);
                $('.reservation_end_date').val(s2);
            }
           
        }
    );


});


			