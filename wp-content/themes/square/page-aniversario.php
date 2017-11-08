<?php
get_header(); ?>
<?php  
    $num_total_registros = $wpdb->get_var("SELECT count(id_bitacora) FROM bitacora_diaria");
	if ($num_total_registros) {
		$rowsPerPage = 10;
		$pageNum = 1;
		if(isset($_GET['pagina'])) {
			sleep(1);
			$pageNum = $_GET['pagina'];
		}
		$offset = ($pageNum - 1) * $rowsPerPage;
		$total_paginas = ceil($num_total_registros / $rowsPerPage);
		$query_usuarios = $wpdb->get_results("SELECT * FROM bitacora_diaria AS b, users AS u WHERE b.id_user = u.id_user ORDER BY id_bitacora DESC LIMIT $offset, $rowsPerPage");
	}
?>
<div class="container-fluid sq-clearfix">
	<br><br><br><br><br>
	<?php 	if ($num_total_registros) { ?>
		<div class="row ">
			<h1 class="color_vallas text-center">Aniversario</h1>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="table-responsive">
					<table class="table">
						<thead>
							<tr>
								<th class="text-center">Fecha</th>
								<th class="text-center">Usuario</th>
								<th class="text-center">Fecha de ingreso</th>
								<th class="text-center">Nombre</th>
								<th class="text-center">Área</th>
								<th class="text-center">Departamento</th>
								<th class="text-center">Ubicación</th>
								<th class="text-center">Empresa</th>
								<th class="text-center">Días anteriores</th>
								<th class="text-center">Días actualizados</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($query_usuarios as $usuario) { ?>
								<tr>
									<td class="text-center"><p><small><?= $usuario->fecha ?></small></p></td>
									<td class="text-center"><p><small><?= $usuario->id_user ?></small></p></td>
									<td class="text-center"><p><small><?= $usuario->fecha_ingreso ?></small></p></td>
									<td><p><small><?= $usuario->nombre." ".$usuario->apellidos  ?></small></p></td>
									<td><p><small><?= $wpdb->get_var("SELECT a.nombre FROM areas AS a, departamentos AS d WHERE d.id_departamento = '$usuario->departamento' AND a.id_area = d.id_area") ?></small></p></td>
									<td><p><small><?= $wpdb->get_var("SELECT nombre FROM departamentos WHERE id_departamento = '$usuario->departamento' ") ?></small></p></td>
									<td><p><small><?= $usuario->ubicacion ?></small></p></td>
									<td class="text-center"><p><small><?= $wpdb->get_var("SELECT razon_social FROM empresas WHERE id_empresa='$usuario->empresa_contratante'") ?></small></p></td>
									<td class="text-center"><p><small><?= $usuario->antiguos ?></small></p></td>
									<td class="text-center"><p><small><?= $usuario->nuevos ?></small></p></td>
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
									<li class="page-item"><a href="<?= get_permalink(get_page_by_path('aniversario')); ?>?pagina=<?= 1 ?>" class="page-link" title="Primero">&Lang;</a></li>
							        <li class="page-item">
							            <a href="<?= get_permalink(get_page_by_path('aniversario')); ?>?pagina=<?= $pageNum-1 ?>" class="page-link" aria-label="Previous" title="Anterior">
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
										<li class="page-item"><a href="<?= get_permalink(get_page_by_path('aniversario')); ?>?pagina=<?= $i ?>"><?= $i ?></a></li>
								<?php }} ?>	 
						        <?php if ($pageNum != $total_paginas){ ?>
									<li class="page-item">
							            <a href="<?= get_permalink(get_page_by_path('aniversario')); ?>?pagina=<?= $pageNum+1 ?>" class="page-link" aria-label="Next" title="Siguiente">
							                <span aria-hidden="true">&rang;</span>
							                <span class="sr-only">Siguiente</span>
							            </a>
							        </li>
							        <li class="page-item"><a href="<?= get_permalink(get_page_by_path('aniversario')); ?>?pagina=<?= $total_paginas ?>" class="page-link" title="Último">&Rang;</a></li>
								<?php } ?>
						    </ul>
						</nav>
					</div>
				<?php } ?>	
			</div>
		</div>
	<?php }else{ ?>
		<div class="text-center">
			<h4>No se han encontrado datos de aniversarios</h4>
		</div>
	<?php } ?>
</div>
