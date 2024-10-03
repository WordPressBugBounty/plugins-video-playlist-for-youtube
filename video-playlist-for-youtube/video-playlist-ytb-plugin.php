<?php 
/* 
 * Plugin Name: Video Playlist for YouTube
 * Plugin URI: https://wordpress.org/plugins/video-playlist-for-youtube
 * Description: It is a very nifty responsive video playlist for youtube that helps you display youtube channels and videos on your website. By using this plugin you can create unlimited playlist while setting up many options and arrange them in any order using drag n drop features.
 * Version: 6.3
 * Author: Galaxy Weblinks
 * Author URI: https://www.galaxyweblinks.com/
 * Text Domain: video-playlist-for-youtube
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

if ( ! defined( 'ABSPATH' ) ) {
        exit; // Exit if accessed directly
    }

if(!defined('VID_PLYLST_PLUGINURL')){
  define('VID_PLYLST_PLUGINURL', plugin_dir_url(__FILE__));
}
if(!defined('VID_PLYLST_PLUGINPATH')){
  define('VID_PLYLST_PLUGINPATH', plugin_dir_path(__FILE__));
}


//echo "path ".VID_PLYLST_PLUGINPATH;
require_once(VID_PLYLST_PLUGINPATH.'vpfy-vplaylist-functions.php');
require_once(VID_PLYLST_PLUGINPATH.'vpfy-settings-pg.php');
require_once(VID_PLYLST_PLUGINPATH.'vpfy-api-playlist-shortcode.php');
//admin notice when activate plugin
register_activation_hook(__FILE__, 'uvfy_vplay_adminnotice');
function uvfy_vplay_adminnotice(){
  update_option('video_plylst_admin_notice','enabled');
}
function uvfy_vplay_admin_notice__success() {
  if(get_option('video_plylst_admin_notice') == 'enabled'){
      ?>
  
    <div class="notice notice-success is-dismissible">
        <p><?php esc_attr_e( 'To view setting please ', 'video-playlist-for-youtube' ); ?>
        <a href="<?php echo esc_attr(admin_url('edit.php?post_type=vid_playlist_ytub')); ?>"><?php esc_attr_e( 'click here', 'video-playlist-for-youtube' ); ?></a></p>
    </div>
    <?php 
  delete_option('video_plylst_admin_notice');
  }
}
add_action( 'admin_notices', 'uvfy_vplay_admin_notice__success' );

//Add Menu Page
add_action('admin_menu', 'vpfu_vplay_add_menu');
function vpfu_vplay_add_menu(){
  add_menu_page('video playlist', __('Video Playlist','video-playlist-for-youtube'), 'manage_options', 'edit.php?post_type=vid_playlist_ytub', NULL);
  add_submenu_page('edit.php?post_type=vid_playlist_ytub','settings', __('Settings','video-playlist-for-youtube'), 'manage_options', 'vpfy_settings_menu', 'vpfy_submenu_settings_page');
  add_action('admin_init', 'vpfyt_settings_api_forytub');
}
/*Register setting api*/
function vpfyt_settings_api_forytub(){
  register_setting('vpfy_reg_groupname','vpfy_reg_ytubapi_key');
}

add_action( 'init', 'vpfy_vplaylist_custompt', 0 );
/*
* Creating a function to create our CPT
*/
 
