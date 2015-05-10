<?php

/**
 * Plugin Name: Better Facebook Page Box
 * Plugin URI: www.danielederosa.de
 * Description: Add the NEW Facebook Page Plugin to your preferred location. Easy, beautiful and with many options!
 * Version: 1.2
 * Author: Daniele De Rosa
 * Author URI: www.danielederosa.de
 **/

// Security
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Options requirements
 */

if ( file_exists( dirname( __FILE__ ) . '/admin/class-tgm-plugin-activation.php' ) ) {
  require_once( dirname( __FILE__ ) . '/admin/class-tgm-plugin-activation.php' );
}
if ( !isset( $dd_options ) && file_exists( dirname( __FILE__ ) . '/admin/options.php' ) ) {
  require_once( dirname( __FILE__ ) . '/admin/options.php' );
}

add_action('init', 'dd_update_dismiss');
function dd_update_dismiss() {
  if ( defined( 'TGMPA_VERSION' ) ) {
    $tgmpa = call_user_func( array( get_class( $GLOBALS['tgmpa'] ), 'get_instance' ) );
    $tgmpa->update_dismiss();
  }
  else {
    TGM_Plugin_Activation::get_instance()->update_dismiss();
  }
}

/**
 * Remove Redux Demo Notices
 */

add_action('init', 'dd_remove_redux_demo');
function dd_remove_redux_demo() {
  if ( class_exists('ReduxFrameworkPlugin') ) {
      remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2 );
  }
  if ( class_exists('ReduxFrameworkPlugin') ) {
      remove_action('admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );
  }
}

/**
 * Loading Redux Framework from WP Plugin Repository
 */

add_action( 'tgmpa_register', 'dd_my_theme_register_required_plugins' );

