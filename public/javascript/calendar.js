$(document).ready(function(){

    function countDays(start, end) {
        return Math.abs(moment(start).diff(moment(end), 'd')) + 1;
    }

    const toDisable = $("#disabled-date").data('dates');
    const toEnable = $("#enabled-date").data('dates');
    const dateTable = [];
    const dateEnabledTable = [];

    if(toDisable.length > 0){
        toDisable.forEach(function(event){
            let interval = countDays(event["start_date"], event["end_date"]);
    
            for(i=0;i < interval; i++){
                let start = moment(event["start_date"]);
                start = start.add(i,'days').format('YYYY-MM-DD');
                dateTable.push(start);
            }
        });
    }

    if(toEnable.length > 0){
        toEnable.forEach(function(event){
            let interval = countDays(event["start_date"], event["end_date"]);
    
            for(i=0;i < interval; i++){
                let start = moment(event["start_date"]);
                start = start.add(i,'days').format('YYYY-MM-DD');
                dateEnabledTable.push(start);
            }
        });
    }

    $('#date-picker').dateRangePicker(
        {
            container:'#date-picker',
            separator : ' to ',
            startDate: new Date(),
            
            beforeShowDay: function(t)
            {
                const date = moment(t.toJSON()).format('YYYY-MM-DD');

                var valid = !(dateTable.includes(date)) && (dateEnabledTable.includes(date));  //disable dates
                var _class = '';
                var _tooltip = valid ? '' : 'Date indisponible';
                return [valid,_class,_tooltip];
            },
            setValue: function(s,s1,s2)
            {
                $('#reservation_start_date').val(s1);
                $('#reservation_end_date').val(s2);
            }
           
        }
    );

});


			