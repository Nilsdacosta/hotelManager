
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
    max-width: 900px;
    margin: 50px auto;
  }

</style>
</head>
<body>

  <div id='calendar'></div>


  <script>

  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
      plugins: [ 'interaction', 'resourceTimeline' ],
      now: '2019-08-07',
      selectable: true,
      selectHelper: true,
      editable: true,
      aspectRatio: 1.8,
      scrollTime: '00:00',
      header: {
        left: 'today prev,next',
        center: 'title',
        right: 'resourceTimelineDay,resourceTimelineTenDay,resourceTimelineMonth,resourceTimelineYear'
      },
      defaultView: 'resourceTimelineDay',
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
      resourceAreaWidth: '25%',
      resourceLabelText: 'Rooms',
      resources: [
        { id: 'a', title: 'Auditorium A' },
        { id: 'b', title: 'Auditorium B', eventColor: 'green' },
        { id: 'c', title: 'Auditorium C', eventColor: 'orange' },
        { id: 'd', title: 'Auditorium D', children: [
          { id: 'd1', title: 'Room D1' },
          { id: 'd2', title: 'Room D2' }
        ] },
        { id: 'e', title: 'Auditorium E' },
        { id: 'f', title: 'Auditorium F', eventColor: 'red' },
        { id: 'g', title: 'Auditorium G' },
        { id: 'h', title: 'Auditorium H' },
        { id: 'i', title: 'Auditorium I' },
        { id: 'j', title: 'Auditorium J' },
        { id: 'k', title: 'Auditorium K' },
        { id: 'l', title: 'Auditorium L' },
        { id: 'm', title: 'Auditorium M' },
        { id: 'n', title: 'Auditorium N' },
        { id: 'o', title: 'Auditorium O' },
        { id: 'p', title: 'Auditorium P' },
        { id: 'q', title: 'Auditorium Q' },
        { id: 'r', title: 'Auditorium R' },
        { id: 's', title: 'Auditorium S' },
        { id: 't', title: 'Auditorium T' },
        { id: 'u', title: 'Auditorium U' },
        { id: 'v', title: 'Auditorium V' },
        { id: 'w', title: 'Auditorium W' },
        { id: 'x', title: 'Auditorium X' },
        { id: 'y', title: 'Auditorium Y' },
        { id: 'z', title: 'Auditorium Z' }
      ],
      events: function(info, successCallback, failureCallback) {
        //ajax
        $.get("events_timeline.php", function(data){
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
                  start:obj[i].start, end:obj[i].end,  rendering:obj[i].rendering,backgroundColor:obj[i].backgroundColor,
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

</body>
</html>
