(function(){

	$ = jQuery;

	$(document).ready(function(){

		$("body").on("click", "#aweber-connect-button", function(){

			var aweber_auth = $('#aweber-auth').val();

			$.post(
				ajaxurl + "?action=smartweber&route=aweber",

				{
					aweber_auth: aweber_auth
				}

				).done(function(data){

					$(document).find("#aweber-modal").foundation('reveal', 'close');
					console.log(data);
					if($.trim( data ) == "success"){
						alert("You have connected your Aweber account.");
					} else {
						alert("Oops, there is a problem - please try again!");
					}

				});

		});

	});

})();