<?php
/**
 * The template for displaying the footer.
 *
 * @package Square
 */

?>

	</div><!-- #content -->

	<footer id="sq-colophon" class="sq-site-footer">
		<?php if(is_active_sidebar('square-footer1') || is_active_sidebar('square-footer2') || is_active_sidebar('square-footer3') || is_active_sidebar('square-footer4') ): ?>
		<div id="sq-top-footer">
			<div class="sq-container">
				<div class="sq-top-footer sq-clearfix">
					<div class="sq-footer sq-footer1">
						<?php if(is_active_sidebar('square-footer1')): 
							dynamic_sidebar('square-footer1');
						endif;
						?>	
					</div>

					<div class="sq-footer sq-footer2">
						<?php if(is_active_sidebar('square-footer2')): 
							dynamic_sidebar('square-footer2');
						endif;
						?>	
					</div>

					<div class="sq-footer sq-footer3">
						<?php if(is_active_sidebar('square-footer3')): 
							dynamic_sidebar('square-footer3');
						endif;
						?>	
					</div>

					<div class="sq-footer sq-footer4">
						<?php if(is_active_sidebar('square-footer4')): 
							dynamic_sidebar('square-footer4');
						endif;
						?>	
					</div>
				</div>
			</div>
		</div>
		<?php endif; ?>

		<?php if(is_active_sidebar('square-about-footer')): ?>
		<div id="sq-middle-footer">
			<div class="sq-container">
				<?php 
					dynamic_sidebar('square-about-footer');
				?>
			</div>
		</div>
		<?php endif; ?>

		<div id="sq-bottom-footer">
			<div class="sq-container sq-clearfix">
				<div class="sq-site-info">
					<?php printf( esc_html__( 'WordPress Theme', 'square' ) ); ?>
					<span class="sep"> | </span>
					<?php printf( esc_html__( '%1$s by %2$s', 'square' ), '<a target="_blank" href="http://hashthemes.com/wordpress-theme/square/">Square</a>', 'Hash Themes' ); ?>
				</div>

				<div class="sq-site-social">
				<?php 
					$facebook = get_theme_mod('square_social_facebook');
					$twitter = get_theme_mod('square_social_twitter');
					$google_plus = get_theme_mod('square_social_google_plus');
					$pinterest = get_theme_mod('square_social_pinterest');
					$youtube = get_theme_mod('square_social_youtube');
					$linkedin = get_theme_mod('square_social_linkedin');
					$instagram = get_theme_mod('square_social_instagram');

					if($facebook)
						echo '<a class="sq-facebook" href="'.esc_url( $facebook ).'" target="_blank"><i class="fa fa-facebook"></i></a>';

					if($twitter)
						echo '<a class="sq-twitter" href="'.esc_url( $twitter ).'" target="_blank"><i class="fa fa-twitter"></i></a>';

					if($google_plus)
						echo '<a class="sq-googleplus" href="'.esc_url( $google_plus ).'" target="_blank"><i class="fa fa-google-plus"></i></a>';

					if($pinterest)
						echo '<a class="sq-pinterest" href="'.esc_url( $pinterest ).'" target="_blank"><i class="fa fa-pinterest"></i></a>';

					if($youtube)
						echo '<a class="sq-youtube" href="'.esc_url( $youtube ).'" target="_blank"><i class="fa fa-youtube"></i></a>';

					if($linkedin)
						echo '<a class="sq-linkedin" href="'.esc_url( $linkedin ).'" target="_blank"><i class="fa fa-linkedin"></i></a>';

					if($instagram)
						echo '<a class="sq-instagram" href="'.esc_url( $instagram ).'" target="_blank"><i class="fa fa-instagram"></i></a>';
				?>
				</div>
			</div>
		</div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
