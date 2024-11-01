(function(){

	$ = jQuery;

	$(document).ready(function(){


		TubeLoot.createHelpVideoLayout = Marionette.LayoutView.extend({

			initialize: function(options){

			
			},

			events: {
				"click #create-new-playlist-button" : "showVideoSelector",
				"click #view-all-button" : "showAllVideoLists"
			},
			template: "#video-help-layout-template",

			regions: {
				mainRegion : "#video-help-main-region"
			},

			onRender: function(){
				
                                this.$el.find("#help").youtube_video({
                                playlist: 'PLI-QoAsgPlgwj680HdlCgJYkqOXVLcP5X',
                                on_done_loading: function(videos){
                                    jQuery('html, body').animate({
                                            scrollTop: jQuery("#help").offset().top
                                    }, 500);  
                                }
                            });
                   
			},

			showHelp: function(){
					var that = this;
					that.mainRegion.show("<p>This is a P tag</p>");
					


			}
		});




	});

})();