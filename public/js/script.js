function checkResa(id,etat) { 

    $.ajax({
        url: '/reservation/checkin',
        type: 'POST',
        data: { 'id': id, 'etat':etat  },
        beforeSend: function(  ) {
            $( "#etat_"+id ).html( 'chargement...' );
          },
        success: function (result) {
            $( "#etat_"+id ).html( result );}
    });  

}


