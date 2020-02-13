


<?php
  require_once("../../inc/init.php");
?>
<html>
<head>
<meta charset='utf-8' />
<link href='../packages/core/main.css' rel='stylesheet' />
<link href='../packages-premium/timeline/main.css' rel='stylesheet' />
<link href='../packages-premium/resource-timeline/main.css' rel='stylesheet' />
<script src='../packages/core/main.js'></script>
<script src='../packages/interaction/main.js'></script>
<script src='../packages-premium/timeline/main.js'></script>
<script src='../packages-premium/resource-common/main.js'></script>
<script src='../packages-premium/resource-timeline/main.js'></script>

<style>

  body {
    margin: 0;
    padding: 0;
    font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
    font-size: 14px;
  }

  #calendar {
    max-width: 80vw;
    height : 70vh;
    margin: 50px auto;
  }

</style>
</head>
<body>

  <div class="container-fluid" id='calendar'></div>


  <script>

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'interaction', 'resourceTimeline', 'dayGrid', 'timeGrid' ],
      now: '2020-01-01',
      selectable: true,
      selectHelper: true,
      editable: true,
      aspectRatio: 1.8,
      scrollTime: '00:00',
      header: {
        left: 'today prev,next',
        center: 'title',
        right: 'resourceTimelineMonth'
      },
      defaultView: 'resourceTimelineMonth',
      views: {
        resourceTimelineDay: {
          buttonText: ':15 slots',
          slotDuration: '00:15'
        },
        resourceTimelineTenDay: {
          type: 'resourceTimeline',
          duration: { days: 10 },
          buttonText: '10 days'
        }
      },
      navLinks: true,
      resourceAreaWidth: '14%',
      resourceLabelText: 'Rooms',
      resources: [
        { id: "2", title: 'maison meublé' },
        { id: "58", title: 'manoir wayne'},
        { id: "59", title: 'test'},
   
        // { id: "59", title: 'test', eventColor: 'orange' },
        // { id: "16", title: 'Auditorium D', children: [
        //   { id: 'd1', title: 'Room D1' },
        //   { id: 'd2', title: 'Room D2' }

      ],
      events: function(info, successCallback, failureCallback) {
        //ajax
        $.get("../../events_timeline.php", function(data){
          var json=data;
          var obj=JSON.parse(json);
          //console.log(obj[0].rendering);
          //console.log(obj.length);

          var length = obj.length;
          var newTab= new Array();
          for(var i=0; i<length; i++){
            //console.log(i);
            newTab.push(
                {

                  resourceId:obj[i].idarticle,
                  start:obj[i].start, 
                  end:obj[i].end,  
                  rendering:obj[i].rendering,
                  backgroundColor:obj[i].backgroundColor,
                },
            ) ;
               
            
                  
          }
          //console.log(newTab)
          $('.lds-circle').hide(5000);
          successCallback(
          
         
          newTab
        
        );

      });


    },
      select: function(arg) {
        console.log(
          'select callback',
          arg.startStr,
          arg.endStr,
          arg.resource ? arg.resource.id : '(no resource)'
        );
      },



    });

    calendar.render();
  });

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.13.0/umd/popper.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.min.js"></script>
</body>
</html>
