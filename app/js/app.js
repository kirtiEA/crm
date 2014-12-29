$(document).ready(function () {
  $("ul").sortable({
    connectWith: "ul",
    forcePlaceholderSize: true,
    placeholder: "placeholder",
    scroll: true,
    zIndex: 9999,
    start: function (event, ui) {
      ui.item.addClass('tilt');
    },
    stop: function (event, ui) {
      ui.item.removeClass('tilt');
    }
  });
  /* This is first way to do it where you show a button on clicking add content */
  // $(".addContent").click(function(event) {
  //   /* Act on the event */
  //   $(".addContent").addClass("hide");
  //   $("#addnewbtn").addClass("show").removeClass('hide');
  // });
  /* This is second way to do it where you instantly show a text area.Uncomment next line if that is required behavior. */
  $(".addContent").on('click', function(){
    $(".addContent").siblings("ul").find("li:last-child").after('<li class="placeholder"><textarea class="form-control" rows="3" placeholder="Description"></textarea></li>');
  });
  
  
  
   // start and end date behavior    
    $("#sdate").datepicker({
        //changeMonth: true,
        numberOfMonths: 1,
        minDate: 0,
        dateFormat: 'dd M yy',
        onClose: function (selectedDate) {
            if (selectedDate)
                $("#edate").datepicker("option", "minDate", selectedDate);
        }
    });
    $("#edate").datepicker({
        minDate: 0,
        //changeMonth: true,
        numberOfMonths: 1,
        dateFormat: 'dd M yy',
        onClose: function (selectedDate) {
            $("#sdate").datepicker("option", "maxDate", selectedDate);
        }
    });

  
});
