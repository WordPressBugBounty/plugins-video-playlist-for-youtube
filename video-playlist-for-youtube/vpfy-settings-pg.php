<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

function vpfy_submenu_settings_page() { ?>
    <div class="wrap">
        <h2><?php esc_attr_e('Video Playlist for YouTube Settings', 'video-playlist-for-youtube'); ?></h2>
        <?php  
        wp_enqueue_style('vpfy-vplay-settings');
        wp_enqueue_script('vpfyt-vplay-settngpg');
        ?>
        <?php 

        $dashboard = esc_url(admin_url('edit.php?post_type=vid_playlist_ytub&page=vpfy_settings_menu&tab=dashboard'));
        $settings = esc_url(admin_url('edit.php?post_type=vid_playlist_ytub&page=vpfy_settings_menu&tab=settings'));
        $api = esc_url(admin_url('edit.php?post_type=vid_playlist_ytub&page=vpfy_settings_menu&tab=api'));
        $help = esc_url(admin_url('edit.php?post_type=vid_playlist_ytub&page=vpfy_settings_menu&tab=help'));
        ?>

        <h2 class="nav-tab-wrapper">
            <a href="<?php echo esc_attr($dashboard);  ?>" class="nav-tab <?php echo $_GET['tab'] == '' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e('Dashboard', 'video-playlist-for-youtube'); ?></a>
            <a href="<?php echo esc_attr($settings); ?>" class="nav-tab <?php echo $_GET['tab'] == 'settings' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e('General Settings', 'video-playlist-for-youtube'); ?></a>
            <a href="<?php echo esc_attr($api); ?>" class="nav-tab <?php echo $_GET['tab'] == 'api' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e('YouTube API', 'video-playlist-for-youtube'); ?></a>
            <a href="<?php echo esc_attr($help); ?>" class="nav-tab <?php echo $_GET['tab'] == 'help' ? 'nav-tab-active' : ''; ?>"><?php esc_attr_e('Help/Usage', 'video-playlist-for-youtube'); ?></a>
        </h2>

        <?php if ( empty($_GET['tab']) || !isset($_GET['tab']) || $_GET['tab'] === 'dashboard') { ?>
            <?php 


            if (isset($_POST['generate_shortcd'])) {

                //check_admin_referer('gpm_repeatable_meta_box_nonce', 'gpm_repeatable_meta_box_nonce');

                if ( ! isset( $_POST['gpm_repeatable_meta_box_nonce'] ) ||
                    ! wp_verify_nonce( $_POST['gpm_repeatable_meta_box_nonce'], 'gpm_repeatable_meta_box_nonce' ) )
                    return;

                $vpfy_channelid = sanitize_text_field($_POST['vpfy_channelid']);
                $vpfy_maxvideos = (int) sanitize_text_field($_POST['vpfy_maxvideos']);
                $vpfysliderwid = (int) sanitize_text_field($_POST['vpfysliderwid']);
                $vpfysliderhei = (int) sanitize_text_field($_POST['vpfysliderhei']);
                ?>

                <div class="genratshort updated notice is-dismissible">
                    <h4>Display a video playlist for youtube on any post or page via a simple shortcode</h4>
                    <p><code>[channel4Youtube channelid=<?php if(!empty($vpfy_channelid)) { echo esc_attr($vpfy_channelid); }  ?> maxresults=<?php if(!empty($vpfy_maxvideos)) {  echo esc_attr($vpfy_maxvideos); } ?> width=<?php if(!empty($vpfy_channelid)) { echo esc_attr($vpfysliderwid); } ?> height=<?php if(!empty($vpfy_channelid)) { echo esc_attr($vpfysliderhei); } ?>]</code></p>
                </div>

                <?php
            }
            ?>
            <div id="poststuff">
                <h3><?php esc_attr_e('About Video Playlist', 'video-playlist-for-youtube'); ?></h3>
                <p><?php esc_attr_e('Display video playlist for YouTube on any post, page or custom post type via a simple shortcode. The plugin supports a manual YouTube playlist (Add title, description and external youtube link) and a dynamic one using the YouTube Data API v3.', 'video-playlist-for-youtube'); ?></p>

                <p><?php esc_attr_e('Embedded players must have a viewport that is at least 320px by 165px. If the player displays controls, it must be large enough to fully display the controls without shrinking the viewport below the minimum size. ', 'video-playlist-for-youtube'); ?></p>
                <p><?php esc_attr_e('By embedding YouTube videos on your site, you are agreeing to <a href="https://developers.google.com/youtube/terms/api-services-terms-of-service" rel="external" target="_blank">YouTube API Terms of Service</a>.', 'video-playlist-for-youtube'); ?></p>               
                <hr>
                <h3><?php esc_attr_e('Generate Dynamic Shortcode', 'video-playlist-for-youtube'); ?></h3>
                <p><?php esc_attr_e('By embedding YouTube videos on your site, paste your channel Id here <a href="https://www.youtube.com/account_advanced" target="_blank" alt="youtube channel id">https://www.youtube.com/account_advanced</a>', 'video-playlist-for-youtube'); ?></p>

                <form method="post" action="">                    

                    <p class="vpfydssh"><label for="vpfy_channelid"><?php esc_attr_e('Channel ID', 'video-playlist-for-youtube'); ?></label>
                        <input type="text" name="vpfy_channelid" class="regular-text" id="vpfy_channelid" placeholder="Channel ID" value="" required="required"> 
                    </p>
                    <p class="vpfydssh"><label for="vpfy_maxres"><?php esc_attr_e('Show Videos', 'video-playlist-for-youtube'); ?></label>
                        <input type="number" name="vpfy_maxvideos" class="regular-text" id="vpfy_maxvideos" placeholder="Show Maximum Videos" min="2" value="" required="required">
                    </p>
                    <div class="vpfy-wihi">
                        <!-- slider width -->
                        <p class="vpfydssh"><label for="vpfy_slidwidh"><?php esc_attr_e('Slider Width: ', 'video-playlist-for-youtube');?><span id="ytube_slidesetting_wdth"></span>px</label>
                            <input type="range" name="vpfysliderwid" min="320" max="2200" value="" class="regular-text ytube-plyslider" id="vpfu_plyst_wid"></p>

                            <!-- slider height -->
                            <p class="vpfydssh"><label for="vpfy_slidheight"><?php esc_attr_e('Slider Height: ', 'video-playlist-for-youtube');?><span id="ytube_slidesetting_height"></span>px</label>
                                <input type="range" name="vpfysliderhei" min="165" max="900" value="" class="regular-text ytube-plyslider" id="vpfu_plyst_hei"></p>
                            </div>


                            <p><input type="submit" name="generate_shortcd" class="button button-primary" value="<?php esc_attr_e('Generate Shortcode', 'video-playlist-for-youtube'); ?>"></p>

                            <?php wp_nonce_field( 'gpm_repeatable_meta_box_nonce', 'gpm_repeatable_meta_box_nonce' ); ?>

                        </form>

                    </div>
                    <?php
                } else if (!empty($_GET['tab']) && isset($_GET['tab']) &&  $_GET['tab'] === 'settings') {
                    if (isset($_POST['info_update1']) && current_user_can('manage_options')) {

                        //check_admin_referer('gpm_repeatable_meta_box_nonce', 'gpm_repeatable_meta_box_nonce');

                        if ( ! isset( $_POST['gpm_repeatable_meta_box_nonce'] ) ||
                            ! wp_verify_nonce( $_POST['gpm_repeatable_meta_box_nonce'], 'gpm_repeatable_meta_box_nonce' ) )
                            return;


                        if (isset($_POST['vpfy_vid_autoply'])) {
                            update_option('vpfy_vid_autoply', (int) sanitize_text_field($_POST['vpfy_vid_autoply']));
                        } else {
                            update_option('vpfy_vid_autoply', 0);
                        }
                        if (isset($_POST['vpfy_vid_length'])) {
                            update_option('vpfy_vid_length', (int) sanitize_text_field($_POST['vpfy_vid_length']));
                        } else {
                            update_option('vpfy_vid_length', 0);
                        }
                        if (isset($_POST['ytpp_controls'])) {
                            update_option('ytpp_controls', (int) sanitize_text_field($_POST['ytpp_controls']));
                        } else {
                            update_option('ytpp_controls', 0);
                        }
                        

                        echo ('<div class="updated notice is-dismissible"><p>Settings updated!</p></div>');
                    }
                    ?>
                    <form method="post" action="">
                        <h3><?php esc_attr_e('Player Settings', 'video-playlist-for-youtube'); ?></h3>

                        <p>
                            <input type="checkbox" name="vpfy_vid_autoply" id="vpfy_vid_autoply" value="1" <?php if (get_option('vpfy_vid_autoply') == 1) echo 'checked'; ?>> <label for="vpfy_vid_autoply">AutoPlay</label>
                        </p>
                        <p>
                            <input type="checkbox" name="vpfy_vid_length" id="vpfy_vid_length" value="1" <?php if (get_option('vpfy_vid_length') == 1) echo 'checked'; ?>> <label for="vpfy_vid_length">Show video length/duration </label>
                        </p>

                        <p><input type="submit" name="info_update1" class="button button-primary" value="<?php esc_attr_e('Save Changes', 'video-playlist-for-youtube'); ?>"></p>
                        <?php wp_nonce_field( 'gpm_repeatable_meta_box_nonce', 'gpm_repeatable_meta_box_nonce' ); ?>
                    </form>
                    <?php
                } else if (!empty($_GET['tab']) && isset($_GET['tab']) &&  $_GET['tab'] === 'api') {                        
                    if( isset($_GET['settings-updated']) && $_GET['settings-updated'] == 'true'):
                                                
                        echo ('<div class="updated notice is-dismissible"><p>Settings updated!</p></div>');
                    endif;
                    ?>
                    <h2><?php esc_attr_e( 'YouTube API V3', 'video-playlist-for-youtube' ); ?></h2>
                    <h4><?php esc_attr_e( 'For getting video duration and show youtube channel, do not forget to create your API key at ', 'video-playlist-for-youtube' ); ?><a href="https://console.developers.google.com/project" target="_blank"><?php esc_attr_e( 'https://console.developers.google.com/project', 'video-playlist-for-youtube' ); ?></a>
                    </h4>
                    <div class="googlepai">
                        <form method="post" action="options.php">
                          <?php 
                          $get_Gapi = get_option('vpfy_reg_ytubapi_key');
                          do_settings_sections('vpfy_reg_groupname');
                          settings_fields( 'vpfy_reg_groupname' );
                          ?>
                          <p><input type="text" name="vpfy_reg_ytubapi_key[]" value="<?php if(!empty($get_Gapi)){echo esc_html($get_Gapi[0]);} ?>" class="regular-text" placeholder="YouTube API" required>
                          </p>
                          <?php wp_nonce_field( 'gpm_repeatable_meta_box_nonce', 'gpm_repeatable_meta_box_nonce' ); ?>
                          <?php submit_button(); ?>
                      </form>
                  </div>
                  <?php
              } else if (!empty($_GET['tab']) && isset($_GET['tab']) &&  $_GET['tab'] === 'help') { ?>
                <div id="poststuff">
                    <?php echo '<h3>Help &amp; Usage Details</h3>
                    <h4>To create an YouTube API key follow these steps</h4>
                    <p>1] Login your Google account.</p>
                    <p>2] Prefer the link - <a href ="https://console.developers.google.com/project" target="_blank"><strong>https://console.developers.google.com/project</strong></a></p>
                    <p>3] On the top bar click on the "CREATE PROJECT" button to create a new project.</p>
                    <p>4] Once create, select that project and go to the "API & Services"</p>
                    <p>5] Click on the "ENABLE APIS AND SERVICES" and enable "YouTube Data API v3" Service.</p>
                    <p>6] Once enable service go to the "credentials" Menu and create an API Key.</p>
                    <p>7] Copy that key and put in the plugin settings YouTube API tab.</p>'; ?>                
                </div>
                <?php
            }
            ?>
        </div>
        <?php
    }
