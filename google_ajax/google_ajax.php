<?php
/**
 * @package google_ajax
 */
/*
  Plugin Name: Custom Google Ajax Rss Feed
  Plugin URI: http://coderhub.wordpress.com
  Description: Google Ajax RSS Feed manage
  Version: 2.5.6
  Author: Anurag Jain
  Author URI: http://coderhub.wordpress.com
  License: GPLv2 or later
 */
error_reporting(E_ALL);

/* On widgit initlization call initFunct function of class google_ajax_class */
add_action('widgets_init', array('google_ajax_class', 'initFunct'));

class google_ajax_class extends WP_Widget {
    /* function call on widgets_init action */

    function initFunct() {
        /* function call for manage widgets settings from widget panel of admin */
        register_widget_control('Google Ajax Rss Feed', array('google_ajax_class', 'controlFunct'));
        /* function call for display widget in front-end on selected sidebar */
        register_sidebar_widget('Google Ajax Rss Feed', array('google_ajax_class', 'sidebarFunct'));
    }

    function controlFunct() {
        /* get_option is function for get row from wp_options table based on option_name field */
        $read_data = get_option('google_ajax_feed');
        if (!empty($read_data)) {
            $google_feed_title = $read_data['google_feed_title'];
            $google_feed_url = $read_data['google_feed_url'];
            $google_feed_pos = $read_data['google_feed_pos'];
            $google_feed_count = $read_data['google_feed_count'];
            $google_feed_key = $read_data['google_feed_key'];
        } else {
            $google_feed_title = '';
            $google_feed_url = '';
            $google_feed_pos = '';
            $google_feed_count = 5;
            $google_feed_key = '';
        }
        ?>
        <!--Never create form tag wthin register_widget_control hook function-->
        <p>
            <label for='google_feed_title' >Title</label>
            <input type='text' id='google_feed_title' name='google_feed_title' value =  '<?php echo $google_feed_title; ?>'  />     
            <label for='google_feed_key' >Google Key</label>
            <input type='text' id='google_feed_key' name='google_feed_key' value =  '<?php echo $google_feed_key; ?>'  />     
            <br/>
            <label for='google_feed_url' >URL</label>
            <input type='text' id='google_feed_url' name='google_feed_url' value =  '<?php echo $google_feed_url; ?>'  />
            <label for='google_feed_pos' >Position</label>
            <select id='google_feed_pos' name = 'google_feed_pos'>
                <option value='h' <?php if ($google_feed_pos == 'h') {
            echo 'selected';
        } ?> >Horizontal</option>
                <option value='v'  <?php if ($google_feed_pos == 'v') {
            echo 'selected';
        } ?>>vertical</option>
            </select>
            <br/>
            <label for='google_feed_count' >Total Feeds</label>
            <input type = 'text' id = 'google_feed_count' name = 'google_feed_count' value = '<?php echo $google_feed_count; ?>' />
            <input type = 'hidden' id = 'custom_action' name = 'custom_action' value = '1' />
            <?php
            /* Check if click on save then update option */
            if (isset($_POST['custom_action'])) {
                $data = array();
                $data['google_feed_url'] = attribute_escape($_POST['google_feed_url']);
                $data['google_feed_pos'] = attribute_escape($_POST['google_feed_pos']);
                $data['google_feed_count'] = attribute_escape($_POST['google_feed_count']);
                $data['google_feed_title'] = attribute_escape($_POST['google_feed_title']);
                $data['google_feed_key'] = attribute_escape($_POST['google_feed_key']);
                update_option('google_ajax_feed', $data);
            }
        }

        function sidebarFunct() {

            $read_data = get_option('google_ajax_feed');
            if (!empty($read_data['google_feed_url'])) {
                $google_feed_url = $read_data['google_feed_url'];
                $google_feed_title = $read_data['google_feed_title'];
                $google_feed_pos = $read_data['google_feed_pos'];
                $google_feed_count = $read_data['google_feed_count'];
                $google_feed_key = $read_data['google_feed_key'];
            } else {
                $google_feed_url = '';
                $google_feed_title = '';
                $google_feed_pos = '';
                $google_feed_count = '';
                $google_feed_key = '';
            }
            $google_js_url = plugins_url('/google_ajax/google_ajax.js', dirname('__FILE__'));
            wp_enqueue_style('my_plugin', 'http://www.google.com/uds/solutions/dynamicfeed/gfdynamicfeedcontrol.css');
            if (!empty($google_feed_url)) {
                echo '
                <script src="http://www.google.com/jsapi?key=' . $google_feed_key . '" type="text/javascript"></script>
                <script src="http://www.google.com/uds/solutions/dynamicfeed/gfdynamicfeedcontrol.js" type="text/javascript"></script>
                <script src="' . $google_js_url . '" type="text/javascript"></script>
                <script type="text/javascript">
                    google.load("feeds", "1");
                    google.setOnLoadCallback(function() { OnLoad("' . $google_feed_title . '" , "' . $google_feed_url . '","' . $google_feed_pos . '" , "' . $google_feed_count . '"); });
                </script>';

                echo $args['after_title'];
                echo $args['before_widget'];
                echo '<div id="ajaxFeed"></div>';
                echo $args['after_widget'];
            } else {
                echo $args['after_title'];
                echo $args['before_widget'];
                echo '<div id="ajaxFeed">Not Found</div>';
                echo $args['after_widget'];
            }
        }

    }
    ?>