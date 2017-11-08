<?php
function solicitar_vacaciones(){
	global $wpdb; 
	$folio=$_POST['folio'];
    $dias_selec=$_POST['dias_selec'];
    $dias_restantes=$_POST['dias_restantes'];
    $responsable=$_POST['resp'];
    $update=$wpdb->update(
        "solicitudes",
        ['responsable'=>$responsable, 'dias_usados'=>$dias_selec],
        ['id_solicitud'=>$folio],
        ['%s','%d'],
        ['%d']
    );
    echo "<script>window.open('pdf?folio=".$folio."', 'Formato de impresión')</script>";
}
function solicitar_permiso(){
	global $wpdb; 
	$folio=$_POST['folio_permiso'];
    $dias_selec=$_POST['dias_selec_permiso'];
    $motivo=$_POST['motivo'];
    $update=$wpdb->update(
        "solicitudes",
        ['id_motivo'=>$motivo, 'dias_usados'=>$dias_selec],
        ['id_solicitud'=>$folio],
        ['%s','%d'],
        ['%d']
    );
    echo "<script>window.open('pdf_permisos?folio=".$folio."', 'Formato de impresión')</script>";
}
//Page usuarios

function actualizar_usuario(){
	global $wpdb; 
	$id 			= isset($_POST['id_modal_editar']) 			? $_POST['id_modal_editar'] 			: "";
	$nombre 		= isset($_POST['nombre_modal_editar']) 	 	? $_POST['nombre_modal_editar'] 		: "";
	$apellidos 		= isset($_POST['apellidos_modal_editar'])  	? $_POST['apellidos_modal_editar']   	: "";
	$correo 		= isset($_POST['correo_modal_editar'])  	? $_POST['correo_modal_editar']   		: "";

	$departamento 	= isset($_POST['departamento_modal_editar']) ? $_POST['departamento_modal_editar'] 	: "";
	$resp 			= isset($_POST['resp_modal_editar']) 		? $_POST['resp_modal_editar'] 			: "";
	$jefe 			= isset($_POST['jefe_modal_editar']) 		? $_POST['jefe_modal_editar'] 			: "";
	$pais 			= isset($_POST['pais_modal_editar']) 		? $_POST['pais_modal_editar'] 			: "";
	$estado 		= isset($_POST['estado_modal_editar']) 		? $_POST['estado_modal_editar'] 		: "";
	$ubicacion 		= isset($_POST['ubicacion_modal_editar']) 	? $_POST['ubicacion_modal_editar'] 		: "";
	$empresa 		= isset($_POST['empresa_modal_editar']) 	? $_POST['empresa_modal_editar'] 		: "";
	$fecha 			= isset($_POST['fecha_modal_editar']) 		? $_POST['fecha_modal_editar'] 			: "";
	$dias 			= isset($_POST['dias_modal_editar']) 		? $_POST['dias_modal_editar'] 			: 0;
	$comentario		= isset($_POST['comentario_modal_editar']) 	? $_POST['comentario_modal_editar'] 	: "";
	$id_wp_users 	= $wpdb->get_var("SELECT ID FROM wp_users WHERE user_login = '$id'");
	$update 		= $wpdb->update('wp_users',['user_email'=> $correo],['ID'=>$id_wp_users],['%s'],['%d']);
	update_user_meta($id_wp_users, 'first_name', $apellidos);
	update_user_meta($id_wp_users, 'last_name', $nombre);
 	$update 		= $wpdb->update(
		'users',
		[
			'departamento' 			=> $departamento, 
			'nombre'				=> $nombre,
			'apellidos'				=> $apellidos,
		    "responsable_area"		=> $resp,			
			'jefe' 					=> $jefe,
			'estado'				=> $estado,
			'ubicacion'				=> $ubicacion,
			'empresa_contratante'	=> $empresa,
			'fecha_ingreso' 		=> $fecha,
			'dias_vacaciones' 		=> $dias,
			'comentario' 			=> $comentario
		],
		['id_user'=>$id],
		['%s','%s','%s','%d','%d','%d','%s','%s','%s','%d','%s'],
		['%d']
	);
	echo "<script>swal('Actualizado!', 'Los datos del usuario han sido actualizados exitosamente!', 'success'); </script>";
}
/*function hola(){
	global $wpdb;
	$users = $wpdb->get_results("SELECT id_user FROM users");
	foreach ($users as $user) {
		$user_info = get_userdata($user->id_user); 
		$update 		= $wpdb->update(
			'users',
			[
				'id_user' 			=> $user_info->ID
			],
			['id_user'=>$user->user_login],
			['%d'],
			['%d']
		);
		echo $user_info->first_name;
		echo "<br>";
		echo $update;
	}
}*/
function eliminar_usuario($id_user){
	global $wpdb; 
	require_once(ABSPATH.'wp-admin/includes/user.php' );
	wp_delete_user($id_user);
	$delete_user=$wpdb->delete(
		'users',
		['id_user'=>$id_user],
		['%d']
	);
	if ( $delete_user) {
		echo "<script>swal('Eliminado!', 'Usuario eliminado con exito!', 'success'); </script>";
	}else{
		echo "<script>swal('¡Error!', 'No se ha podido eliminar la solicitud', 'error');  </script>";
	}
}
function dias_vacaciones($fecha){
	$datetime1 = new DateTime($fecha);
	$datetime2 = new DateTime('now');
	$interval = $datetime1->diff($datetime2);
	$años=intval($interval->format('%y'));
	if($años == 0){
		return 0;
	}elseif($años==1){
		return 6;
	}elseif ($años == 2) {
		return 8;
	}elseif ($años == 3) {
		return 10;
	}elseif ($años == 4){
		return 12;
	}elseif ($años >= 5 && $años <= 9){
		return 14;
	}elseif ($años >= 10 && $años <= 14) {
		return 16;
	}elseif ($años >= 15 && $años <= 19){
		return 18;
	}
	return 0;
}

