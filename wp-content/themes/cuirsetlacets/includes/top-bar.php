<nav class="navbar navbar-default" role="navigation">
<?php
	global $cl_user;
	if ( $cl_user->language === 'fr' ) {
		$lang_menu = 'menu-principal';
	} elseif ( $cl_user->language === 'en' ) {
		$lang_menu = 'main-menu';
	} else {
		echo "menu not defined...";
		$lang_menu = "menu-principal";
	}

    wp_nav_menu( array(
        'theme_location'    => $lang_menu,
        'depth'             => 2,
        'container'         => 'div',
        'container_class'   => 'collapse navbar-collapse navbar-ex1-collapse',
        'menu_class'        => 'nav navbar-nav',
        'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
        'walker'            => new wp_bootstrap_navwalker())
    );
?>
</nav>