<?php
get_header(); ?>
<?php
add_action( 'admin_footer', 'my_action_javascript' ); 
function my_action_javascript() { ?>
	<script type="text/javascript" >
	console.log("cargado");
	jQuery(document).ready(function($) {
		var data = {
			'action': 'my_action',
			'whatever': 1234
		};
		jQuery.post(ajaxurl, data, function(response) {
			alert('Got this from the server: ' + response);
		});
	});
	</script> 
<?php } ?>

<div class="sq-container sq-clearfix">
	<br><br><br><br>    
	<div class="row">
		<div class="color1 col-xs-12 col-sm-3 col-md-3">
            <form class="form-inline">
                
            </form>			            
		</div>
		<div class= "col-xs-12 col-sm-6 col-md-6">
			<center><p class="color_vallas" id="resumen"></p></center>
		</div>
		<div class="clearfix visible-sm-block"></div>
		<div class= "col-xs-12 col-sm-6 col-md-3">
			<p align="right" class="color_vallas" id="relog"></p>
		</div>
	</div>
</div>


<?php get_sidebar(); ?>