function vpfy_vplaylist_custompt() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Playlists', 'Post Type General Name', 'video-playlist-for-youtube' ),
        'singular_name'       => _x( 'Playlist', 'Post Type Singular Name', 'video-playlist-for-youtube' ),
        'menu_name'           => __( 'Playlists', 'video-playlist-for-youtube' ),
        'parent_item_colon'   => __( 'Parent Playlist', 'video-playlist-for-youtube' ),
        'all_items'           => __( 'All Playlists', 'video-playlist-for-youtube' ),
        'view_item'           => __( 'View Playlist', 'video-playlist-for-youtube' ),
        'add_new_item'        => __( 'Add New Playlist', 'video-playlist-for-youtube' ),
        'add_new'             => __( 'Add New', 'video-playlist-for-youtube' ),
        'edit_item'           => __( 'Edit Playlist', 'video-playlist-for-youtube' ),
        'update_item'         => __( 'Update Playlist', 'video-playlist-for-youtube' ),
        'search_items'        => __( 'Search Playlist', 'video-playlist-for-youtube' ),
        'not_found'           => __( 'Not Found', 'video-playlist-for-youtube' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'video-playlist-for-youtube' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'Playlists', 'video-playlist-for-youtube' ),
        'description'         => __( 'Playlist for youtube videos', 'video-playlist-for-youtube' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title' ),         
        'hierarchical'        => false,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => 'edit.php?post_type=vid_playlist_ytub',
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => true,
        'menu_position'       => null,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
        'show_in_rest' => true,
 
    );
     
    // Registering Custom Post Type
    register_post_type( 'vid_playlist_ytub', $args ); 
}
/* Add Meta box for shortcode*/
add_action('admin_init', 'vpfy_vplaylist_add_meta_boxes_for_shortcode', 2);

function vpfy_vplaylist_add_meta_boxes_for_shortcode() {
add_meta_box( 'vpfy-vplay-gallery-for-shortcode', __('Shortcode','video-playlist-for-youtube'), 'vpfy_vplaylist_for_shortcode_display', 'vid_playlist_ytub', 'side', 'default');

add_meta_box( 'vpfy-vplay-videogallery-setings', __('Slider Settings','video-playlist-for-youtube'), 'vpfy_vplaylist_slider_settings', 'vid_playlist_ytub', 'side', 'default');

}

function vpfy_vplaylist_for_shortcode_display(){
  $ytube_current_id = get_the_ID();
  echo esc_attr("[videoPlaylist id=".esc_attr($ytube_current_id)."]");  
}

/* Add Meta box*/

add_action('admin_init', 'vpfysect_vplaylist_add_meta_boxes', 2);

function vpfysect_vplaylist_add_meta_boxes() {
add_meta_box( 'ytube-vplay-gallery', __('Add YouTube Video URL','video-playlist-for-youtube'), 'vpfy_vplaylist_repeatable_meta_box_display', 'vid_playlist_ytub', 'normal', 'default');
}