function nuevo_usuario(){
	global $wpdb; 
	$user 		=	isset($_POST['user']) 		?	$_POST['user']		: "";
	$nombre 	=	isset($_POST['nombre'])		?	$_POST['nombre']	: "";
	$apellidos 	=	isset($_POST['apellidos'])	?	$_POST['apellidos'] : "";
	$pass 		=	isset($_POST['pass'])		?	$_POST['pass']		: "";
	$email 		=	isset($_POST['email'])		?	$_POST['email']		: "";

	$dep 		= 	isset($_POST['departamento'])		? 	$_POST['departamento']		: "";
	$jefe 		= 	isset($_POST['jefe'])				?	$_POST['jefe']				: "";
	$resp 		= 	isset($_POST['resp'])				?	$_POST['resp']				: "";
	$puesto 	= 	isset($_POST['puesto'])				?	$_POST['puesto']			: "";
	$estado 	= 	isset($_POST['estado'])				?	$_POST['estado']			: "";
	$ubi 		= 	isset($_POST['ubi'])				?	$_POST['ubi']				: "";
	$empresa	=	isset($_POST['empresa'])			?	$_POST['empresa']			: "";
	$ingr 		= 	isset($_POST['ingr'])				?	$_POST['ingr']				: "";
	$datos = array(
        "user_login"	=> $user,
        "nombre"		=> $nombre,
        "apellidos"		=> $apellidos,
        "user_pass"		=> $pass,
        "user_email"	=> $email,
        "display_name"	=> $user,
        "first_name"	=> $nombre, 
        "last_name"		=> $apellidos,
        "role"			=> "subscriber"
    );
    $user_id = wp_insert_user( $datos ) ;
    if ( ! is_wp_error( $user_id ) ) {
        $insert = $wpdb->insert(
        	'users',
        	array(
		    	"id_user"				=> $user,
        		"nombre"				=> $nombre,
        		"apellidos"				=> $apellidos,
		    	"departamento"			=> $dep,
		    	"puesto"				=> $puesto,
		    	"responsable_area"		=> $resp,
		    	"jefe"					=> $jefe,
		    	"estado"				=> $estado,
		    	"ubicacion"				=> $ubi,
		    	"empresa_contratante"	=> $empresa,
		    	"fecha_ingreso"			=> $ingr,
		    	"dias_vacaciones"		=> dias_vacaciones($ingr)
		    ),
		    array(
		    	'%d',
		    	'%s',
		    	'%s',
		    	'%s',
		    	'%s',
		    	'%d',
		    	'%d',
		    	'%d',
		    	'%s',
		    	'%s',
		    	'%s',
		    	'%d'
		    )
        );
        if($insert){
            echo "<script>swal('Guardado!', 'Usuario guardado!', 'success'); </script>";
        }else{
            echo "<script>swal('¡Error!', 'No se ha podido guardar usuario', 'error');  </script>";
            require_once(ABSPATH.'wp-admin/includes/user.php' );
			wp_delete_user($user_id);
        }       
    }else{
        echo "<script>swal('¡Error!', 'No se ha podido guardar Usuario', 'error');  </script>";
    }
}
function confirmar_solicitud($folio){
	global $wpdb; 
	$solicitud=$wpdb->get_row("SELECT id_user,dias_usados FROM solicitudes WHERE id_solicitud=$folio");
	$dias_disponibles=$wpdb->get_var("SELECT dias_vacaciones FROM users WHERE id_user=$solicitud->id_user");
	$resta=intval($dias_disponibles) - intval($solicitud->dias_usados);
	if($resta >= 0){
		$update1=$wpdb->update(
			'solicitudes',
			['autorizado'=>'1'],
			['id_solicitud'=>$folio],
			['%d'],
			['%d']
		);
		$update2=$wpdb->update(
			'users',
			['dias_vacaciones'=>$resta],
			['id_user'=>$solicitud->id_user],
			['%d'],
			['%d']
		);
		if ($update1 && $update2) {
			echo "<script>swal('Aprobada!', 'Solicitud de vacaciones aprobada!', 'success'); </script>";
		}else{
			echo "<script>swal('¡Error!', 'No se ha podido confirmar la solicitud', 'error');  </script>";
		}
	}else{
		echo "<script>swal('¡Error!', 'Los días con los que dispone el usuario no son suficientes para esta solicitud!', 'error');  </script>";
	}	
}
function confirmar_solicitud_permiso($folio){
	global $wpdb; 
	$update=$wpdb->update(
		'solicitudes',
		['autorizado'=>'1'],
		['id_solicitud'=>$folio],
		['%d'],
		['%d']
	);
	if ($update) {
		echo "<script>swal('Aprobada!', 'Solicitud de permiso aprobada!', 'success'); </script>";
	}else{
		echo "<script>swal('¡Error!', 'No se ha podido confirmar la solicitud', 'error');  </script>";
	}	
}
function eliminar_solicitud($folio){
	global $wpdb; 
	$delete_folio=$wpdb->delete(
		'solicitudes',
		['id_solicitud'=>$folio],
		['%d']
	);
	$wpdb->delete(
		'eventos',
		['id_solicitud'=>$folio],
		['%d']
	);
	if ($delete_folio) {
		echo "<script>swal('Eliminada!', 'Solicitd eliminada con exito!', 'success'); </script>";
	}else{
		echo "<script>swal('¡Error!', 'No se ha podido eliminar la solicitud', 'error');  </script>";
	}
}
//ajax obtener departamentos
add_action( 'wp_ajax_obtener_departamentos', 'obtener_departamentos' );
add_action( 'wp_ajax_nopriv_obtener_departamentos', 'obtener_departamentos' );
function obtener_departamentos() {
	global $wpdb;
	$id_area = isset($_POST['id_area']) ? $_POST['id_area'] : "";	
	$datos = $wpdb->get_results("SELECT id_departamento,nombre FROM departamentos WHERE id_area='$id_area' ORDER BY nombre");
   	echo json_encode($datos);
	wp_die();
}
//ajax obtener estados
add_action( 'wp_ajax_obtener_estados', 'obtener_estados' );
add_action( 'wp_ajax_nopriv_obtener_estados', 'obtener_estados' );
function obtener_estados() {
	global $wpdb;
	$id_pais = isset($_POST['id_pais']) ? $_POST['id_pais'] : "";	
	$datos = $wpdb->get_results("SELECT id_estado, estado FROM estados WHERE id_pais='$id_pais' ORDER BY estado");
   	echo json_encode($datos);
	wp_die();
}
//ajax calendario
add_action( 'wp_ajax_eliminar_evento', 'eliminar_evento' );
add_action( 'wp_ajax_nopriv_eliminar_evento', 'eliminar_evento' );
function eliminar_evento() {
	global $wpdb; 
	$id_evento = intval($_POST['id_evento']);
	$delete=$wpdb->delete( 'eventos', array( 'id' => $id_evento ) );
	if ($delete) {
		echo ("Evento ".$id_evento." eliminado!");
	}else{
		echo("Error al eliminar evento ".$id_evento);
	}
	wp_die();
}
add_action( 'wp_ajax_nuevo_folio', 'nuevo_folio' );
add_action( 'wp_ajax_nopriv_nuevo_folio', 'nuevo_folio' );
function nuevo_folio() {
	global $wpdb; 
	$id_user = intval($_POST['id_user']);
	$tipo = $_POST['tipo'];
	date_default_timezone_set('america/mexico_city');
    $fecha_hora=date('Y-m-d H:i:s');
	$nuso=$wpdb->insert('solicitudes', ['id_user' => $id_user,'tipo' => $tipo, 'fecha' => $fecha_hora], ['%d','%s','%s']);
	if ($nuso) {
		echo ($wpdb->get_var("SELECT max(id_solicitud) FROM solicitudes WHERE id_user=$id_user"));
	}else{
		echo ("Error");
	}
	wp_die();
}
add_action( 'wp_ajax_nuevo_evento', 'nuevo_evento' );
add_action( 'wp_ajax_nopriv_nuevo_evento', 'nuevo_evento' );
function nuevo_evento() {
	global $wpdb; 
	$id_solicitud = intval($_POST['id_solicitud']);	
	$start = $_POST['start'];
	$end = $_POST['end'];
	$dias = intval($_POST['dias']);
	$insert=$wpdb->query("INSERT INTO eventos VALUES ('',$id_solicitud,'$start','$end',$dias)");	
	echo $insert;
	wp_die();
}
add_action( 'wp_ajax_obtener_eventos', 'obtener_eventos' );
add_action( 'wp_ajax_nopriv_obtener_eventos', 'obtener_eventos');
function obtener_eventos() {
	global $wpdb;
	$id_solicitud = isset($_POST['id_solicitud']) ?	$_POST['id_solicitud'] : "";
	$datos = $wpdb->get_results("SELECT * FROM eventos WHERE id_solicitud='$id_solicitud'");
   	if(!is_null($datos )){
   		echo json_encode($datos);
   	}else{
   		echo json_encode(Array());
   	}	
	wp_die();
}
if ( ! function_exists( 'square_setup' ) ) :
function square_setup() {
	// Make theme available for translation.
	load_theme_textdomain( 'square', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	//Let WordPress manage the document title.
	add_theme_support( 'title-tag' );

	//Support for woocommerce
	add_theme_support( 'woocommerce' );
	add_theme_support( 'wc-product-gallery-zoom' );
    add_theme_support( 'wc-product-gallery-lightbox' );
    add_theme_support( 'wc-product-gallery-slider' );
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'square-about-thumb', 400, 420, true );
	add_image_size( 'square-blog-thumb', 800, 420, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'square' ),
	) );
	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption',
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'square_custom_background_args', array(
		'default-color' => 'ffffff',
		'default-image' => '',
	) ) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', square_fonts_url() ) );
}
endif; // square_setup
add_action( 'after_setup_theme', 'square_setup' );

