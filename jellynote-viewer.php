<?php
/*
Plugin Name: Jellynote Viewer
Plugin URI: jellynote.com
Description: Display Jellynote sheet music and tabs to wordpress.
Version: 0.1
Author: Jellynote
Author URI: jellynote.com
License: A "Slug" license name e.g. GPL2
*/

// v0.1 : Simple shortcode to embed a score from jellynote.com

  // Create shortcode handler for Jellynote
  // [jellynote url=xxx ]
  function addJellynote($atts, $content = null) {
    extract(shortcode_atts(array( "url" => '',
                                  "width" => get_settings('jellynote-width'),
                                  "height" => get_settings('jellynote-height')), $atts));

    $i = strpos($url, "//");        // http://
    $i = strpos($url, "/", $i + 2); // jellynote.com/
    $i = strpos($url, "/", $i + 1); // sheet-music-tabs/
    $url = "http://www.jellynote.com/embed" . substr($url, $i);

    if ($width == "") {
      $width = "100%";
    }
    if ($height == "") {
      $height = "600px";
    }

    return '<iframe frameborder="0" height="' . $height . '" 
            width="' . $width . '" 
            webkitallowfullscreen="true" mozallowfullscreen="true" 
            src="' . $url . '"
            ></iframe>';
  }
  add_shortcode('jellynote', 'addJellynote');

  // Add Jellynote button to MCE
  
  function add_jellynote_button() {
    if( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') )
      return;
    
    if( get_user_option('rich_editing') == 'true') {
      add_filter('mce_external_plugins', 'add_jellynote_tinymce_plugin');
      add_filter('mce_buttons', 'register_jellynote_button');
     }
  }

  function register_jellynote_button($buttons) {
    array_push($buttons, "|", "jellynoteEmbed");
    return $buttons;
  }

  function add_jellynote_tinymce_plugin($plugin_array) {
    $dir = '/wp-content/plugins/jellynote-viewer';
    $url = get_bloginfo('wpurl');
    $plugin_array['jellynoteEmbed'] = $url.$dir.'/custom/editor_plugin.js';
    return $plugin_array;
  }
  add_action('init', 'add_jellynote_button');

  // Add settings menu to Wordpress

  if ( is_admin() ){ // admin actions
    add_action( 'admin_menu', 'jellynote_create_menu' );
  } else {
    // non-admin enqueues, actions, and filters
  }

  function jellynote_create_menu() {
    // Create top-level menu
    add_menu_page('Jellynote Plugin Settings', 'Jellynote Settings', 'administrator',
      __FILE__, 'jellynote_settings_page', plugins_url('/img/jellynote-menu-icon.png', __FILE__));
  
    // Call register settings function
    add_action( 'admin_init', 'register_settings' );
  }

  function register_settings() { // whitelist options
    register_setting( 'settings-group', 'jellynote-width' );
    register_setting( 'settings-group', 'jellynote-height' );
  }

  // Page displayed as the settings page
  function jellynote_settings_page() {
?>
  <div class="wrap">
  <h2>Jellynote Viewer</h2>

  <form method="post" action="options.php">
    <?php settings_fields( 'settings-group' ); ?>
    
    <h3>Default settings</h3>
    <table class="form-table">
      <tr valign="top">
        <th scope="row">Width</th>
        <td><input type="text" name="jellynote-width" value="<?php echo get_option('jellynote-width'); ?>" /> px</td>
      </tr>
      <tr valign="top">
        <th scope="row">Height</th>
        <td><input type="text" name="jellynote-height" value="<?php echo get_option('jellynote-height'); ?>" /> px</td>
      </tr>
    </table>
    
    <?php submit_button(); ?>
  </form> 
</div>

<?php } ?>