function vpfy_vplaylist_repeatable_meta_box_display() {
    global $post;
    $gpminvoice_group = get_post_meta($post->ID, 'customdata_group', true);
     wp_nonce_field( 'gpm_repeatable_meta_box_nonce', 'gpm_repeatable_meta_box_nonce' );
     wp_enqueue_script('vpfyt-vplay-repeatmeta');
     wp_enqueue_style('vpfy-vplay-adminstyle');
     wp_enqueue_script('jquery-ui-sortable');
    ?>
    <script>
    jQuery( function() {
      //jQuery( "tr" ).draggable({ handle: ".ytubedraggable" });
      jQuery( "tbody.ytub-sortble" ).sortable();
      jQuery( "tbody.ytub-sortble" ).disableSelection();
    } );
    </script>


  <table id="repeatable-fieldset-one" width="100%">
  <tbody class="ytub-sortble">
    <?php
     if ( $gpminvoice_group ) :
      foreach ( $gpminvoice_group as $field ) {
    ?>
    <tr class="drgble-sect">
      <td width="20%">
        <input type="text" required="required" placeholder="Title" name="TitleItem[]" value="<?php if($field['TitleItem'] != '') echo esc_attr( $field['TitleItem'] ); ?>" /></td> 
      <td width="35%">
      <textarea placeholder="Description" cols="40" rows="3" name="TitleDescription[]"> <?php if ($field['TitleDescription'] != '') echo esc_attr( $field['TitleDescription'] ); ?> </textarea></td>

      <td width="30%">
        <input type="text" placeholder="Youtube URL" name="YoutubeUr[]" value="<?php if($field['YoutubeUr'] != '') echo esc_attr( $field['YoutubeUr'] ); ?>" /></td> 

      <td width="15%"><a class="button remove-row" href="#1">Remove</a><span class="ytubedraggable">
        <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" role="img" aria-hidden="true" focusable="false"><path d="M13,8c0.6,0,1-0.4,1-1s-0.4-1-1-1s-1,0.4-1,1S12.4,8,13,8z M5,6C4.4,6,4,6.4,4,7s0.4,1,1,1s1-0.4,1-1S5.6,6,5,6z M5,10 c-0.6,0-1,0.4-1,1s0.4,1,1,1s1-0.4,1-1S5.6,10,5,10z M13,10c-0.6,0-1,0.4-1,1s0.4,1,1,1s1-0.4,1-1S13.6,10,13,10z M9,6 C8.4,6,8,6.4,8,7s0.4,1,1,1s1-0.4,1-1S9.6,6,9,6z M9,10c-0.6,0-1,0.4-1,1s0.4,1,1,1s1-0.4,1-1S9.6,10,9,10z"></path></svg></span></td>
    </tr>
    <?php wp_nonce_field( 'gpm_repeatable_meta_box_nonce', 'gpm_repeatable_meta_box_nonce' );

    }
    else :
    // show a blank one
    ?>
    <tr class="drgble-sect">
      <td> 
        <input type="text" required="required" placeholder="Title" title="Title" name="TitleItem[]" /></td>
      <td> 
          <textarea  placeholder="Description" name="TitleDescription[]" cols="40" rows="3">  </textarea>
          </td>

        <td>
          <input type="text" placeholder="Youtube URL" name="YoutubeUr[]" />
      </td>

      <td><a class="button  cmb-remove-row-button button-disabled" href="#">Remove</a><span class="ytubedraggable">
        <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" role="img" aria-hidden="true" focusable="false"><path d="M13,8c0.6,0,1-0.4,1-1s-0.4-1-1-1s-1,0.4-1,1S12.4,8,13,8z M5,6C4.4,6,4,6.4,4,7s0.4,1,1,1s1-0.4,1-1S5.6,6,5,6z M5,10 c-0.6,0-1,0.4-1,1s0.4,1,1,1s1-0.4,1-1S5.6,10,5,10z M13,10c-0.6,0-1,0.4-1,1s0.4,1,1,1s1-0.4,1-1S13.6,10,13,10z M9,6 C8.4,6,8,6.4,8,7s0.4,1,1,1s1-0.4,1-1S9.6,6,9,6z M9,10c-0.6,0-1,0.4-1,1s0.4,1,1,1s1-0.4,1-1S9.6,10,9,10z"></path></svg></span></td>
    </tr>
    <?php wp_nonce_field( 'gpm_repeatable_meta_box_nonce', 'gpm_repeatable_meta_box_nonce' ); 

   endif; ?>

    <!-- empty hidden one for jQuery -->
    <tr class="empty-row screen-reader-text drgble-sect">
      <td>
        <input type="text" placeholder="Title" name="TitleItem[]"/></td>
      <td>
        <textarea placeholder="Description" cols="40" rows="3" name="TitleDescription[]"></textarea>
      </td>
      <td>
        <input type="text" placeholder="Youtube URL" name="YoutubeUr[]" />
      </td>
      <td><a class="button remove-row" href="#">Remove</a><span class="ytubedraggable">
        <svg width="18" height="18" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 18 18" role="img" aria-hidden="true" focusable="false"><path d="M13,8c0.6,0,1-0.4,1-1s-0.4-1-1-1s-1,0.4-1,1S12.4,8,13,8z M5,6C4.4,6,4,6.4,4,7s0.4,1,1,1s1-0.4,1-1S5.6,6,5,6z M5,10 c-0.6,0-1,0.4-1,1s0.4,1,1,1s1-0.4,1-1S5.6,10,5,10z M13,10c-0.6,0-1,0.4-1,1s0.4,1,1,1s1-0.4,1-1S13.6,10,13,10z M9,6 C8.4,6,8,6.4,8,7s0.4,1,1,1s1-0.4,1-1S9.6,6,9,6z M9,10c-0.6,0-1,0.4-1,1s0.4,1,1,1s1-0.4,1-1S9.6,10,9,10z"></path></svg></span></td>
    </tr>
  </tbody>
</table>
<p><a id="add-row" class="button" href="#">Add another</a></p>
 <?php
}
add_action('save_post', 'custom_repeatable_meta_box_save');
function custom_repeatable_meta_box_save($post_id) {

  //check_admin_referer('gpm_repeatable_meta_box_nonce', 'gpm_repeatable_meta_box_nonce');

    if ( ! isset( $_POST['gpm_repeatable_meta_box_nonce'] ) ||
    ! wp_verify_nonce( $_POST['gpm_repeatable_meta_box_nonce'], 'gpm_repeatable_meta_box_nonce' ) )
        return;

    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        return;

    if (!current_user_can('edit_post', $post_id))
        return;

    $old = get_post_meta($post_id, 'customdata_group', true);
    $new = array();

    
    /* Title */
    $invoiceItems = array();
    foreach ($_POST['TitleItem'] as $titlValue) {
        $invoiceItems[] = sanitize_text_field($titlValue);
    }
    /*Description*/
    $prices = array();
    foreach ($_POST['TitleDescription'] as $titlDesValue) {
        $prices[] = sanitize_textarea_field($titlDesValue);
    }

    /*URL*/
    $YoutubeUr = array();
    foreach ($_POST['YoutubeUr'] as $youtubeUrlValue) {
        $YoutubeUr[] = sanitize_text_field($youtubeUrlValue);
    }

     $count = count( $invoiceItems );
     for ( $i = 0; $i < $count; $i++ ) {
        if ( $invoiceItems[$i] != '' ) :
            $new[$i]['TitleItem'] = stripslashes( wp_strip_all_tags( trim($invoiceItems[$i]) ) );
            $new[$i]['TitleDescription'] = stripslashes( trim($prices[$i]) ); // and however you want to sanitize
            $new[$i]['YoutubeUr'] = filter_var($YoutubeUr[$i], FILTER_SANITIZE_URL);
        endif;
    }
    if ( !empty( $new ) && $new != $old )
        update_post_meta( $post_id, 'customdata_group', $new );
    elseif ( empty($new) && $old )
        delete_post_meta( $post_id, 'customdata_group', $old );
    if(isset($_POST['utubeSliderRange'])){
      $new_itm_range = array();
      foreach ($_POST['utubeSliderRange'] as $rangevalue) {
        $new_itm_range[] = sanitize_text_field($rangevalue);
      }
      update_post_meta( $post_id, '_utubeSliderRange', $new_itm_range );      
    }

}

