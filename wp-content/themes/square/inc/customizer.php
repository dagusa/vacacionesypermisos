<?php
/**
 * Square Theme Customizer.
 *
 * @package Square
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function square_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

	$square_page = '';
	$square_page_array = get_pages();
	if(is_array($square_page_array)){
		$square_page = $square_page_array[0]->ID;
	}

	$header_bg_choices = array(
		'sq-white' => __( 'White', 'square'), 
		'sq-black' => __( 'Black', 'square')  
		);

	/*============GENERAL SETTINGS PANEL============*/
	$wp_customize->add_panel(
		'square_general_settings_panel',
		array(
			'title' 			=> __( 'General Settings', 'square' ),
			'priority'          => 10
		)
	);

	//STATIC FRONT PAGE
	$wp_customize->add_section( 'static_front_page', array(
	    'title'          => __( 'Static Front Page', 'square' ),
	    'panel' => 'square_general_settings_panel',
	    'description'    => __( 'Your theme supports a static front page.', 'square'),
	) );

	//TITLE AND TAGLINE SETTINGS
	$wp_customize->add_section( 'title_tagline', array(
	     'title'    => __( 'Site Title & Tagline', 'square' ),
	     'panel' => 'square_general_settings_panel',
	) );

	//HEADER LOGO 
	$wp_customize->add_section( 'header_image', array(
	     'title'    => __( 'Header Logo', 'square' ),
	     'panel' => 'square_general_settings_panel',
	) );

	//HEADER SETTINGS 
	$wp_customize->add_section(
		'square_header_setting_sec',
		array(
			'title'			=> __( 'Header Settings', 'square' ),
			'panel'         => 'square_general_settings_panel'
		)
	);

	$wp_customize->add_setting(
		'square_disable_sticky_header',
		array(
			'default'			=> 0,
			'sanitize_callback' => 'absint'
		)
	);

	$wp_customize->add_control(
		'square_disable_sticky_header',
		array(
			'settings'		=> 'square_disable_sticky_header',
			'section'		=> 'square_header_setting_sec',
			'label'			=> __( 'Disable Sticky Header', 'square' ),
			'type'       	=> 'checkbox',
		)
	);

	$wp_customize->add_setting(
		'square_header_bg',
		array(
			'default'			=> 'sq-black',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'square_sanitize_choices'
		)
	);

	$wp_customize->add_control(
		new Square_Dropdown_Chooser(
			$wp_customize,
			'square_header_bg',
			array(
				'settings'		=> 'square_header_bg',
				'section'		=> 'square_header_setting_sec',
				'type'			=> 'select',
				'label'			=> __( 'Header Background Color', 'square' ),
				'choices'       => $header_bg_choices,
			)
		)
	);


	$wp_customize->add_setting(
		'square_page_header_bg',
		array(
			'default'			=> get_template_directory_uri().'/images/bg.jpg',
			'sanitize_callback' => 'esc_url_raw'
		)
	);

	$wp_customize->add_control(
	    new WP_Customize_Image_Control(
	        $wp_customize,
	        'square_page_header_bg',
	        array(
	            'label'    => __( 'Page Header Banner', 'square' ),
	            'settings' => 'square_page_header_bg',
	            'section'  => 'square_header_setting_sec',
	            'description'   => __( 'This banner will show in the header of all the inner pages <br/>Recommended Image Size: 1800X400px', 'square' )
	        )
	    )
	);

	//BLOG SETTINGS
	$wp_customize->add_section(
		'square_blog_sec',
		array(
			'title'			=> __( 'Blog Settings', 'square' ),
			'panel'         => 'square_general_settings_panel'
		)
	);

	$wp_customize->add_setting(
		'square_blog_format',
		array(
			'default'			=> 'excerpt',
			'sanitize_callback' => 'square_sanitize_text'
		)
	);

	$wp_customize->add_control(
		'square_blog_format', 
		array(
			'label'    => __( 'Blog Content Format', 'square' ),
			'section'  => 'square_blog_sec',
			'settings' => 'square_blog_format',
			'type'     => 'radio',
			'choices'  => array(
				'excerpt'  => 'Excerpt',
				'full_content' => 'Full Content',
			),
		)
	);

	$wp_customize->add_setting(
		'square_blog_share_buttons',
		array(
			'default'			=> 0,
			'sanitize_callback' => 'absint'
		)
	);

	$wp_customize->add_control(
			'square_blog_share_buttons',
			array(
				'settings'		=> 'square_blog_share_buttons',
				'section'		=> 'square_blog_sec',
				'label'			=> __( 'Disable Share Buttons', 'square' ),
				'type'       	=> 'checkbox',
			)
	);

	//BACKGROUND IMAGE
	$wp_customize->add_section( 'background_image', array(
	     'title'    => __( 'Background Image', 'square' ),
	     'panel' => 'square_general_settings_panel',
	) );

	$wp_customize->add_section( 'colors', array(
	     'title'    => __( 'Colors' , 'square'),
	     'panel' => 'square_general_settings_panel',
	) );

	/*============HOME SETTINGS PANEL============*/
	$wp_customize->add_panel(
		'square_home_settings_panel',
		array(
			'title' 			=> __( 'Home Page Sections', 'square' ),
			'priority'          => 10
		)
	);

	/*============SLIDER IMAGES SECTION============*/
	$wp_customize->add_section(
		'square_slider_sec',
		array(
			'title'			=> __( 'Slider Section', 'square' ),
			'panel'         => 'square_home_settings_panel'
		)
	);

	//SLIDERS
	for ($i=1; $i < 4; $i++) { 

	$wp_customize->add_setting(
		'square_slider_heading'.$i,
		array(
			'default'			=> '',
			'sanitize_callback' => 'square_sanitize_text'
		)
	);

	$wp_customize->add_control(
		new Square_Customize_Heading(
			$wp_customize,
			'square_slider_heading'.$i,
		array(
			'settings'		=> 'square_slider_heading'.$i,
			'section'		=> 'square_slider_sec',
			'label'			=> __( 'Slider ', 'square' ).$i,
		)
		)
	);

	$wp_customize->add_setting(
		'square_slider_title'.$i,
		array(
			'default'			=> __('Free WordPress Themes','square'),
			'transport'         => 'postMessage',
			'sanitize_callback' => 'square_sanitize_text',
		)
	);

	$wp_customize->add_control(
		'square_slider_title'.$i,
		array(
			'settings'		=> 'square_slider_title'.$i,
			'section'		=> 'square_slider_sec',
			'type'			=> 'text',
			'label'			=> __( 'Caption Title', 'square' )
		)
	);

	$wp_customize->add_setting(
		'square_slider_subtitle'.$i,
		array(
			'default'			=> __('Create website in no time','square'),
			'transport'         => 'postMessage',
			'sanitize_callback' => 'square_sanitize_text'
		)
	);

	$wp_customize->add_control(
		'square_slider_subtitle'.$i,
		array(
			'settings'		=> 'square_slider_subtitle'.$i,
			'section'		=> 'square_slider_sec',
			'type'			=> 'textarea',
			'label'			=> __( 'Caption SubTitle', 'square' )
		)
	);

	$wp_customize->add_setting(
		'square_slider_image'.$i,
		array(
			'default'			=> get_template_directory_uri().'/images/bg.jpg',
			'sanitize_callback' => 'esc_url_raw'
		)
	);

	$wp_customize->add_control(
	    new WP_Customize_Image_Control(
	        $wp_customize,
	        'square_slider_image'.$i,
	        array(
	            'label'    => __( 'Slider Image', 'square' ),
	            'settings' => 'square_slider_image'.$i,
	            'section'  => 'square_slider_sec',
	            'description'   => __( 'Recommended Image Size: 1800X800px', 'square' )
	        )
	    )
	);
		
	}

	/*============FEATURED SECTION============*/

	//FEATURED PAGES
	$wp_customize->add_section(
		'square_featured_page_sec',
		array(
			'title'			=> __( 'Featured Section', 'square' ),
			'panel'         => 'square_home_settings_panel'
		)
	);

	$wp_customize->add_setting(
		'square_enable_featured_link',
		array(
			'default'			=> 1,
			'sanitize_callback' => 'absint'
		)
	);

	$wp_customize->add_control(
			'square_enable_featured_link',
			array(
				'settings'		=> 'square_enable_featured_link',
				'section'		=> 'square_featured_page_sec',
				'label'			=> __( 'Enable Read More link ', 'square' ),
				'type'       	=> 'checkbox',
			)
	);

	for( $i = 1; $i < 4; $i++ ){

	$wp_customize->add_setting(
		'square_featured_header'.$i,
		array(
			'default'			=> '',
			'sanitize_callback' => 'square_sanitize_text'
		)
	);

	$wp_customize->add_control(
		new Square_Customize_Heading(
			$wp_customize,
			'square_featured_header'.$i,
			array(
				'settings'		=> 'square_featured_header'.$i,
				'section'		=> 'square_featured_page_sec',
				'label'			=> __( 'Featured Page ', 'square' ).$i
			)
		)
	);

	$wp_customize->add_setting(
		'square_featured_page'.$i,
		array(
			'default'			=> $square_page,
			'sanitize_callback' => 'absint'
		)
	);

	$wp_customize->add_control(
		'square_featured_page'.$i,
		array(
			'settings'		=> 'square_featured_page'.$i,
			'section'		=> 'square_featured_page_sec',
			'type'			=> 'dropdown-pages',
			'label'			=> __( 'Select a Page', 'square' )
		)
	);

	$wp_customize->add_setting(
		'square_featured_page_icon'.$i,
		array(
			'default'			=> 'fa fa-bell',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'square_sanitize_text'
		)
	);

	$wp_customize->add_control(
		new Square_Fontawesome_Icon_Chooser(
		$wp_customize,
		'square_featured_page_icon'.$i,
		array(
			'settings'		=> 'square_featured_page_icon'.$i,
			'section'		=> 'square_featured_page_sec',
			'label'			=> __( 'FontAwesome Icon', 'square' ),
			'type'			=> 'icon'
		)
		)
	);
	}

	/*============ABOUT SECTION============*/

	$wp_customize->add_section(
		'square_about_sec',
		array(
			'title'			=> __( 'About Us Section', 'square' ),
			'panel'         => 'square_home_settings_panel'
		)
	);

	$wp_customize->add_setting(
		'square_disable_about_sec',
		array(
			'default'			=> 0,
			'sanitize_callback' => 'absint'
		)
	);

	$wp_customize->add_control(
			'square_disable_about_sec',
			array(
				'settings'		=> 'square_disable_about_sec',
				'section'		=> 'square_about_sec',
				'label'			=> __( 'Disable About Section ', 'square' ),
				'type'       	=> 'checkbox',
			)
	);

	$wp_customize->add_setting(
		'square_about_header',
		array(
			'default'			=> '',
			'sanitize_callback' => 'square_sanitize_text'
		)
	);

	$wp_customize->add_control(
		new Square_Customize_Heading(
			$wp_customize,
			'square_about_header',
			array(
				'settings'		=> 'square_about_header',
				'section'		=> 'square_about_sec',
				'label'			=> __( 'About Page ', 'square' )
			)
		)
	);

	$wp_customize->add_setting(
		'square_about_page',
		array(
			'default'			=> '',
			'sanitize_callback' => 'absint'
		)
	);

	$wp_customize->add_control(
		'square_about_page',
		array(
			'settings'		=> 'square_about_page',
			'section'		=> 'square_about_sec',
			'type'			=> 'dropdown-pages',
			'label'			=> __( 'Select a Page', 'square' )
		)
	);

	$wp_customize->add_setting(
		'square_about_image_header',
		array(
			'default'			=> '',
			'sanitize_callback' => 'square_sanitize_text'
		)
	);

	$wp_customize->add_control(
		new Square_Customize_Heading(
			$wp_customize,
			'square_about_image_header',
			array(
				'settings'		=> 'square_about_image_header',
				'section'		=> 'square_about_sec',
				'label'			=> __( 'About Page Stack Images', 'square' )
			)
		)
	);

	$wp_customize->add_setting(
		'square_about_image_stack',
		array(
			'default'			=> '',
			'sanitize_callback' => 'square_sanitize_text'
		)
	);

	$wp_customize->add_control(
		new Square_Display_Gallery_Control(
			$wp_customize,
			'square_about_image_stack',
		array(
			'settings'		=> 'square_about_image_stack',
			'section'		=> 'square_about_sec',
			'label'			=> __( 'About Us Stack Image', 'square' ),
			'description'   => __( 'Recommended Image Size: 400X420px <br/> Leave the gallery empty for Full Width Text', 'square' )
		)
		)
	);

	/*============ABOUT SECTION============*/

	$wp_customize->add_section(
		'square_tab_sec',
		array(
			'title'			=> __( 'Tab Section', 'square' ),
			'panel'         => 'square_home_settings_panel'
		)
	);

	$wp_customize->add_setting(
		'square_disable_tab_sec',
		array(
			'default'			=> 0,
			'sanitize_callback' => 'absint'
		)
	);

	$wp_customize->add_control(
			'square_disable_tab_sec',
			array(
				'settings'		=> 'square_disable_tab_sec',
				'section'		=> 'square_tab_sec',
				'label'			=> __( 'Disable Tab Section ', 'square' ),
				'type'       	=> 'checkbox',
			)
	);

	for($i = 1; $i < 6; $i++){

		$wp_customize->add_setting(
			'square_tab_header'.$i,
			array(
				'default'			=> '',
				'sanitize_callback' => 'square_sanitize_text'
			)
		);

		$wp_customize->add_control(
			new Square_Customize_Heading(
				$wp_customize,
				'square_tab_header'.$i,
				array(
					'settings'		=> 'square_tab_header'.$i,
					'section'		=> 'square_tab_sec',
					'label'			=> __( 'Tab ', 'square' ).$i
				)
			)
		);

		$wp_customize->add_setting(
			'square_tab_title'.$i,
			array(
				'default'			=> '',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'square_sanitize_text'
			)
		);

		$wp_customize->add_control(
			'square_tab_title'.$i,
			array(
				'settings'		=> 'square_tab_title'.$i,
				'section'		=> 'square_tab_sec',
				'type'			=> 'text',
				'label'			=> __( 'Tab Title', 'square' )
			)
		);

		$wp_customize->add_setting(
			'square_tab_icon'.$i,
			array(
				'default'			=> 'fa fa-bell',
				'transport'         => 'postMessage',
				'sanitize_callback' => 'square_sanitize_text'
			)
		);

		$wp_customize->add_control(
			new Square_Fontawesome_Icon_Chooser(
			$wp_customize,
			'square_tab_icon'.$i,
			array(
				'settings'		=> 'square_tab_icon'.$i,
				'section'		=> 'square_tab_sec',
				'type'			=> 'icon',
				'label'			=> __( 'FontAwesome Icon', 'square' ),
			)
			)
		);

		$wp_customize->add_setting(
			'square_tab_page'.$i,
			array(
				'default'			=> '',
				'sanitize_callback' => 'absint'
			)
		);

		$wp_customize->add_control(
			'square_tab_page'.$i,
			array(
				'settings'		=> 'square_tab_page'.$i,
				'section'		=> 'square_tab_sec',
				'type'			=> 'dropdown-pages',
				'label'			=> __( 'Select a Page', 'square' )
			)
		);

	}

	/*============CLIENTS LOGO SECTION============*/
	$wp_customize->add_section(
		'square_logo_sec',
		array(
			'title'			=> __( 'Clients Logo Section', 'square' ),
			'panel'         => 'square_home_settings_panel'
		)
	);

	$wp_customize->add_setting(
		'square_disable_logo_sec',
		array(
			'default'			=> 0,
			'sanitize_callback' => 'absint'
		)
	);

	$wp_customize->add_control(
			'square_disable_logo_sec',
			array(
				'settings'		=> 'square_disable_logo_sec',
				'section'		=> 'square_logo_sec',
				'label'			=> __( 'Disable Client Logo Section ', 'square' ),
				'type'       	=> 'checkbox',
			)
	);

	$wp_customize->add_setting(
		'square_logo_header',
		array(
			'default'			=> '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'square_sanitize_text'
		)
	);

	$wp_customize->add_control(
		new Square_Customize_Heading(
			$wp_customize,
			'square_logo_header',
			array(
				'settings'		=> 'square_logo_header',
				'section'		=> 'square_logo_sec',
				'label'			=> __( 'Section Title & Logo', 'square' )
			)
		)
	);

	$wp_customize->add_setting(
		'square_logo_title',
		array(
			'default'			=> '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'square_sanitize_text'
		)
	);

	$wp_customize->add_control(
		'square_logo_title',
		array(
			'settings'		=> 'square_logo_title',
			'section'		=> 'square_logo_sec',
			'type'			=> 'text',
			'label'			=> __( 'Title', 'square' )
		)
	);

	//CLIENTS LOGOS
	$wp_customize->add_setting(
		'square_client_logo_image',
		array(
			'default'			=> '',
			'sanitize_callback' => 'square_sanitize_text'
		)
	);

	$wp_customize->add_control(
		new Square_Display_Gallery_Control(
			$wp_customize,
			'square_client_logo_image',
		array(
			'settings'		=> 'square_client_logo_image',
			'section'		=> 'square_logo_sec',
			'label'			=> __( 'Upload Clients Logos', 'square' ),
			'description'   => __( 'Recommended Image Size: 220X90px', 'square' )
		)
		)
	);

	/*============SOCIAL ICONS SECTION============*/
	$wp_customize->add_section(
		'square_social_sec',
		array(
			'title'			=> __( 'Footer Social Icons', 'square' ),
		)
	);

	$wp_customize->add_setting(
		'square_social_facebook',
		array(
			'default'			=> '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_url_raw'
		)
	);

	$wp_customize->add_control(
		'square_social_facebook',
		array(
			'settings'		=> 'square_social_facebook',
			'section'		=> 'square_social_sec',
			'type'			=> 'text',
			'label'			=> __( 'Facebook', 'square' )
		)
	);

	$wp_customize->add_setting(
		'square_social_twitter',
		array(
			'default'			=> '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_url_raw'
		)
	);

	$wp_customize->add_control(
		'square_social_twitter',
		array(
			'settings'		=> 'square_social_twitter',
			'section'		=> 'square_social_sec',
			'type'			=> 'text',
			'label'			=> __( 'Twitter', 'square' )
		)
	);

	$wp_customize->add_setting(
		'square_social_google_plus',
		array(
			'default'			=> '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_url_raw'
		)
	);

	$wp_customize->add_control(
		'square_social_google_plus',
		array(
			'settings'		=> 'square_social_google_plus',
			'section'		=> 'square_social_sec',
			'type'			=> 'text',
			'label'			=> __( 'Google Plus', 'square' )
		)
	);

	$wp_customize->add_setting(
		'square_social_pinterest',
		array(
			'default'			=> '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_url_raw'
		)
	);

	$wp_customize->add_control(
		'square_social_pinterest',
		array(
			'settings'		=> 'square_social_pinterest',
			'section'		=> 'square_social_sec',
			'type'			=> 'text',
			'label'			=> __( 'Pinterest', 'square' )
		)
	);

	$wp_customize->add_setting(
		'square_social_youtube',
		array(
			'default'			=> '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_url_raw'
		)
	);

	$wp_customize->add_control(
		'square_social_youtube',
		array(
			'settings'		=> 'square_social_youtube',
			'section'		=> 'square_social_sec',
			'type'			=> 'text',
			'label'			=> __( 'Youtube', 'square' )
		)
	);

	$wp_customize->add_setting(
		'square_social_linkedin',
		array(
			'default'			=> '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_url_raw'
		)
	);

	$wp_customize->add_control(
		'square_social_linkedin',
		array(
			'settings'		=> 'square_social_linkedin',
			'section'		=> 'square_social_sec',
			'type'			=> 'text',
			'label'			=> __( 'Linkedin', 'square' )
		)
	);

	$wp_customize->add_setting(
		'square_social_instagram',
		array(
			'default'			=> '',
			'transport'         => 'postMessage',
			'sanitize_callback' => 'esc_url_raw'
		)
	);

	$wp_customize->add_control(
		'square_social_instagram',
		array(
			'settings'		=> 'square_social_instagram',
			'section'		=> 'square_social_sec',
			'type'			=> 'text',
			'label'			=> __( 'Instagram', 'square' )
		)
	);

	/*============IMPORTANT LINKS============*/
	$wp_customize->add_section(
		'square_implink_section',
		array(
			'title' 			=> __( 'Important Links', 'square' ),
			'priority'			=> 1
		)
	);

	$wp_customize->add_setting(
		'square_imp_links',
		array(
			'sanitize_callback' => 'square_sanitize_text'
		)
	);

	$wp_customize->add_control(
		new Square_Info_Text( 
			$wp_customize,
			'square_imp_links',
			array(
				'settings'		=> 'square_imp_links',
				'section'		=> 'square_implink_section',
				'description'	=> '<a class="ht-implink" href="https://hashthemes.com/documentation/square-documentation/" target="_blank">'.__('Documentation', 'square').'</a><a class="ht-implink" href="http://demo.hashthemes.com/square/" target="_blank">'.__('Live Demo', 'square').'</a><a class="ht-implink" href="https://hashthemes.com/support/" target="_blank">'.__('Support Forum', 'square').'</a><a class="ht-implink" href="https://www.facebook.com/hashtheme/" target="_blank">'.__('Like Us in Facebook', 'square').'</a>',
			)
		)
	);

	$wp_customize->add_setting(
		'square_rate_us',
		array(
			'sanitize_callback' => 'square_sanitize_text'
		)
	);

	$wp_customize->add_control(
		new Square_Info_Text( 
			$wp_customize,
			'square_rate_us',
			array(
				'settings'		=> 'square_rate_us',
				'section'		=> 'square_implink_section',
				'description'	=> sprintf(__( 'Please do rate our theme if you liked it %s', 'square'), '<a class="ht-implink" href="https://wordpress.org/support/theme/square/reviews/?filter=5" target="_blank">Rate/Review</a>' ),
			)
		)
	);
}
add_action( 'customize_register', 'square_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function square_customize_preview_js() {
	wp_enqueue_script( 'square_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20130508', true );
}
add_action( 'customize_preview_init', 'square_customize_preview_js' );


