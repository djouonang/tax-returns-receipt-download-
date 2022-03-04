jQuery(document).ready(function($) {
        jQuery(".clickme").click( function() {
  
          event.preventDefault();
           var id = $(this).attr("data-id");
           var email =$(this).attr("data-email");
          var date =$(this).attr("data-date");
         

        var data = {
        
          action: 'download_file', // here php function 
          'id' : id,			
          'email' : email,
		  'date' : date,
       
    };
   
    jQuery.post(account_script_checker.ajaxurl, data, function(response) {
     

    });
                       

});
});