//   
//#################################################################################
//Simple File Manager PHP Script v1.5.													
//Created By Dave Earley.															
//Dave-Earley.com																	
//3rd April 10																	
//Do whatever you wish with the script (but please leave this notice here)		
//#################################################################################

   $(document).ready(function() {
		$("#manage_table").tablesorter(); 
        $('#upload_box').hide();
        $('#upload_button').click(function() {

            $('#upload_button').fadeOut('fast', function() {

                $('#upload_box').fadeIn('fast');

            });


        });


        $('#close_upload').click(function() {

            $('#upload_box').fadeOut('fast', function() {

                $('#upload_button').fadeIn('fast');
            });
            return false;


        });



        $('#folder_box').hide();
        $('#folder_button').click(function() {

            $('#folder_button').fadeOut('fast', function() {

                $('#folder_box').fadeIn('fast');

            });


        });


        $('#close_folder').click(function() {

            $('#folder_box').fadeOut('fast', function() {

                $('#folder_button').fadeIn('fast');
            });
            return false;


        });
		
		$(".close").click(
					function () {
						$(this).fadeTo(400, 0, function () { // Links with the class "close" will close parent
							$(this).slideUp(400);
						});
					return false;
					}
				);


    });