/*Display playlist*/
add_shortcode('videoPlaylist','vpfy_vplaylist_display_gallery');
function vpfy_vplaylist_display_gallery($atts){
  $arry_arg = shortcode_atts(array('id'=>''),$atts);

  $output = '';
  ob_start(); ?>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800%7CShadows+Into+Light" rel="stylesheet" type="text/css">
  <script type='text/javascript' src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js'></script>
  <?php 
    wp_enqueue_script('vpfy-playlist-min');
    wp_enqueue_script('vpfy-playlist-video');
    wp_enqueue_script('vpfy-unitegallery-video');
    wp_enqueue_style('vpfy-vplay-galcss');
    wp_enqueue_style('vpfy-playlist-ryt-no-thumb');
    wp_enqueue_style('vpfy-playlist-ryt-thumb');
    wp_enqueue_style('vpfy-playlist-ryt-ttl-only');
    wp_enqueue_style('vpfy-unite-gallery');

    if(!empty($arry_arg['id'])){
      $ytube_custmgrp = get_post_meta($arry_arg['id'], 'customdata_group', true);
      
      echo('<div id="gallery'.esc_attr($arry_arg['id']).'" style="margin:0px auto;display:none;">');
      foreach ($ytube_custmgrp as $ykey => $ytubvalue) { 
        $ytuburl = parse_url($ytubvalue['YoutubeUr']);
        
        $existance = 0;
        foreach ($ytuburl as $utkey => $utvalue) {
          if('query' == $utkey){              
            $existance = 1;         
          }
          else{
            $existance = 0;
          }
        }

        if($existance == 1){ 
          $ytubid = explode('v=', $ytuburl['query']);
          $ytubid = explode('&', $ytubid[1]);
          if(strlen($ytubvalue['TitleItem']) > 25){
            $ytvidTitle = substr($ytubvalue['TitleItem'], 0, 25)."...";
          }
          else{
            $ytvidTitle = $ytubvalue['TitleItem'];
          }
          ?>
          <div data-type="youtube"
             data-title="<?php echo esc_attr($ytvidTitle); ?>"
             data-description="<?php echo esc_attr(substr($ytubvalue['TitleDescription'], 0, 80)); ?>"
             data-thumb="https://i.ytimg.com/vi/<?php echo esc_attr($ytubid[0]); ?>/mqdefault.jpg"
             data-image="https://i.ytimg.com/vi/<?php echo esc_attr($ytubid[0]); ?>/sddefault.jpg"
               data-videoid="<?php echo esc_attr($ytubid[0]); ?>" ></div>
        <?php }
        elseif($existance == 0){ ?>
          <div data-type="youtube"
             data-title="<?php echo esc_attr($ytubvalue['TitleItem']); ?>"
             data-description="<?php echo esc_attr(substr($ytubvalue['TitleDescription'], 0, 80)); ?>"
             data-thumb="https://i.ytimg.com/vi/123/mqdefault.jpg"
             data-image="https://i.ytimg.com/vi/123/sddefault.jpg"
               data-videoid="123" ></div>
        <?php }
                
      }
      echo ('</div>');

      if( null!== get_post_meta( $arry_arg['id'], '_utubeSliderRange', true )){
        $sliderRange = get_post_meta($arry_arg['id'], '_utubeSliderRange', true);
      }
      $sliderWidth = !empty($sliderRange) ? $sliderRange[0] : '1100'; 
      $sliderHeight = !empty($sliderRange) ? $sliderRange[1] : '450';  
      if (!empty(get_option('vpfy_vid_autoply')) && get_option('vpfy_vid_autoply') == 1){
        $autoply = 'true';
      }
      else{
        $autoply = 'false';
      }
      ?>
      <script>        
        jQuery(document).ready(function() {
          jQuery("#gallery<?php echo esc_attr($arry_arg['id']); ?>").unitegallery({
            gallery_theme: "video",
            gallery_width: <?php echo esc_attr($sliderWidth); ?>,
            gallery_height: <?php echo esc_attr($sliderHeight); ?>,
            theme_autoplay: <?php echo esc_attr($autoply); ?>,
          });
        });           
      </script>
      <?php
    }
    ?>      
      
  <?php  $output = ob_get_clean();
  return $output;
}