/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function dd_my_theme_register_required_plugins() {
	/*
	 * Array of plugin arrays. Required keys are name and slug.
	 * If the source is NOT from the .org repo, then source is also required.
	 */
	$plugins = array(

		// This is an example of how to include a plugin pre-packaged with a theme.
		array(
        'name'      => 'Redux Framework',
        'slug'      => 'redux-framework',
        'required'  => true
    ),

	);

	/*
	 * Array of configuration settings. Amend each line as needed.
	 *
	 * TGMPA will start providing localized text strings soon. If you already have translations of our standard
	 * strings available, please help us make TGMPA even better by giving us access to these translations or by
	 * sending in a pull-request with .po file(s) with the translations.
	 *
	 * Only uncomment the strings in the config array if you want to customize the strings.
	 *
	 * Some of the strings are wrapped in a sprintf(), so see the comments at the
	 * end of each line for what each argument will be.
	 */
	$config = array(
		'id'           => 'tgmpa',                 // Unique ID for hashing notices for multiple instances of TGMPA.
		'default_path' => '',                      // Default absolute path to pre-packaged plugins.
		'menu'         => 'tgmpa-install-plugins', // Menu slug.
		'parent_slug'  => 'themes.php',            // Parent menu slug.
		'capability'   => 'edit_theme_options',    // Capability needed to view plugin install page, should be a capability associated with the parent menu used.
		'has_notices'  => true,                    // Show admin notices or not.
		'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
		'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
		'is_automatic' => true,                   // Automatically activate plugins after installation or not.
		'message'      => '',                      // Message to output right before the plugins table.
		'strings'      => array(
			'page_title'                      => __( 'Install Required Plugins', 'theme-slug' ),
			'menu_title'                      => __( 'Install Plugins', 'theme-slug' ),
			'installing'                      => __( 'Installing Plugin: %s', 'theme-slug' ), // %s = plugin name.
			'oops'                            => __( 'Something went wrong with the plugin API.', 'theme-slug' ),
			'notice_can_install_required'     => _n_noop(
				'Better Facebook Page Box requires the following plugin: %1$s.',
				'Better Facebook Page Box requires the following plugins: %1$s.',
				'theme-slug'
			), // %1$s = plugin name(s).
			'notice_can_install_recommended'  => _n_noop(
				'Better Facebook Page Box recommends the following plugin: %1$s.',
				'Better Facebook Page Box recommends the following plugins: %1$s.',
				'theme-slug'
			), // %1$s = plugin name(s).
			'notice_cannot_install'           => _n_noop(
				'Sorry, but you do not have the correct permissions to install the %1$s plugin.',
				'Sorry, but you do not have the correct permissions to install the %1$s plugins.',
				'theme-slug'
			), // %1$s = plugin name(s).
			'notice_ask_to_update'            => _n_noop(
				'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.',
				'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.',
				'theme-slug'
			), // %1$s = plugin name(s).
			'notice_ask_to_update_maybe'      => _n_noop(
				'There is an update available for: %1$s.',
				'There are updates available for the following plugins: %1$s.',
				'theme-slug'
			), // %1$s = plugin name(s).
			'notice_cannot_update'            => _n_noop(
				'Sorry, but you do not have the correct permissions to update the %1$s plugin.',
				'Sorry, but you do not have the correct permissions to update the %1$s plugins.',
				'theme-slug'
			), // %1$s = plugin name(s).
			'notice_can_activate_required'    => _n_noop(
				'The following required plugin is currently inactive: %1$s.',
				'The following required plugins are currently inactive: %1$s.',
				'theme-slug'
			), // %1$s = plugin name(s).
			'notice_can_activate_recommended' => _n_noop(
				'The following recommended plugin is currently inactive: %1$s.',
				'The following recommended plugins are currently inactive: %1$s.',
				'theme-slug'
			), // %1$s = plugin name(s).
			'notice_cannot_activate'          => _n_noop(
				'Sorry, but you do not have the correct permissions to activate the %1$s plugin.',
				'Sorry, but you do not have the correct permissions to activate the %1$s plugins.',
				'theme-slug'
			), // %1$s = plugin name(s).
			'install_link'                    => _n_noop(
				'Begin installing plugin',
				'Begin installing plugins',
				'theme-slug'
			),
			'update_link' 					  => _n_noop(
				'Begin updating plugin',
				'Begin updating plugins',
				'theme-slug'
			),
			'activate_link'                   => _n_noop(
				'Begin activating plugin',
				'Begin activating plugins',
				'theme-slug'
			),
			'return'                          => __( 'Return to Required Plugins Installer', 'theme-slug' ),
			'plugin_activated'                => __( 'Plugin activated successfully.', 'theme-slug' ),
			'activated_successfully'          => __( 'The following plugin was activated successfully:', 'theme-slug' ),
			'plugin_already_active'           => __( 'No action taken. Plugin %1$s was already active.', 'theme-slug' ),  // %1$s = plugin name(s).
			'plugin_needs_higher_version'     => __( 'Plugin not activated. A higher version of %s is needed for this theme. Please update the plugin.', 'theme-slug' ),  // %1$s = plugin name(s).
			'complete'                        => __( 'All plugins installed and activated successfully. %1$s', 'theme-slug' ), // %s = dashboard link.
			'contact_admin'                   => __( 'Please contact the administrator of this site for help.', 'tgmpa' ),

			'nag_type'                        => 'updated', // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
		)
	);

	tgmpa( $plugins, $config );

}


/**
 * Load scripts
 */

add_action('wp_enqueue_scripts', 'dd_load_scripts');
function dd_load_scripts() {
 if ('jquery' != true) {
   wp_enqueue_script('jquery');
 }
}

/**
 * Put Facebook SDK
 */

add_action('wp_footer', 'dd_put_facebook_sdk');
function dd_put_facebook_sdk() {
  global $dd_options;

  $dd_bfp_language = '';
  if ( $dd_options['option-language'] == 1 ) {
    $dd_bfp_language = 'en_US';
  } else if ( $dd_options['option-language'] == 2 ) {
    $dd_bfp_language = 'de_DE';
  } else if ( $dd_options['option-language'] == 3 ) {
    $dd_bfp_language = 'fr_FR';
  } else if ( $dd_options['option-language'] == 4 ) {
    $dd_bfp_language = 'es_LA';
  } else if ( $dd_options['option-language'] == 5 ) {
    $dd_bfp_language = 'pt_PT';
  } else if ( $dd_options['option-language'] == 6 ) {
    $dd_bfp_language = 'ru_RU';
  } else if ( $dd_options['option-language'] == 7 ) {
    $dd_bfp_language = 'cs_CZ';
  } else if ( $dd_options['option-language'] == 8 ) {
    $dd_bfp_language = 'ja_JP';
  } else if ( $dd_options['option-language'] == 9 ) {
    $dd_bfp_language = 'it_IT';
  }

  ?>

  <script>
    window.onload = function() {

      var fbRoot = '<div id="fb-root"></div>';
      var fbScript = document.createElement("script");

      jQuery('body').prepend(fbRoot);
      jQuery("#fb-root").after(jQuery("<script />", {
        html: '(function(d, s, id) {' +
        'var js, fjs = d.getElementsByTagName(s)[0];' +
        'if (d.getElementById(id)) return;' +
        'js = d.createElement(s); js.id = id;' +
        'js.src = ' +
        '"//connect.facebook.net/<?php echo $dd_bfp_language ?>/sdk.js#xfbml=1&version=v2.3&appId=430209653814231";' +
        'fjs.parentNode.insertBefore(js, fjs);' +
        '}(document, "script", "facebook-jssdk"));'
      }))
    }
  </script>


<?php }

