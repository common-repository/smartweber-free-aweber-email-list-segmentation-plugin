(function(){

	$ = jQuery;

	$(document).ready(function(){
	//Depends on TubeLoot.Saved videos to be created as a collection (found in
		// app.js );
	var  URLView = Marionette.ItemView.extend({
		template: "#youtube-url-template"
	});

	var YouTubeSearchView = Marionette.ItemView.extend({
		template: "#youtube-search-template"
	});

	var  EmbedView = Marionette.ItemView.extend({
		template: "#embed-template"
	});

	var YouTubeVideo = Backbone.Model.extend({

		defaults: {
			type: 'youtube',
			embed_code: '',
			image_url: '',
			url: '',
			video_id: '',
			products: [],
			settings: {
				video_height: 315,
				video_width: 560,
				autoplay: false,
				show_controls: true,
				allow_sharing: true,
                                background:"#336f9c",
                                background_opacity:"0.43",
                                controls_bg:"#f7b92a",
                                controls_bg_opacity:"0.81",
                                buttons:"#336f9c",
                                buttons_opacity:"0.43",
                                buttons_hover:"#d6d6d6",
                                buttons_hover_opacity:"0.27",
                                buttons_active:"#050000",
                                buttons_active_opacity:"0.81",
                                bar_bg:"#1a1a1a",
                                bar_bg_opacity:"0.31",
                                buffer:"#336f9c",
                                buffer_opacity:"0.43",
                                scrollbar_bg:"#050000",
                                scrollbar_bg_opacity:"0.78",
                                time_text:"#ffffff",
                                fill:"#ffffff",
                                fill_opacity:"0.71",
                                scrollbar:"#ffffff"
			}
		}

	});

	var AvailableVideos = Backbone.Collection.extend({
		model: YouTubeVideo
	});

	var availableVideos = new AvailableVideos;

	var Preview = Marionette.ItemView.extend({
		template: "#video-preview-template"
	});

	TubeLoot.YouTubeView = Marionette.LayoutView.extend({

		initialize: function(){
			var that = this;

			
		},
		
		template: '#youtube-layout-template',

		events : {
			"click #create-video-button" : "createVideo",
			"change #video-type" : "showVideoInfo",
			"click #search-button" : "searchYouTube",
			"keypress #search-box" : "checkForEnter",
			"click .video-item" : "showVideoModal",
			"click .select-video-button" : "saveVideo",
			"click .page-button" : "searchYouTube"

		},

		regions: {

			"videoInfoRegion" : "#video-info-region"
		},

		checkForEnter: function(e){

			 if ( e.which === 13 ) { 
			 	this.searchYouTube(e);
			 }
		},

		createVideo: function(e){

				$("#video-type-selector").show();

				this.showURLView();
                                jQuery('html, body').animate({
                                        scrollTop: jQuery("#main-region").offset().top
                                }, 500);                                    
			
		},

		saveVideo: function(video_index){
			/*	var video_index = $(e.currentTarget).data('video-index');
				var video = this.videos[video_index];
				console.log("TubeLoot.SavedVideos");
				console.log(TubeLoot.SavedVideos);
console.log(this.videos);*/
				


		},

		showVideoInfo: function(e){

			var videoType = $(e.currentTarget).val();

			switch(videoType){

				case "youtube":
					this.showYouTubeSearch();
				break;

				case "embed":
					this.showEmbedView();
				break;

				case "video-url":
					this.showURLView();
				break;
			}
			console.log();
		},

		showURLView: function(){

				var urlView = new URLView();

				this.videoInfoRegion.show(urlView);

		},

		showYouTubeSearch: function(){

			var youtubeSearchView = new YouTubeSearchView();
			this.videoInfoRegion.show(youtubeSearchView);
		},

		showEmbedView: function(){
			var embedView = new EmbedView();
			this.videoInfoRegion.show(embedView);
		},

		searchYouTube: function(e) {
				console.log(e);


				var that = this;
				var keyword = $("#search-box").val();

				if( $.trim(keyword) == ''){

					return;
				}

				$('#video-list').html('');
				$('#ajax-indicator').html('<i class="fa fa-cog fa-spin"></i> Searching...');

				var extra_params = '';

				//Now we want to see if we have pagination tokens
				
				var $button = $(e.currentTarget);
				
				console.clear();

				if($button.hasClass("page-button")){
					var page_token = $button.attr("data-page-token");
					var extra_params = '&page_token='+page_token
					console.log(page_token);
					console.log($button.text());

				}

				var     $next_button = this.$el.find("#next-page-button");
						$next_button.hide();

				var     $prev_button = this.$el.find("#prev-page-button");
						$prev_button.hide();
				$.get(
					ajaxurl + '?action=tubeloot&route=youtube&keyword=' + keyword + extra_params
					).success(function(data) {
						
						var videos = $.parseJSON(data);

						if( videos[0] == null){
							$('#ajax-indicator').html("No videos found!");
						}

						$('#ajax-indicator').html('');

						var page_buttons_set = false; //We haven't set up our page buttons

						

						$(videos).each(function(index, video){

						if(!page_buttons_set){

							if(video.nextPageToken != null){
								$next_button.show();
								$next_button.attr("data-page-token", video.nextPageToken);
							}

							if(video.prevPageToken != null){
								$prev_button.show();
								$prev_button.attr("data-page-token", video.prevPageToken);
							}

							page_buttons_set = true;

						}



						video.index = index;

						var template = _.template($('#video-template').html());
						var html = template(video);

						$('#video-list').append(html);

						});

						availableVideos = new AvailableVideos(videos)
						/*that.videos = [];

						that.videos = videos;*/

						console.log(availableVideos);

					});
			},

			showVideoModal: function(e){

				var that = this;
				var videos = availableVideos; //this.videos;

				var $video_item = $(e.currentTarget);
				var video_index = $video_item.data('video-index');
				
				var video = availableVideos.findWhere({index : video_index});

				console.log(video);

			
				//var template = _.template($('#video-preview-template').html());

				//var html = template(video);

				var preview = new Preview({ 
					model: video,
					el: that.$el.find('#video-preview')
				});

				preview.render();

				this.$el.find('#myModal').foundation('reveal', 'open');

				//$("body").find('#video-preview').html(html);

			}


	});


	$('body').on('click', '.select-video-button', function(e){

				$button = $(e.currentTarget);
				

				

				if($button.hasClass("embed-button")){

					var embed_code = $("#embed-code").val();
					var video_name = $('#video-name').val();

						if($.trim(embed_code) == '' || $.trim(video_name) == '' ){
							return;
						}

					var video = new YouTubeVideo;
					
					video.set({ embed_code : embed_code, name: video_name });
					video.set({ embed : true});

				} else if ($button.hasClass("url-button")){

					var video_url = $("#video-url").val();
					var video_name = $('#video-name').val();
						if($.trim(video_url) == '' || $.trim(video_name) == '' ){
							return;
						}

					var video = new YouTubeVideo;
					
					video.set({ url : video_url, name: video_name });
					video.set({ url_video : true});

				} else {

					var video_index = $button.data("video-index");
					var video = availableVideos.findWhere({index: video_index});

				}

				$button.html('<i class="fa fa-cog fa-spin"></i> Saving...');

				video.url = ajaxurl + "?action=tubeloot&route=save-video";

				video.save(null, {

					success: function(data){
						$button.html('Saved!');
						TubeLoot.SavedVideos.add(video, {at: 0});

						TubeLoot.Routes.navigate('#saved-videos', {trigger: true });

						$("body").find('#myModal').foundation('reveal', 'close');

					}

				});
				


			});

	});

})();