function square_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'square_content_width', 800 );
}
add_action( 'after_setup_theme', 'square_content_width', 0 );

//Enables the Excerpt meta box in Page edit screen.
function square_add_excerpt_support_for_pages() {
	add_post_type_support( 'page', 'excerpt' );
}
add_action( 'init', 'square_add_excerpt_support_for_pages' );
add_filter( 'show_admin_bar', '__return_false' );
//Register widget area.
function square_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Right Sidebar', 'square' ),
		'id'            => 'square-right-sidebar',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Left Sidebar', 'square' ),
		'id'            => 'square-left-sidebar',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Shop Sidebar', 'square' ),
		'id'            => 'square-shop-sidebar',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 1', 'square' ),
		'id'            => 'square-footer1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 2', 'square' ),
		'id'            => 'square-footer2',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 3', 'square' ),
		'id'            => 'square-footer3',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'Footer 4', 'square' ),
		'id'            => 'square-footer4',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	) );

	register_sidebar( array(
		'name'          => esc_html__( 'About Footer', 'square' ),
		'id'            => 'square-about-footer',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h5 class="widget-title">',
		'after_title'   => '</h5>',
	) );
}
add_action( 'widgets_init', 'square_widgets_init' );

if ( ! function_exists( 'square_fonts_url' ) ) :

