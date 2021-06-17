AOS.init({
	easing: 'ease-out-back',
	duration: 1000
});

$(document).ready(function(){

  // Date time picker for tutor schedules

  $( "#datepicker" ).datepicker({
    onSelect: function (selectedDate) {
    $date = selectedDate
  }});
 
  $('.start_timepicker').timepicker({
    timeFormat: 'h:mm p',
    zindex:9999,
    scrollbar: true,
    interval: 60,  
    minTime: '7',
    maxTime: '9:00pm',
    change: function(time) {
      var element = $(this);
      var timepicker = element.timepicker();
      $starttime =  timepicker.format(time)
      
  }
    
  });

  $('.end_timepicker').timepicker({
    timeFormat: 'h:mm p',
    zindex:9999,
    scrollbar: true,
    interval: 60,  
    minTime: '7',
    maxTime: '9:00pm',
    change: function(time) {
      var element = $(this);
      var timepicker = element.timepicker();
      $endtime =  timepicker.format(time)
      
    }
    
  });

  $('#addSchedule').on("submit", function(){
    $('#datepicker').val($date)
    $('#start').val($starttime)
    $('#end').val($endtime) 
  })

  // initializing data table
  $('#dataTable').DataTable();


  // rating
  $('.rating .fa-star').click(function(){
    
    if($(this).hasClass('checked')) {
        $(this).toggleClass('checked');
        $(this).prevAll('.fa-star').addClass('checked');
        $(this).nextAll('.fa-star').removeClass('checked');
    }
    else
    {
        $(this).toggleClass('checked');
        $(this).prevAll('.fa-star').addClass('checked');
    }
    $("#hdnRateNumber").val($('.checked').length);        
 
  });

  $('#classModal').on('show.bs.modal', function (event) {
    var button = $(event.relatedTarget) // Button that triggered the modal
    var studentEmail = button.data('value')
    var bookingId = button.data('id')
    var modal = $(this)
    modal.find('.modal-body .studentEmail').val(studentEmail)
    modal.find('.modal-body .bookingId').val(bookingId)
 
  })


  // filter courses

  $('#price_range').slider({
    range:true,
    min:5,
    max:500,
    values:[0, 5000],
    step:5,
    stop:function(event, ui)
    {
        $('#price_show').html(ui.values[0] + ' - ' + ui.values[1]);
        $('#hidden_minimum_price').val(ui.values[0]);
        $('#hidden_maximum_price').val(ui.values[1]);
        filter_data();
    }
});

  $('.common_selector').click(function(){
    console.log('cliked')
    filter_data();
  });

  $('#sortByTitle').on('change', function(){
    filter_data();
   
 });


  function filter_data(){
    var action = "fetch_data";
    
    var categories = get_filter("categories");
    var titleSort = $("#sortByTitle").val();
    var min_price = $('#hidden_minimum_price').val();
    var max_price = $('#hidden_maximum_price').val();
  
    $.ajax({
      url:"fetch_data.php",
      method:"POST",
      data:{action:action, categories:categories, titleSort:titleSort,min_price:min_price, max_price:max_price},
      success:function(data){
        $('.filter_data').html(data);
      }
    });
  }

  function get_filter(class_name){
    var filter = [];
    $('.'+class_name+':checked').each(function(){
        filter.push($(this).val());
    });
    console.log(filter)
    return filter;      
  }

  // dashboard graphs

  showGraph();
  var months  = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
  function showGraph(){
      $.ajax({
        url:'data.php',
        method:"get",
        success:function(data){
          var rating = [];
          var course = [];
          var enroll = [];
          var date = [];
          var enrollCoun = [];
          var courseTitle = []
          // console.log(data)
          var parsedData = JSON.parse(data)
          
          for(let i = 0; i<parsedData.length; i++){
            rating.push(parsedData[i].Rating)
            course.push(parsedData[i].Course_Title)
            enroll.push(parsedData[i].TOTAL_PAYMENT)
            let month = months[new Date(parsedData[i].MONTH).getMonth()]
            date.push(month)
            enrollCoun.push(parsedData[i].TOTAL)
            courseTitle.push(parsedData[i].Title)
            
          }

          var reviewchartdata = {
            labels: course,
            datasets: [
                {
                  label: 'Course',
                  backgroundColor: '#3C5C91',
                  borderColor: '#3C5C91',
                  hoverBackgroundColor: '#CCCCCC',
                  hoverBorderColor: '#666666',
                  barThickness: 30,
                  barPercentage: 1,
                  data: rating
                }
            ],
            
          };

          var paymentchartdata = {
            labels: date,
            datasets: [
                {
                  label: 'Payment Month',
                  
                  backgroundColor: '#3C5C91',
                  borderColor: '#3C5C91',
                  hoverBackgroundColor: '#CCCCCC',
                  hoverBorderColor: '#666666',
                  barThickness: 30,
                  data: enroll
                }
            ],
          };

          var coursechartdata = {
            labels: courseTitle,
            datasets: [
                {
                  label: 'Course',
                  backgroundColor: '#3C5C91',
                  borderColor: '#3C5C91',
                  hoverBackgroundColor: '#CCCCCC',
                  hoverBorderColor: '#666666',
                  barThickness: 30,
                  data: enrollCoun
                }
            ],
            
          };

          var graphTarget1 = $("#ratingGraph");
          var graphTarget2 = $("#enrollGraph");
          var graphTarget3 = $("#courseGraph");
  
          var barGraph = new Chart(graphTarget1, {
              type: 'bar',
              data: reviewchartdata,
              options: {
                scales: {
                  xAxes: [{
                    ticks: {
                      autoSkip: false,
                     
                      minRotation:45
                    }
                  }]
                }
              }
          });

          var bar1Graph = new Chart(graphTarget2, {
            type: 'bar',
            data: paymentchartdata
          });

          var bar2Graph = new Chart(graphTarget3, {
            type: 'bar',
            data: coursechartdata,
            options: {
              scales: {
                  xAxes: [{
                      ticks: {
                          autoSkip: false,
                          maxRotation: 45,
                          
                      }
                  }]
              },
              responsive: false
          }
              
            
          });
        }
      })
    
  }


  // initialize alert
  $('.alert').alert();

  // mobile functions
	$('.mobile').hide();
  $(".toggler").click(function(){
    $('.mobile').toggle("slow");
  });

});


