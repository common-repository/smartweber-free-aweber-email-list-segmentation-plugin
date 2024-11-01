<div class="row" >
	<h1>SmartWeber</h1>
<p><strong>If you have ANY issues or questions please send an email to: projects@wpdevelopers.net</strong></p>
	<?php 
	include_once RAPID_APP_PATH . "includes/class-aweber.php";
	//Let's initialize Aweber

	$accessKey = get_option('smartweber_aweber_accessKey');

	if($accessKey){
						$aweber = new PHPClan_Aweber;
						$aweber->consumerKey =get_option('smartweber_aweber_consumerKey');// "AzaV24fEx4IfPesTcT2lr8Sp";
						$aweber->consumerSecret =get_option('smartweber_aweber_consumerSecret');// "aoUn97h1xCWeLKHkTuAKeO94rHA5S8z3o9wFWLzH";
						$aweber->accessKey = get_option('smartweber_aweber_accessKey');//"AgUttDoW0XYgcNkRfI369nJs";
						$aweber->accessSecret = get_option('smartweber_aweber_accessSecret');//"MEjkvQuZsppA4zI7xwHXV5wcQXrmBMqvPMzyxZcJ";
						$aweber->aweber();

						$lists = $aweber->lists();
	}						
						?>
</div>
<div class="row">

	<a href="#aweber" data-reveal-id="aweber-modal">Connect your Aweber account</a>
	
		<div id="aweber-modal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
		  <h2 id="modalTitle">Connect your Aweber Account</h2>

		  <div class="row" ><p><a href="http://easystart.aweber.com" target="_blank">Get an Aweber account</a></p></div>
		  
		  <div><a href="https://auth.aweber.com/1.0/oauth/authorize_app/2f9d505a" target="_blank">Get your Aweber authorization code here</a></div>
		  
		  <div class="row" >
		  	<p><strong>Paste your Aweber authorization code below:</strong></p>
		  <textarea id="aweber-auth"></textarea>
		  </div>
		  
		  <div class="row" >
		  	<button id="aweber-connect-button" >Connect Aweber</button>
		  </div>
		  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
		</div>

</div>
<div class="row" >
	<h2>Your links</h2>

<p><em>Copy and paste your links into your emails. Whenever a subscriber clicks on your link, they will be copied to a the list you specify.</em>

	<div id="main-view" ></div>
</div>

<div class="row" ></div>

<script type="text/template" id="main-layout">
	<div id="links-region" ></div>	
</script>

<script type="text/template" id="links-layout">
		<button class="success tiny" id="create-link-button"><i class="fa fa-plus" ></i> Create new link</button>
		<div id="links-list-region" ></div>

		<div id="link-modal" class="reveal-modal" data-reveal aria-labelledby="modalTitle" aria-hidden="true" role="dialog">
  <h2 id="modalTitle">Add a link</h2>
  <div class="row" >
  	Name <input type="text" name="link-name" id="link-name"/>
  </div>
   <div class="row" >
  	URL <input type="text" name="link-url" id="link-url"/>
  </div>

 <!-- <div class="row">
  	<input type="radio" name="list-action" value="move" checked="checked" /> Move 
  	<input type="radio" name="list-action" value="copy" /> Copy
  </div> -->

  <div class="row" >
  	<span>Copy to:</span>

  	<select	name="list-id" id="list-id" >
  	<?php 
  		
  		if($lists){
			
			foreach ($lists as $list) {
		
		?>

		<option value="<?php echo $list->list_id; ?>" ><?php echo $list->name; ?></option>

  			<?php


			}
  		}

  	?>
  	</select>
  </div>
  <div class="row" >
  	<button id="add-link-button" >Add link</button>
  </div>
  <a class="close-reveal-modal" aria-label="Close">&#215;</a>
</div>
</script>

<script type="text/template" id="link-template">
	<a target="_blank" href="<%- url %> " ><%- name %></a> <input type="text" class="regular-text" readonly="readonly" value="<?php echo site_url("?_c_=<%- code %>"); ?>&_e_={!email}" />
</script>

<script type="text/template"></script>
<script type="text/template"></script>
<script type="text/template"></script>
<script type="text/template"></script>

	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.2/js/vendor/modernizr.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/foundation/5.5.2/js/foundation.min.js"></script>
	<script type="text/javascript" src="<?php echo  plugin_dir_url( __FILE__ ) ;?>js/app.js"></script>
	<script type="text/javascript" src="<?php echo  plugin_dir_url( __FILE__ ) ;?>js/main.js"></script>
	<script type="text/javascript" src="<?php echo  plugin_dir_url( __FILE__ ) ;?>js/router.js"></script>
	<script type="text/javascript" src="<?php echo  plugin_dir_url( __FILE__ ) ;?>js/links.js"></script>
	<script type="text/javascript" src="<?php echo  plugin_dir_url( __FILE__ ) ;?>js/script.js"></script>
	<script type="text/javascript">
		(function(){

			  $ = jQuery;

			  $(document).foundation();

		})();
</script>