function square_customizer_script() {
	wp_enqueue_script( 'square-customizer-script', get_template_directory_uri() .'/inc/js/customizer-scripts.js', array('jquery'),'09092016', true  );
	wp_enqueue_script( 'square-customizer-chosen-script', get_template_directory_uri() .'/inc/js/chosen.jquery.js', array('jquery'),'1.4.1', true  );
	wp_enqueue_style( 'square-customizer-chosen-style', get_template_directory_uri() .'/inc/css/chosen.css');
	wp_enqueue_style( 'square-customizer-font-awesome', get_template_directory_uri() .'/css/font-awesome.css');
	wp_enqueue_style( 'square-customizer-style', get_template_directory_uri() .'/inc/css/customizer-style.css');	
}
add_action( 'customize_controls_enqueue_scripts', 'square_customizer_script' );


if( class_exists( 'WP_Customize_Control' ) ):	

class Square_Customize_Heading extends WP_Customize_Control {

    public function render_content() {
    	?>

        <?php if ( !empty( $this->label ) ) : ?>
            <h3 class="square-accordion-section-title"><?php echo esc_html( $this->label ); ?></h3>
        <?php endif; ?>
    <?php }
}

class Square_Dropdown_Chooser extends WP_Customize_Control{
	public function render_content(){
		if ( empty( $this->choices ) )
                return;
		?>
            <label>
                <span class="customize-control-title">
                	<?php echo esc_html( $this->label ); ?>
                </span>

                <?php if($this->description){ ?>
	            <span class="description customize-control-description">
	            	<?php echo wp_kses_post($this->description); ?>
	            </span>
	            <?php } ?>

                <select class="hs-chosen-select" <?php $this->link(); ?>>
                    <?php
                    foreach ( $this->choices as $value => $label )
                        echo '<option value="' . esc_attr( $value ) . '"' . selected( $this->value(), $value, false ) . '>' . esc_html( $label ) . '</option>';
                    ?>
                </select>
            </label>
		<?php
	}
}