// Add the custom columns to the youtube playlist post type:
add_filter( 'manage_vid_playlist_ytub_posts_columns', 'set_custom_vpfy_shortcode_columns' );
function set_custom_vpfy_shortcode_columns($columns) {
    $columns['vpfy_col_shortcode'] = __( 'Shortcode', 'video-playlist-for-youtube' );
    return $columns;
}

// Add the data to the custom columns for the youtube playlist post type:
add_action( 'manage_vid_playlist_ytub_posts_custom_column' , 'vpfy_custom_vidyou_column', 10, 2 );
function vpfy_custom_vidyou_column( $column, $post_id ) {
    switch ( $column ) {

        case 'vpfy_col_shortcode' :
           echo esc_attr("[videoPlaylist id=".$post_id."]");
           break;  
    }
}


/*Plugin settings */
function vpfy_vplaylist_slider_settings(){  
  if( null!== get_post_meta( get_the_ID(), '_utubeSliderRange', true )){
    $sliderRange = get_post_meta(get_the_ID(), '_utubeSliderRange', true);
  }
  $sliderWidth = !empty($sliderRange) ? $sliderRange[0] : '1100'; 
  $sliderHeight = !empty($sliderRange) ? $sliderRange[1] : '450';     
  ?>
  <div class="ytube-slide-container">
    <!-- slider width -->
    <p><strong><?php esc_attr_e('Slider Width: ', 'video-playlist-for-youtube');?></strong><span id="ytube_slide_wdth"></span>px</p>
      <input type="range" name="utubeSliderRange[]" min="320" max="2200" value="<?php echo esc_attr($sliderWidth); ?>" class="ytube-plyslider" id="vpfu_plyst_width">

      <!-- slider height -->
      <p><strong><?php esc_attr_e('Slider Height: ', 'video-playlist-for-youtube');?></strong><span id="ytube_slide_height"></span>px</p>
      <input type="range" name="utubeSliderRange[]" min="165" max="900" value="<?php echo esc_attr($sliderHeight); ?>" class="ytube-plyslider" id="vpfu_plyst_height">    
  </div>
<?php } 

