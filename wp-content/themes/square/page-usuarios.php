<?php
get_header(); ?>
<?php  

	if (isset($_POST['editar'])) {
		actualizar_usuario();
	}
	if (isset($_POST['eliminar'])) {
		eliminar_usuario($_POST['id_modal_eli']);
	}
	$paises = $wpdb->get_results('SELECT * FROM paises ORDER BY nombre');	
    $areas = $wpdb->get_results('SELECT * FROM areas ORDER BY nombre');    
    $empresas = $wpdb->get_results("SELECT * FROM empresas");
    $num_total_registros = $wpdb->get_var("SELECT count(w.ID) FROM wp_users AS w, users AS u, estados AS e WHERE u.estado=e.id_estado and w.user_login=u.id_user");
	if ($num_total_registros) {
		$rowsPerPage = 10;
		$pageNum = 1;
		if(isset($_GET['pagina'])) {
			sleep(1);
			$pageNum = $_GET['pagina'];
		}
		$offset = ($pageNum - 1) * $rowsPerPage;
		$total_paginas = ceil($num_total_registros / $rowsPerPage);
		$query_usuarios = $wpdb->get_results("SELECT w.ID, w.user_login, w.user_login,u.nombre,u.apellidos,u.departamento,u.puesto,u.comentario,u.responsable_area,u.jefe,u.estado,e.id_pais,u.ubicacion,empresa_contratante,u.fecha_ingreso,u.dias_vacaciones FROM wp_users AS w, users AS u, estados AS e WHERE u.estado=e.id_estado and w.user_login=u.id_user ORDER BY u.nombre LIMIT $offset, $rowsPerPage");
	}
	$users=$wpdb->get_results("SELECT w.ID FROM wp_users AS w, users AS u WHERE w.user_login=u.id_user ORDER BY u.nombre");