function square_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Open Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Open Sans font: on or off', 'square' ) ) {
		$fonts[] = 'Open+Sans:400,300,600,700';
	}

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Inconsolata, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Roboto Condensed font: on or off', 'square' ) ) {
		$fonts[] = 'Roboto+Condensed:300italic,400italic,700italic,400,300,700';
	}

	/*
	 * Translators: To add an additional character subset specific to your language,
	 * translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language.
	 */
	$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'square' );

	if ( 'cyrillic' == $subset ) {
		$subsets .= ',cyrillic,cyrillic-ext';
	} elseif ( 'greek' == $subset ) {
		$subsets .= ',greek,greek-ext';
	} elseif ( 'devanagari' == $subset ) {
		$subsets .= ',devanagari';
	} elseif ( 'vietnamese' == $subset ) {
		$subsets .= ',vietnamese';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' =>  implode( '|', $fonts ) ,
			'subset' =>  $subsets ,
		), '//fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * Enqueue scripts and styles.
 */
function square_scripts() {
	wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.js', array(), '2.6.3', true );
	wp_enqueue_script( 'bxslider', get_template_directory_uri() . '/js/jquery.bxslider.js', array('jquery'), '4.1.2', true );
	wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/js/owl.carousel.js', array('jquery'), '1.3.3', true );
	wp_enqueue_script( 'jquery-superfish', get_template_directory_uri() . '/js/jquery.superfish.js', array('jquery'), '20160213', true );

	if(is_page_template( 'templates/home-template.php' ) || is_front_page() ){
		wp_enqueue_script( 'square-draggabilly', get_template_directory_uri() . '/js/draggabilly.pkgd.min.js', array('jquery'), '1.3.3', true );
		wp_enqueue_script( 'square-elastiStack', get_template_directory_uri() . '/js/elastiStack.js', array('jquery'), '1.0.0', true );
	}

	wp_enqueue_script( 'square-custom', get_template_directory_uri() . '/js/square-custom.js', array('jquery'), '20150903', true );
	
	wp_enqueue_style( 'square-fonts', square_fonts_url(), array(), null );
	wp_enqueue_style( 'bxslider', get_template_directory_uri() . '/css/jquery.bxslider.css', array(), '4.1.2' );
	wp_enqueue_style( 'animate', get_template_directory_uri() . '/css/animate.css', array(), '1.0' );
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() . '/css/font-awesome.css', array(), '4.6.3' );
	wp_enqueue_style( 'owl-carousel', get_template_directory_uri() . '/css/owl.carousel.css', array(), '1.3.3' );
	wp_enqueue_style( 'owl-theme', get_template_directory_uri() . '/css/owl.theme.css', array(), '1.3.3' );
	wp_enqueue_style( 'square-style', get_stylesheet_uri() );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'square_scripts' );

/**
 * Enqueue admin style
 */
function square_admin_scripts() {
	wp_enqueue_media();
	wp_enqueue_style( 'square-admin-style', get_template_directory_uri() . '/inc/css/admin-style.css', array(), '1.0' );
	wp_enqueue_script( 'square-admin-scripts', get_template_directory_uri() . '/inc/js/admin-scripts.js', array('jquery'), '20160915', true );
}
add_action( 'admin_enqueue_scripts', 'square_admin_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/square-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Woocommerce additions
 */
require get_template_directory() . '/inc/woo-functions.php';

/**
 * Load Custom Metabox
 */
require get_template_directory() . '/inc/square-metabox.php';

/**
 * Widgets additions.
 */
require get_template_directory() . '/inc/widgets/widget-fields.php';
require get_template_directory() . '/inc/widgets/widget-contact-info.php';
require get_template_directory() . '/inc/widgets/widget-personal-info.php';