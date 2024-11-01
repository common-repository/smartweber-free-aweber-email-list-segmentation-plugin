(function(){


	$ = jQuery;

	
	$(document).ready(function(){

		var MainLayout = Marionette.LayoutView.extend({

			el: '#main-view',
			template: '#main-layout',

			regions: {

				"mainRegion": "#main-region",
				"linksRegion": "#links-region"
			}
			
		});

		SmartWeber.MainLayout = new MainLayout;

	});



})();