
<?php 
    get_header(); 
    if (isset($_POST['guardar'])) {
        nuevo_usuario();
    }
    global $wpdb;
    $paises = $wpdb->get_results('SELECT * FROM paises ORDER BY nombre');
    $departamentos = $wpdb->get_results('SELECT * FROM departamentos ORDER BY nombre');
    $usuarios = $wpdb->get_results( "SELECT w.ID AS id_user,u.departamento AS departamento,u.area AS area FROM wp_users AS w, users AS u WHERE w.ID=u.id_user");
    $empresas = $wpdb->get_results("SELECT * FROM empresas");
?>
<div class="container">
	<br><br><br><br>
	<div class="row ">
		<h5 class="color_vallas text-center">Nuevo usuario</h5>
	</div>    
    <form method="POST" enctype="multipart/form-data">
        <div class="row">
            <div class="col-md-6 col-lg-6 col-xm-12">  
            	<div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-user"> Numero de empleado:</span>
                    <input type="number" class="form-control" name="user" required placeholder="Numero de empleado">
                </div>
                <br>   
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-user"> Nombre:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input type="text" class="form-control" name="nombre" required placeholder="Nombre">
                </div>
                <br>        
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-user"> Apellidos:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input type="text" class="form-control" name="apellidos" required placeholder="Apellidos">
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-envelope"> Correo:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input type="email" class="form-control" name="email" required placeholder="user@gpovallas.com">
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-home"> Departamento:&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <select class="form-control" name="dep" onchange="change_departamento(this.value);" required>
                         <option value="">Selecciona un departamento</option>
                        <?php foreach ($departamentos as $departamento) { ?>
                            <option class="text-center" value="<?= $departamento->id_departamento ?>"><?= $departamento->nombre ?></option>
                        <?php } ?>
                    </select>
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-home"> Área:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <select class="form-control" id="select_area" name="area" onchange="change_area(this.value);" required>
                        <option value="">Selecciona área</option>
                    </select>
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-user"> Jefe inmediato:&nbsp;&nbsp;&nbsp;</span>
                    <select class="form-control" id="select_jefe" name="jefe">
                        <option class="text-center" value="">Selecciona el Jefe inmediato</option>
                        <?php foreach ($usuarios as $usuario) { ?>
                            <?php $user_info = get_userdata($usuario->id_user); ?>
                            <option value="<?= $usuario->id_user ?>"><?= $user_info->first_name." ".$user_info->last_name ?></option>
                        <?php } ?>
                    </select>
                </div>
                <br>
            </div>
            <div class="col-md-6 col-lg-6 col-xm-12">
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-map-marker"> País:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <select class="form-control" name="pais" onchange="change_pais(this.value);" required>
                        <option value="">Selecciona el país</option>
                        <?php foreach ($paises as $pais) { ?>
                            <option class="text-center" value="<?= $pais->id ?>"><?= $pais->nombre ?></option>
                        <?php } ?>
                    </select>
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-map-marker"> Estado:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <select class="form-control" id="select_estado" name="estado" required>
                        <option value="">Selecciona un estado</option>
                    </select>
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-map-marker"> Ubicación:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input type="text" class="form-control" name="ubi" required placeholder="Ubicación">
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-pencil"> Empresa contratante:&nbsp;</span>
                    <select class="form-control" name="empresa" required>
                        <option value="">Selecciona una empresa</option>
                        <?php foreach ($empresas as $empresa) { ?>
                            <option class="text-center" value="<?= $empresa->id_empresa ?>"><?= $empresa->razon_social ?></option>
                        <?php } ?>
                    </select>
                </div>
                <br>
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-calendar"> Fecha de ingreso:&nbsp;&nbsp;</span>
                    <input type="date" class="form-control" name="ingr" required placeholder="Selecciona una fecha">
                </div>
                <br> 
                <div class="input-group">
                    <span class="input-group-addon glyphicon glyphicon-lock"> Contraseña:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <input type="password" class="form-control" name="pass" required placeholder="Contraseña">
                </div>
                <br>  
            </div>
            <div class="row text-center">
                <button type="submit" class="btn btn-success" name="guardar">Guardar</button>                    
            </div>
        </div>
    </form>
</div>
<script>
    var ajaxurl = '<?= admin_url( 'admin-ajax.php' ); ?>';
    function change_pais(id_pais){
        var data_eventos={
            'action':'obtener_estados',
            'id_pais':id_pais
        };
        jQuery.post(ajaxurl,data_eventos,function(response){
            datos = JSON.parse(response);
            var option_estado = "";                        
            for (i=0;i<datos.length;i++){
                option_estado = option_estado + "<option value='"+datos[i]['id_estado']+"'>"+datos[i]['estado']+"</option>";
            }
            console.log(option_estado);
            document.getElementById('select_estado').innerHTML = option_estado;
        });
    }
    function change_departamento(id_departamento){
        var data_eventos={
            'action':'obtener_areas',
            'id_departamento':id_departamento
        };
            console.log(id_departamento);

        jQuery.post(ajaxurl,data_eventos,function(response){
            datos = JSON.parse(response);
            var option_area = "<option value=''>Selecciona área</option>";                        
            for (i=0;i<datos.length;i++){
                option_area = option_area + "<option value='"+datos[i]['id_area']+"'>"+datos[i]['nombre']+"</option>";
            }
            document.getElementById('select_area').innerHTML = option_area;
        });
    }
</script>