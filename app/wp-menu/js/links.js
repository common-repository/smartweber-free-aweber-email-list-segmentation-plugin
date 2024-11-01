(function(){

	console.log("Introducing links");

	var links = [{

		id: 1,
		name : "ActionPHP",
		url: "http://actionphp.com"

	},

	{

		id: 2,
		name : "WPDevelopers.net",
		url: "http://wpdevelopers.net"

	},

	{

		id: 3,
		name : "PHPClan",
		url: "http://phpclan.com"

	}];
	
	$ = jQuery;

	$(document).ready(function() {

		var SmartLink = Backbone.Model.extend({});

		SmartWeber.Links = Backbone.Collection.extend({
			url: ajaxurl + "?action=smartweber&route=links"
		});

		var links_list;

		var LinkView = Marionette.ItemView.extend({
			template: "#link-template"
		});

		var LinksView = Marionette.CollectionView.extend({
			childView: LinkView
		});

		var LinksLayout = Marionette.LayoutView.extend({
			template: "#links-layout",

			regions: {

				"linksListRegion" : "#links-list-region"

			},

			events: {
				"click #create-link-button" : "createLink"				
			},

			onRender: function(){

				var that = this;

				links_list = new SmartWeber.Links;
				links_list.fetch({
					success: function (data) {
						that.linksListRegion.show(new LinksView({
					collection: links_list
				}));
				
					}
				})
				
			},

			createLink: function(e) {
				// Show modal
				this.$el.find("#link-modal").foundation('reveal', 'open');
			}
		});

		SmartWeber.MainLayout.linksRegion.show(new LinksLayout);

			$("body").on("click", "#add-link-button", function(e){

				var name = $('#link-name').val();
				var url = $("#link-url").val();
				var list_id = $('#list-id').find(":selected").val();
				//var list_action = $('input[name=list-action]:radio:checked').val();

				if( $.trim(name) == "" || $.trim(url) == ""){
					return;
				}

				var link = new SmartLink({
					name: name,
					url: url,
					list_id: list_id
				});

				link.url = ajaxurl + "?action=smartweber&route=create";
				link.save(null, {
					success: function(data){
						links_list.add(link);
					}
				});

				

				$(document).find("#link-modal").foundation('reveal', 'close');

			});


	});

	
})();