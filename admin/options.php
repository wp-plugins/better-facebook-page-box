<?php
    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: http://docs.reduxframework.com/
     */

    if ( ! class_exists( 'Redux' ) ) {
        return;
    }


    // This is your option name where all the Redux data is stored.
    $opt_name = "redux_demo";


    /*
     *
     * --> Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
     *
     */

    $sampleHTML = '';
    if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
        Redux_Functions::initWpFilesystem();

        global $wp_filesystem;

        $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
    }

    // Background Patterns Reader
    $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
    $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
    $sample_patterns      = array();

    if ( is_dir( $sample_patterns_path ) ) {

        if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) {
            $sample_patterns = array();

            while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                    $name              = explode( '.', $sample_patterns_file );
                    $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                    $sample_patterns[] = array(
                        'alt' => $name,
                        'img' => $sample_patterns_url . $sample_patterns_file
                    );
                }
            }
        }
    }

    /*
     *
     * --> Action hook examples
     *
     */

    // If Redux is running as a plugin, this will remove the demo notice and links
    // add_action( 'redux/loaded', 'remove_demo' );

    // Function to test the compiler hook and demo CSS output.
    // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
    //add_filter('redux/options/' . $opt_name . '/compiler', 'compiler_action', 10, 3);

    // Change the arguments after they've been declared, but before the panel is created
    //add_filter('redux/options/' . $opt_name . '/args', 'change_arguments' );

    // Change the default value of a field after it's been set, but before it's been useds
    //add_filter('redux/options/' . $opt_name . '/defaults', 'change_defaults' );

    // Dynamically add a section. Can be also used to modify sections/fields
    //add_filter('redux/options/' . $opt_name . '/sections', 'dynamic_section');


    /**
     * ---> SET ARGUMENTS
     * All the possible arguments for Redux.
     * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
     * */

    $theme = wp_get_theme(); // For use with some settings. Not necessary.

    $args = array(
        // TYPICAL -> Change these values as you need/desire
        'opt_name'             => $opt_name,
        // This is where your data is stored in the database and also becomes your global variable name.
        'display_name'         => 'Better Facebook Page Box',
        // Name that appears at the top of your panel
        'display_version'      => 'Version 1.0',
        // Version that appears at the top of your panel
        'menu_type'            => 'menu',
        //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
        'allow_sub_menu'       => true,
        // Show the sections below the admin menu item or not
        'menu_title'           => __( 'BFP Box', 'dd_bfp_plugin' ),
        'page_title'           => __( 'Better Facebook Page Box', 'dd_bfp_plugin' ),
        // You will need to generate a Google API key to use this feature.
        // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
        'google_api_key'       => '',
        // Set it you want google fonts to update weekly. A google_api_key value is required.
        'google_update_weekly' => false,
        // Must be defined to add google fonts to the typography module
        'async_typography'     => true,
        // Use a asynchronous font on the front end or font string
        //'disable_google_fonts_link' => true,                    // Disable this in case you want to create your own google fonts loader
        'admin_bar'            => false,
        // Show the panel pages on the admin bar
        'admin_bar_icon'       => 'dashicons-facebook',
        // Choose an icon for the admin bar menu
        'admin_bar_priority'   => 50,
        // Choose an priority for the admin bar menu
        'global_variable'      => 'dd_options',
        // Set a different name for your global variable other than the opt_name
        'dev_mode'             => false,
        // Show the time the page took to load, etc
        'update_notice'        => true,
        // If dev_mode is enabled, will notify developer of updated versions available in the GitHub Repo
        'customizer'           => true,
        // Enable basic customizer support
        //'open_expanded'     => true,                    // Allow you to start the panel in an expanded way initially.
        //'disable_save_warn' => true,                    // Disable the save warning when a user changes a field

        // OPTIONAL -> Give you extra features
        'page_priority'        => null,
        // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
        'page_parent'          => 'themes.php',
        // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
        'page_permissions'     => 'manage_options',
        // Permissions needed to access the options panel.
        'menu_icon'            => 'dashicons-facebook',
        // Specify a custom URL to an icon
        'last_tab'             => '',
        // Force your panel to always open to a specific tab (by id)
        'page_icon'            => 'icon-themes',
        // Icon displayed in the admin panel next to your menu_title
        'page_slug'            => '',
        // Page slug used to denote the panel, will be based off page title then menu title then opt_name if not provided
        'save_defaults'        => true,
        // On load save the defaults to DB before user clicks save or not
        'default_show'         => false,
        // If true, shows the default value next to each field that is not the default value.
        'default_mark'         => '',
        // What to print by the field's title if the value shown is default. Suggested: *
        'show_import_export'   => false,
        // Shows the Import/Export panel when not used as a field.

        // CAREFUL -> These options are for advanced use only
        'transient_time'       => 60 * MINUTE_IN_SECONDS,
        'output'               => true,
        // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
        'output_tag'           => true,
        // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
        // 'footer_credit'     => '',                   // Disable the footer credit of Redux. Please leave if you can help it.

        // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
        'database'             => '',
        // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!

        // HINTS
        'hints'                => array(
            'icon'          => 'el el-question-sign',
            'icon_position' => 'right',
            'icon_color'    => 'lightgray',
            'icon_size'     => 'normal',
            'tip_style'     => array(
                'color'   => 'red',
                'shadow'  => true,
                'rounded' => false,
                'style'   => '',
            ),
            'tip_position'  => array(
                'my' => 'top left',
                'at' => 'bottom right',
            ),
            'tip_effect'    => array(
                'show' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'mouseover',
                ),
                'hide' => array(
                    'effect'   => 'slide',
                    'duration' => '500',
                    'event'    => 'click mouseleave',
                ),
            ),
        )
    );


    // Panel Intro text -> before the form


    // Add content after the form.
    $args['footer_text'] = __( '<p>Thanks for downloading my plugin, i hope you´ll enjoy it! You can find more stuff on <a href="http://www.danielederosa.de" target="_blank">my website.</a> Have fun! ;-)</p>', 'dd_bfp_plugin' );

    Redux::setArgs( $opt_name, $args );

    /*
     * ---> END ARGUMENTS
     */

    /**
     * Sections
     */

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Facebook Data', 'dd_bfp_plugin' ),
        'id'         => 'fb-id',
        'desc'       => __( 'You can start! Please put your facebook Page URL <strong>(Example: https://www.facebook.com/danielederosaweb)</strong>', 'dd_bfp_plugin'),
        'icon' => 'el el-facebook',
        'fields'     => array(
            array(
                'id'       => 'your-fb-url',
                'type'     => 'text',
                'title'    => __( 'Your facebook URL', 'dd_bfp_plugin' ),
                'subtitle' => __( 'Please put your URL here', 'dd_bfp_plugin' ),
                'placeholder'  => 'Your URL here'
            ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Dimensions', 'dd_bfp_plugin' ),
        'id'         => 'dimensions',
        'icon'       => 'el el-resize-full',
        'desc'       => __( 'You got it! Please choose your preferred dimensions.', 'dd_bfp_plugin'),
        'fields'     => array(
            array(
                'id'             => 'box-dimensions',
                'type'           => 'dimensions',
                'units'          => 'px',    // You can specify a unit value. Possible: px, em, %
                'units_extended' => 'false',  // Allow users to select any type of unit
                'title'          => __( 'Width / Height of the Page Box', 'dd_bfp_plugin' ),
                'subtitle'       => __( 'Please choose your dimensions', 'dd_bfp_plugin' ),
                'default'        => array(
                    'width'  => 500,
                    'height' => 500,
                )
            ),
            array(
                'id'       => 'dimensions-info',
                'type'     => 'info',
                'desc'     => '<h3>Important: Facebook Limits (Pixel)</h3><strong>Width:</strong> Min. 280 to Max. 500<br><strong>Height:</strong> Min. 130'
            ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title' => __( 'Box Content', 'dd_bfp_plugin' ),
        'id'    => 'box-content',
        'desc'  => __( '', 'dd_bfp_plugin' ),
        'icon'  => 'el el-group',
        'fields' => array(
          array(
              'id'       => 'friend-faces',
              'type'     => 'switch',
              'title'    => __( 'Friend´s faces', 'dd_bfp_plugin' ),
              'subtitle' => __( 'Do you want to show your friend´s faces?', 'dd_bfp_plugin' ),
              'default'  => true,
          ),
          array(
              'id'       => 'cover-photo',
              'type'     => 'switch',
              'title'    => __( 'Hide Cover Photo', 'dd_bfp_plugin' ),
              'subtitle' => __( 'Do you want to hide your cover photo?', 'dd_bfp_plugin' ),
              'default'  => false,
          ),
          array(
              'id'       => 'page-posts',
              'type'     => 'switch',
              'title'    => __( 'Show page posts', 'dd_bfp_plugin' ),
              'subtitle' => __( 'Do you want to show your page posts?', 'dd_bfp_plugin' ),
              'default'  => true,
          ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Special', 'dd_bfp_plugin' ),
        'id'         => 'special',
        'icon'       => 'el el-asl',
        'fields'     => array(
            array(
                'id'       => 'after-post',
                'type'     => 'switch',
                'title'    => __( 'Display on posts', 'dd_bfp_plugin' ),
                'subtitle' => __( 'Place the Faceboox Box to the end of each post. So cool!', 'dd_bfp_plugin' ),
                'default'  => false,
            ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Display! Now!', 'dd_bfp_plugin' ),
        'id'         => 'display',
        'icon'       => 'el el-screen',
        'fields'     => array(
            array(
                'id'       => 'display-info-widget',
                'type'     => 'info',
                'desc'     => '<h3>Widget</h3>To display your awesome Facebook Page simply you can switch to the <strong>Widgets Page</strong> and move your <strong>"BFP Box"-Widget</strong> into your preferred location! So easy!'
            ),
            array(
                'id'       => 'display-info-shortcode',
                'type'     => 'info',
                'desc'     => '<h3>Shortcode</h3>You prefer to include it with a cool shortcode? No problem! Please use for that the following shortcode and paste it into your posts and pages: <pre>[dd_bfp_plugin]</pre>'
            ),
            array(
                'id'       => 'display-info-php',
                'type'     => 'info',
                'desc'     => '<h3>PHP</h3>Oh, i see.. you want to include your Facebook Page Box directly in your theme files? Cool! You can use for that the following PHP code line. Try it! <pre>&lt;?php echo do_shortcode("[dd_bfp_plugin]") ?&gt;</pre>'
            ),
        )
    ) );

    Redux::setSection( $opt_name, array(
        'title'      => __( 'Support', 'dd_bfp_plugin' ),
        'id'         => 'support',
        'icon'       => 'el el-heart',
        'fields'     => array(
            array(
                'id'       => 'support-info',
                'type'     => 'info',
                'desc'     => '<h3>Oh, something is wrong? I´m there!</h3><p>If you have any questions or issues, you always can write me an email to: <pre>mail@danielederosa.de</pre> with the Subject <strong>"BFP Box Support"</strong> .. i will try my best to solve your problem! ;-)</p>',
                'style'    => 'success'
            ),
        )
    ) );
