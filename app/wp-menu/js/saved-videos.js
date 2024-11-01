(function(){

	TubeLoot.SingleVideoView = Marionette.ItemView.extend({
		template: "#single-video-item-view",
		className: "row  colour-row",

		events: {

			"click .remove-video" : "removeVideo",
                        "click .name" : "jeditable"
                        
		},
                jeditable: function(e){
                    $model = this.model;
                    $(e.currentTarget).editable('admin-ajax.php?action=tubeloot&route=edit-video', {
                         callback: function(value){ 
                             $model['attributes']['name'] = value;
                             $('#myModal').foundation('reveal', 'open');
                             setTimeout(function(){ $('#myModal').foundation('reveal', 'close'); }, 1500);
                         }  
                    });
                    
                    
                },
		removeVideo: function(){

			if(confirm("Are you sure you want to delete this video? This action cannot be undone!")){


			this.model.url = ajaxurl + "?action=tubeloot&route=delete_video/" + this.model.get("id");
			this.model.destroy();

			}
		}


	});

	var NoVideos = Marionette.ItemView.extend({
		template: '#no-videos-added-template'
	});

	TubeLoot.VideoListLayout = Marionette.LayoutView.extend({
		initialize: function(){
			var that = this;
                        jQuery('html, body').animate({
                                scrollTop: jQuery("#main-region").offset().top
                        }, 500);    
			
		},
		template: "#video-list-template",
		regions: {
			"videoListRegion" : "#video-list-region"
		},

		events: {
			"click .shortcode-link" : "showShortcode"
		},

		onRender: function(){

			var videoListView = new TubeLoot.SavedVideosView({
				collection: TubeLoot.SavedVideos
			});

			this.videoListRegion.show(videoListView);
		},

		showShortcode: function(e){

			var video_id = $(e.currentTarget).data('video-id');

			prompt("Copy your shortcode:", '[tubeloot video="' + video_id + '"]');

		}
	});

	TubeLoot.SavedVideosView = Marionette.CollectionView.extend({

		 appendHtml: function(collectionView, itemView){
    		collectionView.$el.prepend(itemView.el);
  		},
		childView: TubeLoot.SingleVideoView,

		emptyView: NoVideos

	});






})();