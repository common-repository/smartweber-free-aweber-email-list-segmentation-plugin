(function(){

	$ = jQuery;

$(document).ready(function(){
	//Here we have our Marionetter router
	SmartWeber.Router = Marionette.AppRouter.extend({

		appRoutes: {

			'aweber' : 'aweber'
		}

	});

	var API = {

		aweber: function () {
			console.log("connecting aweber");
		}            
	}

	SmartWeber.on("start", function(){

		SmartWeber.Routes = new SmartWeber.Router({
			controller: API
		});

		SmartWeber.MainLayout.render();
		if(Backbone.history){
			Backbone.history.start();
		}

	});

	SmartWeber.start();

	});

})();