/**
 * Show box on the end of posts
 */

add_filter('the_content', 'dd_bfp_end_of_posts');
function dd_bfp_end_of_posts($text) {
  global $post;
  global $dd_options;
  $content = "";

  if ( $dd_options['after-post'] ) {
    if( is_singular() && is_main_query() && is_single() ) {
  		$shortcode = do_shortcode('[dd_bfp_plugin]');;
  		$content .= $shortcode;
  	}
  }
  return $text . $content;
}

/**
 * Create widget
 */

add_action('widgets_init',
   create_function('', 'return register_widget("dd_facebook_widget");')
);
class dd_facebook_widget extends WP_Widget {

	/**
	 * Sets up the widgets name etc
	 */
	public function __construct() {
    $widget_ops = array('classname' => 'dd_facebook_widget', 'description' => 'Place the Facebook Page Plugin to your sidebar' );
    $this->WP_Widget('dd_facebook_widget', 'BFP Box', $widget_ops);
  }

	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
    extract($args, EXTR_SKIP);
    $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
    $text = empty($instance['text']) ? '' : $instance['text'];

    echo (isset($before_widget)?$before_widget:'');

    if (!empty($title))
      echo $before_title . $title . $after_title;;
    if (!empty($text))
      echo $text . '<br><br>';

    echo do_shortcode('[dd_bfp_plugin]');

    echo (isset($after_widget)?$after_widget:'');
	}

	/**
	 * Ouputs the options form on admin
	 *
	 * @param array $instance The widget options
	 */
	public function form( $instance ) {

   // PART 1: Extract the data from the instance variable
   $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
   $title = $instance['title'];
   $text = $instance['text'];

   // PART 2-3: Display the fields
   ?>
   <!-- PART 2: Widget Title field START -->
   <p>
    <label for="<?php echo $this->get_field_id('title'); ?>">Title:
      <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>"
             name="<?php echo $this->get_field_name('title'); ?>" type="text"
             value="<?php echo attribute_escape($title); ?>" />
    </label>
    </p>
    <!-- Widget Title field END -->

   <!-- PART 3: Widget Text field START -->
   <p>
    <label for="<?php echo $this->get_field_id('text'); ?>">Custom text:
      <input class="widefat" id="<?php echo $this->get_field_id('text'); ?>"
             name="<?php echo $this->get_field_name('text'); ?>" type="text"
             value="<?php echo attribute_escape($text); ?>" />
    </label>
    </p>
    <!-- Widget Text field END -->
   <?php

  }

	/**
	 * Processing widget options on save
	 *
	 * @param array $new_instance The new options
	 * @param array $old_instance The previous options
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
    $instance['title'] = $new_instance['title'];
    $instance['text'] = $new_instance['text'];
    return $instance;
	}
}


/**
 * Add shortcodes
 */

add_shortcode('dd_bfp_plugin', 'call_dd_bfp_plugin');

function call_dd_bfp_plugin($atts, $content = null) {

  global $dd_options;
  $facebookOutput = '<div class="fb-page" data-width="'. esc_html($dd_options['box-dimensions']['width']) .'" data-height="'. esc_html($dd_options['box-dimensions']['height']) .'" data-href="'.esc_html($dd_options['your-fb-url']).'" data-hide-cover="'. esc_html($dd_options['cover-photo']) .'" data-show-facepile="'. esc_html($dd_options['friend-faces']) .'" data-show-posts="'. esc_html($dd_options['page-posts']) .'"><div class="fb-xfbml-parse-ignore"><blockquote cite="'. esc_html($dd_options['your-fb-url']) .'"></blockquote></div></div>';

  if ( $dd_options['your-fb-url'] ) {
    return $facebookOutput;
  } else {
    return 'Your facebook page URL is missing.. :(';
  }

}

?>
