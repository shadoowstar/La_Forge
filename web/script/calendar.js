$(document).ready(function() {
    $.ajax({
        type : 'POST',
        url : pageUrl + "get-event/",
        dataType : 'json',
        success : function (data) {
            console.log(data.events);
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listWeek'
                },
                defaultDate: '2018-02-12',
                navLinks: true, // can click day/week names to navigate views
                editable: true,
                eventLimit: true, // allow "more" link when too many events
                events: data.events
            });
        }
    });

});
