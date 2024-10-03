<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly
/*Display playlist*/
add_shortcode('channel4Youtube','vpfy_vplaylist_display_channel_playlist');
function vpfy_vplaylist_display_channel_playlist($atts){
  $arry_arg = shortcode_atts(array('channelid'=>'1', 'maxresults'=>'10', 'width'=>'1260', 'height'=>'533'),$atts);
  
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


    //Get videos from channel by YouTube Data API
    $apikyyoutb = get_option('vpfy_reg_ytubapi_key');
    $API_key    = $apikyyoutb[0]; //'AIzaSyAldZXc1fKqllZc1UmuS86MloVmWe0rXHs'; 
    $channelID  = $arry_arg['channelid'];
    $maxResults = $arry_arg['maxresults'];
    $getRespon = wp_remote_get(esc_url_raw('https://www.googleapis.com/youtube/v3/search?order=date&part=snippet&channelId='.$channelID.'&maxResults='.$maxResults.'&key='.$API_key.''));
    if( is_wp_error( $getRespon ) ) {
      return false; 
    }
    $videoList = json_decode( wp_remote_retrieve_body( $getRespon ) );
    if( ! empty( $videoList ) ) {
      if(!empty($videoList->error)){
        echo esc_attr($videoList->error->errors[0]->message);
      }
      else{
        if(isset($videoList->items)){
          echo ('<div id="gallery'.esc_attr($channelID).'" style="margin:0px auto;display:none;">');
          foreach($videoList->items as $item){            
            if(!empty($item->id->videoId)){              
              if(strlen($item->snippet->title) > 25){
                $ytvidTitle = substr($item->snippet->title, 0, 25)."...";
              }
              else{
                $ytvidTitle = $item->snippet->title;
              }
              ?>
              <div data-type="youtube"
                 data-title="<?php echo esc_attr($ytvidTitle); ?>"
                 data-description="<?php echo esc_attr(substr($item->snippet->description, 0, 80)); ?>"
                 data-thumb="https://i.ytimg.com/vi/<?php echo esc_attr($item->id->videoId); ?>/mqdefault.jpg"
                 data-image="https://i.ytimg.com/vi/<?php echo esc_attr($item->id->videoId); ?>/sddefault.jpg"
                   data-videoid="<?php echo esc_attr($item->id->videoId); ?>" ></div>
            <?php 
            }            
          }
          echo ('</div>');
          ?>
          <?php if (!empty(get_option('vpfy_vid_autoply')) && get_option('vpfy_vid_autoply') == 1){
            $autoply = 'true';
          }
          else{
            $autoply = 'false';
          }
          ?>
          <script>        
            jQuery(document).ready(function() {
              jQuery("#gallery<?php echo esc_attr($channelID); ?>").unitegallery({
                gallery_theme: "video",                
                gallery_width: <?php echo esc_attr($arry_arg['width']); ?>,
                gallery_height: <?php echo esc_attr($arry_arg['height']); ?>,
                theme_autoplay: <?php echo esc_attr($autoply); ?>,
              });
            });           
          </script>
          <?php
        }
      }
    }
  $output = ob_get_clean();
  return $output;
}