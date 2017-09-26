/**
 * Square Custom JS
 *
 * @package square
 *
 * Distributed under the MIT license - http://opensource.org/licenses/MIT
 */

jQuery(function($){

	$('#sq-bx-slider').bxSlider({
		'pager':false,
		'auto' : true,
		'mode' : 'fade',
		'pause' : 5000,
		'prevText' : '<i class="fa fa-angle-left"></i>',
		'nextText' : '<i class="fa fa-angle-right"></i>',
		'adaptiveHeight' : true
	});

    $(".sq_client_logo_slider").owlCarousel({
      autoPlay: 4000, 
      items : 5,
      itemsTablet: [768,3],
      itemsMobile : [479,2],
      pagination : false
    });

    $(".sq-tab-pane:first").show();
    $(".sq-tab li:first").addClass('sq-active');

    $(".sq-tab li a").click(function(){
    	var tab = $(this).attr('href');
    	$(".sq-tab li").removeClass('sq-active');
    	$(this).parent('li').addClass('sq-active');
    	$(".sq-tab-pane").hide();
    	$(tab).show();
    	return false;
    });

    $(window).scroll(function(){
    	var scrollTop = $(window).scrollTop();
    	if( scrollTop > 0 ){
    		$('#sq-masthead').addClass('sq-scrolled');
    	}else{
    		$('#sq-masthead').removeClass('sq-scrolled');
    	}
    });

    $('.sq-menu > ul').superfish({
		delay:       500,                            
		animation:   {opacity:'show',height:'show'},  
		speed:       'fast'                         
	});

    $('.sq-toggle-nav').click(function(){
    	$('#sq-site-navigation').slideToggle();
    });

});

if( jQuery('#sq-elasticstack').length > 0 ){
	new ElastiStack( document.getElementById( 'sq-elasticstack' ), {
		// distDragBack: if the user stops dragging the image in a area that does not exceed [distDragBack]px 
		// for either x or y then the image goes back to the stack 
		distDragBack : 200,
		// distDragMax: if the user drags the image in a area that exceeds [distDragMax]px 
		// for either x or y then the image moves away from the stack 
		distDragMax : 450,
		// callback
		onUpdateStack : function( current ) { return false; }
	} );
}