class Square_Fontawesome_Icon_Chooser extends WP_Customize_Control{
	public $type = 'icon';

	public function render_content(){
		?>
            <label>
                <span class="customize-control-title">
                <?php echo esc_html( $this->label ); ?>
                </span>

                <?php if($this->description){ ?>
	            <span class="description customize-control-description">
	            	<?php echo wp_kses_post($this->description); ?>
	            </span>
	            <?php } ?>

                <div class="square-selected-icon">
                	<i class="fa <?php echo esc_attr($this->value()); ?>"></i>
                	<span><i class="fa fa-angle-down"></i></span>
                </div>

                <ul class="square-icon-list clearfix">
                	<?php
                	$square_font_awesome_icon_array = square_font_awesome_icon_array();
                	foreach ($square_font_awesome_icon_array as $square_font_awesome_icon) {
							$icon_class = $this->value() == $square_font_awesome_icon ? 'icon-active' : '';
							echo '<li class='.$icon_class.'><i class="'.$square_font_awesome_icon.'"></i></li>';
						}
                	?>
                </ul>
                <input type="hidden" value="<?php $this->value(); ?>" <?php $this->link(); ?> />
            </label>
		<?php
	}
}

class Square_Display_Gallery_Control extends WP_Customize_Control{
	public $type = 'gallery';
	 
