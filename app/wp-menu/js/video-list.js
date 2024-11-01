(function(){

	$ = jQuery;

	$(document).ready(function(){

		var Playlist = Backbone.Model.extend({

			defaults: {
				name : "New playlist",
				videos: []
			}

		});

		var currentPlaylist;

		var Playlists = Backbone.Collection.extend({
			url: ajaxurl + "?action=tubeloot&route=video-list",
			model: Playlist
		});

		var playlists = new Playlists;
                console.log(playlists);

		var PlaylistItemView = Marionette.ItemView.extend({
			template: "#playlist-item-template",

			events: {
				"click .shortcode-link" : "showShortcode",
				"click .remove-video-list" : "removeVideoList"
			},

			showShortcode: function(e){
				
				var $link = $(e.currentTarget);

				var video_list_id = $link.data("video-list-id");

				prompt("Copy your shortcode below: " , '[tubeloot_tab tab="' + video_list_id +'"]');

			},

			removeVideoList: function(e){
				var $link = $(e.currentTarget);
				var video_list_id = $link.data("video-list-id");

				var playlist = playlists.get(video_list_id);

				if(confirm("Are you sure? This action cannot be undone!")){

					playlist.destroy();

				}
			}
		});

		var PlaylistsView = Marionette.CollectionView.extend({
			childView: PlaylistItemView
		});


		var AvailableVideo = Marionette.ItemView.extend({
			template: "#available-video-template",
			tagName: "li",
			onRender: function(){

				this.$el.attr("data-tubeloot-video-id", this.model.get("id"));
			}

		});

		var AvailableVideos = Marionette.CollectionView.extend({

			initialize: function(){
				this.collection = TubeLoot.SavedVideos;
			},

			onRender: function(){
				 this.$el.attr("id", "available-videos-list");
				
			},

			childView: AvailableVideo,
			tagName: "ul"

		});

		var VideoSelector = Marionette.LayoutView.extend({

			initialize: function(){

			},

			template: "#video-selector-template",

			events: {
				"click #save-video-list-button" : "saveVideoList"
			},

			regions: {
				playlistRegion: "#playlist-region",
				availableVideosRegion: "#available-videos-region"
			},

			onRender: function(){
				this.availableVideosRegion.show(new AvailableVideos);

				this.$el.find("#playlist").sortable({
					connectWith: "#available-videos-list"
				});


				this.$el.find("#available-videos-list").sortable({
					connectWith: "#playlist"
				});

			},

			saveVideoList: function(e){
				var playlist_name = $("#video-playlist-name").val();

				if($.trim(playlist_name) == ""){
					alert("Please type in a name for your list.");
					return;
				}


				$button = $(e.currentTarget);

				$button.html('<i class="fa fa-cog fa-spin"></i> Saving...');

				var playlist_videos = Array();
				this.$el.find("#playlist li").each(function(index, value){
					
					var video_id = $(this).data("tubeloot-video-id");
					playlist_videos.push(video_id);

				});

				var playlist = new Playlist({
					name: playlist_name,
					videos: playlist_videos
				});

				playlist.url = ajaxurl + "?action=tubeloot&route=video-list"
				playlist.save(null, {

					success: function(){
						$button.html("Saved!");

						setTimeout(function(){

							TubeLoot.Routes.navigate('#saved-video-list', {trigger: true });
                                                        
						}, 1000);
					}

				});

				playlists.add(playlist);
				console.log(playlist);

			}


		});



		TubeLoot.CreateVideoListLayout = Marionette.LayoutView.extend({

			initialize: function(options){
				var that = this;

//				playlists.fetch({
//
//						success: function(data){
//							
//				
//								//video-selector-main-regionthat.showAllVideoLists();
//							
//						}
//
//					});
			},

			events: {
				"click #create-new-playlist-button" : "showVideoSelector",
				"click #view-all-button" : "showAllVideoLists"
			},
			template: "#video-list-layout-template",

			regions: {
				mainRegion : "#video-selector-main-region"
			},

			onRender: function(){
				

				//	this.showVideoSelector();
				
			},

			showVideoSelector: function(){
				this.mainRegion.show(new VideoSelector);
                                jQuery('html, body').animate({
                                        scrollTop: jQuery("#main-region").offset().top
                                }, 500);                                    
			},

			showAllVideoLists: function(){
					var that = this;

					var playlistsView = new PlaylistsView({
								collection: playlists
							});

					that.mainRegion.show(playlistsView);
					


			}
		});


		//Get a list of videos
		var savedVideos = TubeLoot.SavedVideos;

		console.log(savedVideos);

		//Create new list
		//Add video to list
		//remove video from list






	});

})();