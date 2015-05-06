<?php

/**
 * Plugin Name: Better Facebook Page Box
 * Plugin URI: www.danielederosa.de
 * Description: Add the NEW Facebook Page Plugin to your preferred location. Easy, beautiful and with many options!
 * Version: 1.0
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

if ( !is_multisite() ) {
  TGM_Plugin_Activation::get_instance()->update_dismiss();
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
 * Load Redux Framework from WP Plugin Repository
 */

add_action( 'tgmpa_register', 'dd_register_required_plugins' );

function dd_register_required_plugins() {

    $plugins = array(

        array(
            'name'      => 'Redux Framework',
            'slug'      => 'redux-framework',
            'required'  => true,
        ),

    );

    $config = array(
        'default_path' => '',                      // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => false,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
            'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
            'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
            'notice_can_install_required'     => _n_noop( 'Better Facebook Page Box requires this plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'Better Facebook Page Box recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
            'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
            'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
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
function dd_put_facebook_sdk() { ?>

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
        '"//connect.facebook.net/de_DE/sdk.js#xfbml=1&version=v2.3&appId=430209653814231";' +
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
