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

<?php 
	if (isset($_POST['guardar'])) {
		$datos = array(
			"user_login"=>$_POST['nick'],
			"user_pass"=>$_POST['pass'],
			"user_email"=>$_POST['email'],
			"display_name"=>$_POST['nick'],
			"first_name "=>$_POST['nombre'], 
			"last_name"=>$_POST['apellidos'],
			"role"=>"subscriber"
		);
		$user_id = wp_insert_user( $datos ) ; 
		$dep=$_POST['dep'];
		$ubi=$_POST['ubi'];
		$ingr=$_POST['ingr'];
		if ( ! is_wp_error( $user_id ) ) {
			$insert=$wpdb->query("INSERT INTO users VALUES ('$user_id','$ingr','$dep','$ubi')");
		    echo "<script>swal('OK', 'Usuario creado!', 'success'); </script>";
		    
		}
	}
?>
<div class="container">
	<br><br><br><br>
	<div class="row ">
		<h5 class="color_vallas text-center">Solicitud de vacaciones</h5>
	</div>    
    <form method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-12 col-lg-12 col-xm-12">  
            	<div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-user"> Usuario:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input type="text" class="form-control text-center" name="nick" required placeholder="Usuario">
                </div>
                <br>   
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-user"> Nombre:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input type="text" class="form-control text-center" name="nombre" required placeholder="Nombre">
                </div>
                <br>        
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-user"> Apellidos:&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input type="text" class="form-control text-center" name="apellidos" required placeholder="Apellidos">
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-envelope"> Correo:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input type="email" class="form-control text-center" name="email" required placeholder="user@gmail.com">
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-home"> Departamento: &nbsp;</span>
                    <input type="text" class="form-control text-center" name="dep" required placeholder="Departamento">
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-map-marker"> Ubicación:&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input type="text" class="form-control text-center" name="ubi" required placeholder="Ubicación">
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-calendar"> Fecha de ingreso:</span>
                    <input type="date" class="form-control text-center" name="ingr" required placeholder="Selecciona una fecha">
                </div>
                <br> 
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-lock"> Contraseña:&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input type="password" class="form-control text-center" name="pass" required placeholder="Contraseña">
                </div>
                <br> 
		        <div class="row text-center">
		            <button type="submit" class="btn btn-success" name="guardar">Guardar</button>                    
		        </div> 
            </div>
        </div>
    </form>
</div>
<!-- Button trigger modal -->
<a class="btn btn-primary btn-lg" href="#myModal1" data-toggle="modal">Launch demo modal</a>

<!-- Modal -->
<div id="myModal1" class="modal fade" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">×</button>
                    <h4 class="modal-title">My Title in a Modal Window</h4>
            </div>
            <div class="modal-body">This is the body of a modal...</div>
            <div class="modal-footer">This is the footer of a modal...</div>
            </div>
    </div>
</div>


