<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Thirteen
 * @since Twenty Thirteen 1.0
 */
?>
        </div>
            <footer id="colophon" class="site-footer leather-effect" role="contentinfo">
                <div class="site-info">
                    <div class="row">
                        <div class="col-sm-4">
                            <a href="http://www.facebook.com/taillejarret.mesnieedemontinius" name="@facebook">
                                <img class="social img-circle" src="<?php echo WP_THEME; ?>/img/social/facebook.png" alt="Cuirs et Lacets @facebook" width="36" height="36" />
                            </a>
                        </div>
                        <div class="col-md-4" style="text-align: center">
                            <small>
                                <a href="<?php echo CL_HOME; ?>"><?php _cl( "Page d'accueil", 'Home page' ); ?></a> -
                                <a href="<?php echo CL_HOME; ?>"><?php _cl( 'Mentions légales', 'Imprint' ); ?></a> -
                                <a href="<?php echo CL_HOME . __cl( '/eng/', '/fr/' ); ?>"><?php _cl( 'English version', 'Version Francaise' ); ?></a>
                            </small>
                            
                        </div>
                        <div class="col-md-4" style="text-align: right">
                            <small>
                                <u><b>Cuirs et Lacets :</b></u><br />
                                <span class="siret">Siret : 800 092 496 00010</span>
                            </small>
                        </div>
                    </div>
                </div><!-- .site-info -->
            </footer><!-- #colophon -->
	</div><!-- #container id="page" -->

	<?php wp_footer(); ?>
    </body>
</html>