/*Ajax response for video duration*/
function vpfytGetYoutubeDuration() {

  //check_admin_referer('gpm_repeatable_meta_box_nonce', 'gpm_repeatable_meta_box_nonce');

   if ( ! isset( $_POST['gpm_repeatable_meta_box_nonce'] ) ||
                    ! wp_verify_nonce( $_POST['gpm_repeatable_meta_box_nonce'], 'gpm_repeatable_meta_box_nonce' ) )
                    return;

  $vid = $_POST['ytvideo_id'];
  $get_Gapi_key = get_option('vpfy_reg_ytubapi_key');
  $response = wp_remote_get("https://www.googleapis.com/youtube/v3/videos?id=".$vid."&part=contentDetails,statistics&key=".$get_Gapi_key[0]);

  if ( is_array( $response ) && ! is_wp_error( $response ) ) {
    $videoDetails = $response['body']; // use the content
    $videoDetails =json_decode($videoDetails, true);
    $responsearry = array();
    foreach ($videoDetails['items'] as $vidTime)
    {
      $youtube_time = $vidTime['contentDetails']['duration'];
      preg_match_all('!\d+!',$youtube_time,$parts);      
      if(count($parts[0]) == 3){
        $ythours = $parts[0][0];
        $ytminuts = $parts[0][1];
        $ytsecond = $parts[0][2];
        $responsearry = array('ythours'=>$ythours,'ytminuts'=>$ytminuts,'ytsecond'=>$ytsecond);
        wp_send_json_success($responsearry);
      }
      if(count($parts[0]) == 2){
        $ythours = '00';
        $ytminuts = $parts[0][0];
        $ytsecond = $parts[0][1];
        $responsearry = array('ythours'=>$ythours,'ytminuts'=>$ytminuts,'ytsecond'=>$ytsecond);
        wp_send_json_success($responsearry);
      }
      if(count($parts[0]) == 1){
        $ythours = '00';
        $ytminuts = '00';
        $ytsecond = $parts[0][0];
        $responsearry = array('ythours'=>$ythours,'ytminuts'=>$ytminuts,'ytsecond'=>$ytsecond);
        wp_send_json_success($responsearry);
      }
      else{
        $ythours = '00';
        $ytminuts = '00';
        $ytsecond = '00';
        $responsearry = array('ythours'=>$ythours,'ytminuts'=>$ytminuts,'ytsecond'=>$ytsecond);
        wp_send_json_success($responsearry);
      }  
      
    }
  }
}

/*Create global ajax varialble*/
add_action("wp_ajax_vpfytGetYoutubeDuration", "vpfytGetYoutubeDuration");
add_action("wp_ajax_nopriv_vpfytGetYoutubeDuration", "vpfytGetYoutubeDuration");