	public function render_content() {
	?>
	<label>
		<span class="customize-control-title">
			<?php echo esc_html( $this->label ); ?>
		</span>

		<?php if($this->description){ ?>
			<span class="description customize-control-description">
			<?php echo wp_kses_post($this->description); ?>
			</span>
		<?php } ?>

		<div class="gallery-screenshot clearfix">
    	<?php
        	{
        	$ids = explode( ',', $this->value() );
            	foreach ( $ids as $attachment_id ) {
                	$img = wp_get_attachment_image_src( $attachment_id, 'thumbnail' );
                	echo '<div class="screen-thumb"><img src="' . esc_url($img[0]) . '" /></div>';
            	}
        	}
    	?>
    	</div>

    	<input id="edit-gallery" class="button upload_gallery_button" type="button" value="<?php _e('Add/Edit Gallery','square') ?>" />
		<input id="clear-gallery" class="button upload_gallery_button" type="button" value="<?php _e('Clear','square') ?>" />
		<input type="hidden" class="gallery_values" <?php echo $this->link() ?> value="<?php echo esc_attr( $this->value() ); ?>">
	</label>
	<?php
	}
}

class Square_Info_Text extends WP_Customize_Control{

    public function render_content(){
    ?>
	    <span class="customize-control-title">
			<?php echo esc_html( $this->label ); ?>
		</span>

		<?php if($this->description){ ?>
			<span class="description customize-control-description">
			<?php echo wp_kses_post($this->description); ?>
			</span>
		<?php }
    }

}

endif;


//SANITIZATION FUNCTIONS
function square_sanitize_text( $input ) {
    return wp_kses_post( force_balance_tags( $input ) );
}

function square_sanitize_checkbox( $input ) {
    if ( $input == 1 ) {
        return 1;
    } else {
        return '';
    }
}

function square_sanitize_integer( $input ) {
    if( is_numeric( $input ) ) {
        return intval( $input );
    }
}

function square_sanitize_choices( $input, $setting ) {
    global $wp_customize;
 
    $control = $wp_customize->get_control( $setting->id );
 
    if ( array_key_exists( $input, $control->choices ) ) {
        return $input;
    } else {
        return $setting->default;
    }
}