?>
<div class="container-fluid sq-clearfix">
	<br><br><br><br><br>
	<?php 	if ($num_total_registros) { ?>
		<div class="row ">
			<h1 class="color_vallas text-center">Lista de usuarios</h1>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th class="text-center">Usuario</th>
								<th class="text-center">Nombre</th>
								<th class="text-center">Área</th>
								<th class="text-center">Departamento</th>
								<th class="text-center">Ubicación</th>
								<th class="text-center">Empresa</th>
								<th class="text-center">Fecha de Ingreso</th>
								<th class="text-center">Días de vacaciones</th>
								<th class="text-center"></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($query_usuarios as $usuario) { ?>
								<?php $user_info = get_userdata($usuario->ID); ?>
								<?php $user_departamento = $wpdb->get_row("SELECT * FROM departamentos WHERE id_departamento='$usuario->departamento'"); ?>
								<?php $user_area = $wpdb->get_row("SELECT * FROM areas WHERE id_area = '$user_departamento->id_area'"); ?>
								<tr>
									<td class="text-center"><p><small><?= $usuario->user_login ?></small></p></td>
									<td><p><small><?= $usuario->nombre." ".$usuario->apellidos  ?></small></p></td>
									<td><p><small><?= $user_area->nombre ?></small></p></td>
									<td><p><small><?= $user_departamento->nombre ?></small></p></td>
									<td><p><small><?= $usuario->ubicacion ?></small></p></td>
									<td class="text-center"><p><small><?= $wpdb->get_var("SELECT razon_social FROM empresas WHERE id_empresa='$usuario->empresa_contratante'") ?></small></p></td>								
									<td class="text-center"><p><small><?= $usuario->fecha_ingreso ?></small></p></td>
									<td class="text-center"><p><small><?= $usuario->dias_vacaciones ?></small></p></td>	
									<td class="text-center" width="90px">
										<div class="btn-group">
											<a id="<?= $usuario->user_login ?>" onclick="llenar_modal_edicion(this.id,'<?= $usuario->nombre ?>','<?= $usuario->apellidos ?>','<?= $user_info->user_email ?>','<?= $user_area->id_area ?>','<?= $usuario->departamento ?>','<?= $usuario->puesto ?>','<?= $usuario->responsable_area ?>','<?= $usuario->jefe ?>','<?= $usuario->id_pais ?>','<?= $usuario->estado ?>','<?= $usuario->ubicacion ?>','<?= $usuario->empresa_contratante ?>','<?= $usuario->fecha_ingreso ?>','<?= $usuario->dias_vacaciones ?>','<?= $usuario->comentario ?>')" data-toggle="modal" data-target="#myModal1" data-dismiss="modal" class="btn btn-warning btn-xs"><span class="glyphicon glyphicon-pencil"></span></a>
											<a id="<?= $usuario->ID ?>" onclick="llenar_id_eliminar(this.id)" data-toggle="modal" data-target="#myModal2" data-dismiss="modal" class="btn btn-danger btn-xs"><span class="glyphicon glyphicon-remove"></span> </a>
										</div>
									</td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>	
				<?php  if ($total_paginas > 1) { ?>
					<div class="text-center">
						<nav class="my-4">
						    <ul class="pagination pg-blue mb-0">
						    	<?php if ($pageNum != 1){ ?>
									<li class="page-item"><a href="<?= get_permalink(get_page_by_path('usuarios')); ?>?pagina=<?= 1 ?>" class="page-link" title="Primero">&Lang;</a></li>
							        <li class="page-item">
							            <a href="<?= get_permalink(get_page_by_path('usuarios')); ?>?pagina=<?= $pageNum-1 ?>" class="page-link" aria-label="Previous" title="Anterior">
							                <span aria-hidden="true">&lang;</span>
							                <span class="sr-only">Anterior</span>
							            </a>
							        </li>
								<?php } ?>	
								<?php 
									if($pageNum-3 < 1) {
										$inicio = 1;
									}else{
										$inicio = $pageNum-3;
									}
									if ($pageNum + 3 > $total_paginas) {
										$fin = $total_paginas;
									}else{
										$fin = $pageNum + 3;
									}
								?>	
								<?php for ($i = $inicio; $i <= $fin; $i++) { ?>
									<?php if ($pageNum == $i){ ?>
										<li class="page-item active"><a class="page-link"><?= $i ?></a></li>
									<?php }else{ ?>
										<li class="page-item"><a href="<?= get_permalink(get_page_by_path('usuarios')); ?>?pagina=<?= $i ?>"><?= $i ?></a></li>
								<?php }} ?>	 
						        <?php if ($pageNum != $total_paginas){ ?>
									<li class="page-item">
							            <a href="<?= get_permalink(get_page_by_path('usuarios')); ?>?pagina=<?= $pageNum+1 ?>" class="page-link" aria-label="Next" title="Siguiente">
							                <span aria-hidden="true">&rang;</span>
							                <span class="sr-only">Siguiente</span>
							            </a>
							        </li>
							        <li class="page-item"><a href="<?= get_permalink(get_page_by_path('usuarios')); ?>?pagina=<?= $total_paginas ?>" class="page-link" title="Último">&Rang;</a></li>
								<?php } ?>
						    </ul>
						</nav>
					</div>
				<?php } ?>	
			</div>
		</div>
	<?php }else{ ?>
		<div class="text-center">
			<h4>No se han encontrado usuarios</h4>
		</div>
	<?php } ?>
</div>
</div>
<div class="modal fade" id="myModal1" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header text-center">
				<h5 class="modal-title" id="exampleModalLabel">Editar usuario</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
        		<form action="" method="POST" enctype="multipart/form-data">        			
	            	<div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span> Id:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	                    <input id="id_modal_editar" name="id_modal_editar" class="form-control" readonly>
	                </div>
	                <br>
	            	<div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span> Nombre:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	                    <input id="nombre_modal_editar" name="nombre_modal_editar" type="text" class="form-control">
	                </div>
	                <br>
	                <div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span> Apellidos:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	                    <input id="apellidos_modal_editar" name="apellidos_modal_editar" type="text" class="form-control">
	                </div>
	                <br>
					<div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span> Correo:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	                    <input id="correo_modal_editar" name="correo_modal_editar" type="text" class="form-control">
	                </div>
	                <br>
	                <div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-home"></span> Área:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	                    <select class="form-control text-center"  id="area_modal_editar" onchange="change_area(this.value,0);" required>
	                        <option class="text-center" value="">Selecciona un área</option>
	                        <?php foreach ($areas as $area) { ?>
	                            <option class="text-center" value="<?= $area->id_area ?>"><?= $area->nombre ?></option>
	                        <?php } ?>
	                    </select>
	                </div>
	                <br>
	                <div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-home"></span> Departamento:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	                    <select class="form-control" id="departamento_modal_editar" name="departamento_modal_editar" required>
	                        <option class="text-center" value="0">Selecciona un departamento</option>
	                    </select>
	                </div>
	                <br>
	                <div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-home"></span> Puesto:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	                    <input id="puesto_modal_editar" name="puesto_modal_editar" type="text" class="form-control">
	                </div>
	                <br>
	                <div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span> Responsable de área:</span>
	                    <select class="form-control" id="resp_modal_editar" name="resp_modal_editar">
	                        <option class="text-center" value="0">Selecciona el Responsable de área</option>
	                        <?php foreach ($users as $usuario) { ?>
	                            <?php $user_info = get_userdata($usuario->ID); ?>
	                            <option class="text-center" value="<?= $usuario->ID ?>"><?= $user_info->first_name." ".$user_info->last_name ?></option>
	                        <?php } ?>
	                    </select>
	                </div>
	                <br>
	                <div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span> Jefe inmediato:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	                    <select class="form-control" id="jefe_modal_editar" name="jefe_modal_editar">
	                        <option class="text-center" value="0">Selecciona el Jefe inmediato</option>
	                        <?php foreach ($users as $usuario) { ?>
	                            <?php $user_info = get_userdata($usuario->ID); ?>
	                            <option class="text-center" value="<?= $usuario->ID ?>"><?= $user_info->first_name." ".$user_info->last_name ?></option>
	                        <?php } ?>
	                    </select>
	                </div>
	                <br>
	                <div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span> Pais:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	                    <select class="form-control" id="pais_modal_editar" name="pais_modal_editar" onchange="change_pais(this.value,0);" required>
	                        <option class="text-center" value="">Selecciona el país</option>
	                        <?php foreach ($paises as $pais) { ?>
	                            <option class="text-center" value="<?= $pais->id ?>"><?= $pais->nombre ?></option>
	                        <?php } ?>
	                    </select>
	                </div>
	                <br>
	                <div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span> Estado:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	                    <select class="form-control" id="estado_modal_editar" name="estado_modal_editar" required>
	                        <option class="text-center" value="">Selecciona un estado</option>
	                    </select>
	                </div>
	                <br>
	                <div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-map-marker"></span> Ubicación:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	                    <input id="ubicacion_modal_editar" name="ubicacion_modal_editar" type="text" class="form-control">
	                </div>
	                <br>
	                <div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span> Empresa contratante:</span>
	                    <select class="form-control" id="empresa_modal_editar" name="empresa_modal_editar" required>
	                        <?php foreach ($empresas as $empresa) { ?>
	                            <option class="text-center" value="<?= $empresa->id_empresa ?>"><?= $empresa->razon_social ?></option>
	                        <?php } ?>
	                    </select>
	                </div>
	                <br>
	                <div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span> Fecha de ingreso:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	                    <input id="fecha_modal_editar" name="fecha_modal_editar" type="date" class="form-control">
	                </div>
	                <br>
	                <div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span> Días de vacaciones:&nbsp;&nbsp;&nbsp;&nbsp;</span>
	                    <input id="dias_modal_editar" name="dias_modal_editar" type="number" class="form-control">
	                </div>
	                <br>
	                <div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-pencil"></span> Comentario:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</span>
	                    <textarea id="comentario_modal_editar" name="comentario_modal_editar" class="form-control"></textarea>
	                </div>
	                <hr>
	                <div class="text-right">
		                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
						<button type="submit" name="editar" class="btn btn-primary">Aceptar</button>	                	
	                </div>
                </form>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="myModal2" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header text-center">
				<h5 class="modal-title" id="exampleModalLabel">Eliminar solicitud</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
				<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="text-center">
					<p>Confirma que deseas eliminar la solicitud</p>
				</div>
        		<form action="" method="POST" enctype="multipart/form-data">
	            	<div class="input-group">
	                    <span class="input-group-addon"><span class="glyphicon glyphicon-remove"></span> ID:</span>
	                    <input id="id_modal_eli" name="id_modal_eli" type="text" class="form-control text-center" readonly>
	                </div>
	                <hr>
	                <div class="text-right">
		                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
						<button type="submit" name="eliminar" class="btn btn-primary">Eliminar</button>
					</div>
                </form>
			</div>
		</div>
	</div>
</div>
<script>
	function llenar_modal_edicion(id_click,nombre,apellidos,correo,area,departamento,puesto,responsable,jefe,pais,estado,ubicacion,empresa,fecha,dias,comentario){
		change_pais(pais,estado);		
		change_area(area,departamento);
		console.log("jef"+jefe)
		document.getElementById("id_modal_editar").value = id_click;
		document.getElementById("nombre_modal_editar").value = nombre;
		document.getElementById("apellidos_modal_editar").value = apellidos;
		document.getElementById("correo_modal_editar").value = correo;
		document.getElementById("area_modal_editar").value = area;		
		document.getElementById("puesto_modal_editar").value = puesto;
		document.getElementById("resp_modal_editar").value = responsable;
		console.log("responsable: "+responsable);
		document.getElementById("jefe_modal_editar").value = jefe;
		document.getElementById("pais_modal_editar").value = pais;
		document.getElementById("ubicacion_modal_editar").value = ubicacion;
		document.getElementById("empresa_modal_editar").value = empresa;
		document.getElementById("fecha_modal_editar").value = fecha;
		document.getElementById("dias_modal_editar").value = dias;
		document.getElementById("comentario_modal_editar").innerHTML = comentario;
	}
	function llenar_id_eliminar(id_click){
		document.getElementById("id_modal_eli").value = id_click;
	}
	//ajax para selects
	var ajaxurl = '<?= admin_url( 'admin-ajax.php' ); ?>';
    function change_pais(id_pais,estado){
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
            document.getElementById('estado_modal_editar').innerHTML = option_estado;
			document.getElementById("estado_modal_editar").value = estado;
        });
    }
    function change_area(id_area,departamento){
        var data_eventos={
            'action':'obtener_departamentos',
            'id_area':id_area
        };
            console.log(id_area);

        jQuery.post(ajaxurl,data_eventos,function(response){
            datos = JSON.parse(response);
            var option_area = "<option value=''>Selecciona un departamento</option>";                        
            for (i=0;i<datos.length;i++){
                option_area = option_area + "<option value='"+datos[i]['id_departamento']+"'>"+datos[i]['nombre']+"</option>";
            }
            document.getElementById('departamento_modal_editar').innerHTML = option_area;
	        document.getElementById("departamento_modal_editar").value = departamento;
        });
    }
</script>