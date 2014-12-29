$(document).ready(function () {
  $("ul").sortable({
    connectWith: "ul",
    forcePlaceholderSize: true,
    placeholder: "placeholder",
    scroll: true,
    zIndex: 9999,
    start: function (event, ui) {
      ui.item.addClass('tilt');
      $('ul').css('min-height', '50px');
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
    
    $('#brand').autocomplete({
      source: JSON.parse($('#allbrands').html()),
      appendTo: "#addalead",
      focus: function( event, ui ) {
            $("#brand").val(ui.item.label);
            return false;  
        },
      select: function (event, ui) {
          event.preventDefault();
        console.log(ui.item.label + ' label');
        $("#brand").val(ui.item.label);
        $("#selectedbrandid").val(ui.item.value); // save selected id to hidden input
    }
    });
    
    
    $('#user').autocomplete({
      source: JSON.parse($('#allsales').html()),
      appendTo: "#addalead",
      focus: function( event, ui ) {
            $("#user").val(ui.item.label);
            return false;  
        },
      select: function (event, ui) {
          event.preventDefault();
        console.log(ui.item.label + ' label');
        $("#user").val(ui.item.label);
        $("#selecteduserid").val(ui.item.value); // save selected id to hidden input
    }
    });
    
    $('#agency').autocomplete({
      source: $('#completePath').text()+ '/ajax/fetchCompanyContacts',
      appendTo: "#addalead",
      focus: function( event, ui ) {
            $("#agency").val(ui.item.label);
            return false;  
        },
      select: function (event, ui) {
          event.preventDefault();
//        console.log(ui.item.label + ' label');
        $("#agency").val(ui.item.label);
        $("#selectedagencyid").val(ui.item.value); // save selected id to hidden input
    }
    });
    
    
    

  
});

var createlead = function() {
    var budget = '';
    $('input[name=Budget]').each(function(){
       budget = budget + $(this).val(); 
    });

    
     $.ajax({
        url: $('#completePath').text()+ '/dashboard/createLead',
        type: "POST",
        data: {                    
            brandid: $("#selectedbrandid").val(),
            contactid: $("#selectedagencyid").val(),
            description: $("#description").val(),
            assignedto: $("#selecteduserid").val(),
            sdate: $("#sdate").val(),
            edate: $("#edate").val(),
            tags: $("#tags").val(),
            budget: budget
        },
        async: false,                
        success: function(data) {
            console.log(data);
            $('#addalead').modal('hide');
        }
    });

    
}


var loadleads = function(id) {
    console.log('aad');
    
    $.ajax({
        url: $('#completePath').text()+ '/ajax/fetchLeadsForStatus',
        type: "POST",
        data: {                    
            id :id
        },
        async: false,                
        success: function(data) {
            console.log(data);
            var template = $('#card').html();
            Mustache.parse(template);   // optionbucket_al, speeds up future uses
            var rendered = Mustache.render(template, JSON.parse(data));
            $('#bucket_' + id).append(rendered);
        }
    });
    

};