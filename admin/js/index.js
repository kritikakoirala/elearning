// AOS.init({
// 	easing: 'ease-out-back',
// 	duration: 1000
// });

$(document).ready(function(){

  $('#dataTable').DataTable();

  $('#sidebarCollapse').on('click', function () {
  
    $('#sidebar').toggleClass('active');
  });


  showGraph();
  var months  = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'June', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
   function showGraph(){
       $.ajax({
         url:'data.php',
         method:"get",
         success:function(data){
           var rating = [];
           var course = [];
           var totalStudent = [];
           var studentCourse = [];
           var enroll = [];
           var date = [];
           var enrollCoun = [];
           var courseTitle = []
           console.log(data)
          
           var parsedData = JSON.parse(data)
           
           for(let i = 0; i<parsedData.length; i++){
             rating.push(parsedData[i].Rating)
             course.push(parsedData[i].Course_Title)
            
              totalStudent.push(parsedData[i].Total_Students)
          
             studentCourse.push(parsedData[i].Course)
             
             enroll.push(parsedData[i].TOTAL_PAYMENT)
             let month = months[new Date(parsedData[i].MONTH).getMonth()]
             date.push(month)
            //  enrollCoun.push(parsedData[i].TOTAL)
            //  courseTitle.push(parsedData[i].Title)
             
           }
           var filteredCourse = studentCourse.filter(Boolean)
           

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
                  data: rating
                }
            ],
            
          };
 
           var studentchartdata = {
             labels: filteredCourse,
             datasets: [
                 {
                   
                   backgroundColor: [
                    'rgb(255,99,132)',
                    'rgb(255,205,86)',
                    'rgb(60, 92, 145)',
                    'rgb(90,108,230)',
                    
                    ],
                 
                   hoverBackgroundColor: '#CCCCCC',
                   hoverBorderColor: '#666666',
                  
                   data: totalStudent
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
 
          //  var coursechartdata = {
          //    labels: courseTitle,
          //    datasets: [
          //        {
          //          label: 'Course',
          //          backgroundColor: '#3C5C91',
          //          borderColor: '#3C5C91',
          //          hoverBackgroundColor: '#CCCCCC',
          //          hoverBorderColor: '#666666',
          //          barThickness: 30,
          //          data: enrollCoun
          //        }
          //    ],
             
          //  };
 
           var graphTarget1 = $("#ratingGraph");
           var graphTarget2 = $("#studentGraph");
           var graphTarget3 = $("#enrollGraph");
          //  var graphTarget3 = $("#courseGraph");
   
           var barGraph = new Chart(graphTarget1, {
               type: 'bar',
               data: reviewchartdata,
               options: {
                 legend: {
                   position: 'bottom',
                 },
                 scales: {
                     xAxes: [{
                         ticks: {
                             autoSkip: false,
                             maxRotation: 90,
                             minRotation: 90
                             
                         }
                     }]
                 }
               }
           });
           var barGraph1 = new Chart(graphTarget2, {
            type: 'doughnut',
            data: studentchartdata,
            options:{
              plugins:{
                legend:{
                  title:{
                    padding:130,
                    color:'#f57h9'
                  }
                }
              }
            }
            
            });
 
           var barGraph1 = new Chart(graphTarget3, {
             type: 'bar',
             data: paymentchartdata,
             
           });
 
          //  var bar2Graph = new Chart(graphTarget3, {
          //    type: 'bar',
          //    data: coursechartdata,
          //    options: {
          //      scales: {
          //          xAxes: [{
          //              ticks: {
          //                  autoSkip: false,
          //                  maxRotation: 45,
                           
          //              }
          //          }]
          //      },
          //      responsive: false
          //  }
               
             
          //  });
         }
       })
     
   }
 
});