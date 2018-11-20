
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
       <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js"></script>
 <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.6.1/js/bootstrap-datepicker.min.js"></script>
 
 
  
      <script src="../js/custom.js"></script>
	  
  <script>
  $(document).ready(function() {
(function () {
       var result;
       $.ajax({
            url: 'get_end_and_start_date_for_examination_date_and_time.php',
            async: false,  
            method: 'GET',
			 dataType : "JSON",
            success: function(data) {
                result = data;
				//alert(result.start)
				 var  sdate = result.start;
            var    edate = result.end;
                loadDatepicker(sdate,edate);
            },
			 
            error: function(error,text,http){
                alert(error + " " + text + " " + http);
            }
        });
		 function loadDatepicker(sdate,edate){
          $('.input-group.date').datepicker({
            format: 'yyyy-mm-dd',
            autoclose: true,
            keyboardNavigation : true ,
            todayHighlight:true,
            autoclose: true,
            startDate:sdate,
            endDate:edate
          }) 
        }
    })();
  });
  </script>
 

    </body>
</html> 