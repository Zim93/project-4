//Gestion du calandrier
$(document).ready(function(){
    
    //fonction de calcul du nombre de jour du début à la fin
    function countDays(start, end) {
        return Math.abs(moment(start).diff(moment(end), 'd')) + 1;
    }

    //Récupération des dates à activer et désactiver
    const toDisable = $("#disabled-date").data('dates') ? $("#disabled-date").data('dates') : [];
    const toEnable = $("#enabled-date").data('dates') ? $("#enabled-date").data('dates') : [];
    const dateTable = [];
    const dateEnabledTable = [];
   
    if(toDisable.length > 0){
        toDisable.forEach(function(event){
            let interval = countDays(event["start_date"], event["end_date"]);
            //Ajout de toute les data à activer dans un tableau
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
            //Ajout de toute les data à désactiver dans un tableau
            for(i=0;i < interval; i++){
                let start = moment(event["start_date"]);
                start = start.add(i,'days').format('YYYY-MM-DD');
                dateEnabledTable.push(start);
            }
        });
    }
    
    //Affichage du calendrier de recherche
    if($('#date-picker-search').is('div')){
        $('#date-picker-search').dateRangePicker(
            {
                container:'.date-picker-container',
                separator : ' to ',
                startDate: new Date(),
                autoClose: true,
                setValue: function(s,s1,s2)
                {
                    $('#reservation_start_date').val(s1);
                    $('#reservation_end_date').val(s2);
                }
               
            }
        );
    }

    //Affichage du calendrier pour réservation et disponibilité
    if($('#date-picker').is('div')){
        $('#date-picker').dateRangePicker(
            {
                container:'.date-picker-container',
                separator : ' to ',
                startDate: new Date(),
                autoClose: true,
                //Activation et désactivation des dates 
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
    }

    

});


			