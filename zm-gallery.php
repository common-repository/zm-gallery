<?php
/*
Plugin Name: ZM Gallery
Plugin URI: http://zenmaker.net/
Description: Css3 image slider
Version: 1.0
Author: ZenMaker
Author URI: http://zenmaker.net/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: zm_gallery

Copyright © 2015 ZenMAker

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.
*/
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class ZMGallery {
    function __construct() {
        $this->init();
    }

    function init() {
        add_action( 'admin_menu', array($this, 'zm_gallery_admin_menu') );

        add_shortcode( 'zm_gallery', array($this, 'zm_gallery_shortcode') );
        if( get_option( 'zm_gallery_replace_slider' ) ) {
            add_filter( 'post_gallery', array($this, 'zm_gallery_wp_shortcode'), 10, 2 );
        }
        else {
            add_action( 'media_buttons_context',  array($this, 'zm_gallery_button') );
        }

        add_action( 'admin_footer', array($this, 'zm_gallery_choose') );

        // load language files
        load_plugin_textdomain( 'zm_gallery', false, plugins_url('zm-gallery/languages') );

        if ( isset( $_POST["add_zm_gallery"] ) && $_POST["add_zm_gallery"] == 'Y' ) {
            include_once ABSPATH . WPINC . '/pluggable.php';
            if( function_exists('check_admin_referer') ) {
                check_admin_referer('zm_gallery_add');
            }
            $this->zm_gallery_add_save();
            exit();
        }
        if ( isset( $_POST["settings_zm_gallery"] ) && $_POST["settings_zm_gallery"] == 'Y' ) {
            include_once ABSPATH . WPINC . '/pluggable.php';
            if( function_exists('check_admin_referer') ) {
                check_admin_referer('zm_gallery_settings');
            }
            $this->zm_gallery_settings_save();
            exit();
        }
        if ( isset( $_POST["edit_zm_gallery"] ) && $_POST["edit_zm_gallery"] == 'Y' ) {
            include_once ABSPATH . WPINC . '/pluggable.php';
            if( function_exists('check_admin_referer') ) {
                check_admin_referer('zm_gallery_edit');
            }
            $this->zm_gallery_edit_save();
            exit();
        }
        if( isset($_GET['delete']) && (int)$_GET['delete']>0 ) {
            $this->zm_gallery_delete();
            exit();
        }
        if( isset( $_POST["zm_gallery_posts"] ) ) {
            $posts = 20;
            $count = wp_count_posts();
            $published_posts = $count->publish;
            $page = (int)$_POST["page"];
            $args = array(
                'posts_per_page'   => $posts,
                'offset'           => $page,
                'category'         => '',
                'category_name'    => '',
                'orderby'          => 'date',
                'order'            => 'DESC',
                'include'          => '',
                'exclude'          => '',
                'meta_key'         => '',
                'meta_value'       => '',
                'post_type'        => 'post',
                'post_mime_type'   => '',
                'post_parent'      => '',
                'author'	       => '',
                'post_status'      => 'publish',
                'suppress_filters' => true
            );
            $posts_array = get_posts( $args );

            $html = '<div class="zm-slider-images-links">';
            foreach($posts_array as $post) {
                $post_date_obj = new DateTime( $post->post_date );
                $post_date = $post_date_obj->format('Y/m/d');
                $html .= '<div class="zm-slider-images-links-elem" data-id="' . $post->ID . '">';
                $html .= '<h4>' . $post->post_title . '</h4>';
                $html .= $post_date;
                $html .= '</div>';
            }
            $html .= '</div>';

            $pages = ceil( $published_posts / $posts );
            if($pages > 1) {
                $pages_list = array('<div class="zm-slider-images-paginator">');
                if($page == 0) {
                    $pages_list[] = '<span class="zm-slider-images-paginator-prev disabled">' . __('Prev') . '</span>';
                }
                else {
                    $pages_list[] = '<span class="zm-slider-images-paginator-prev">' . __('Prev') . '</span>';
                }

                if($pages > 7) {
                    if( $page-2 <= 0 ) {
                        $pages_list[] = '<span class="zm-slider-images-paginator-link' . ( ($page == 0) ? ' curr' : '' ) . '">1</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-link' . ( ($page == 1) ? ' curr' : '' ) . '">2</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-link' . ( ($page == 2) ? ' curr' : '' ) . '">3</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-link' . ( ($page == 3) ? ' curr' : '' ) . '">4</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-link' . ( ($page == 4) ? ' curr' : '' ) . '">5</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-txt">...</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-link">' . $pages . '</span>';
                    }
                    elseif( $page+2 >= $pages ) {
                        $pages_list[] = '<span class="zm-slider-images-paginator-link">1</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-txt">...</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-link' . ( ($page == $pages-5) ? ' curr' : '' ) . '">' . ($pages-4) . '</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-link' . ( ($page == $pages-4) ? ' curr' : '' ) . '">' . ($pages-3) . '</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-link' . ( ($page == $pages-3) ? ' curr' : '' ) . '">' . ($pages-2) . '</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-link' . ( ($page == $pages-2) ? ' curr' : '' ) . '">' . ($pages-1) . '</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-link' . ( ($page == $pages-1) ? ' curr' : '' ) . '">' . $pages . '</span>';
                    }
                    else {
                        $pages_list[] = '<span class="zm-slider-images-paginator-link">1</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-txt">...</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-link">' . ($page-2) . '</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-link">' . ($page-1) . '</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-link curr">' . $page . '</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-link">' . ($page+1) . '</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-link">' . ($page+2) . '</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-link">...</span>';
                        $pages_list[] = '<span class="zm-slider-images-paginator-txt">' . $pages . '</span>';
                    }
                }
                else {
                    for($i=0; $i<$pages; $i++) {
                        if($page == $i) {
                            $pages_list[] = '<span class="zm-slider-images-paginator-link curr">' . ($i+1) . '</span>';
                        }
                        else {
                            $pages_list[] = '<span class="zm-slider-images-paginator-link">' . ($i+1) . '</span>';
                        }
                    }
                }

                if($page == $pages-1) {
                    $pages_list[] = '<span class="zm-slider-images-paginator-next disabled">' . __('Next') . '</span>';
                }
                else {
                    $pages_list[] = '<span class="zm-slider-images-paginator-next">' . __('Next') . '</span>';
                }
                $pages_list[] = '</div>';
                $paginator = implode('', $pages_list);

                $html .= $paginator;
            }

            print $html;
            //print_r($posts_array);
            exit();
        }
    }

    function zm_gallery_button( $context ) {
        $container_id = 'popup_container';
        $title =  __('Add ZM Gallery');

        //append the icon
        $context .= '<a class="thickbox button" href="#TB_inline?width=100%&inlineId=' . $container_id . '">' . $title . '</a>';
        return $context;
    }
    function zm_gallery_choose() {
        global $wpdb;
        ?>
        <div id="popup_container" style="display:none;">
            <h2 style="font-weight: 400;">Hello from my custom button!</h2>
            <ul class="zm-gallery-list">
                <?php
                $table_name = $wpdb->prefix . 'zm_gallery';
                $gallery = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC" );
                foreach ( $gallery as $g ) {
                    $images = json_decode($g->data);
                    $src = '';
                    foreach($images as $i) {
                        $src = $i->src;
                        break;
                    }
                    ?>
                    <li data-id="<?php echo $g->id; ?>">
                        <img src="<?php echo $src; ?>" alt="$"/>
                        <span><?php echo $g->name; ?></span>
                    </li>
                    <?php
                }
                ?>
            </ul>
            <button id="zm-gallery-list-select" class="button button-primary button-large" data-empty="<?php _e('Select gallery for including!'); ?>"><?php _e('Select gallery'); ?></button>
        </div>
    <?php
    }

    function zm_gallery_main_slider() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'zm_gallery';
        $gallery = $wpdb->get_results("SELECT * FROM " . $table_name . " WHERE main=1" );

        if( $gallery ) {
            $zm_images = json_decode($gallery[0]->data);
            $zm_width = (int)$gallery[0]->width > 0 ? (int)$gallery[0]->width : 1170;
        }
        else {
            return;
        }

        if( count($zm_images) < 1 ) {
            return;
        }
        ?>
        <script>
            var zm_gallery_width = <?php echo $zm_width; ?>;
        </script>
        <div class="zm-gallery-main">
            <div class="zm-gallery-loader"></div>
            <div class="zm-gallery-main-bg" style="display: none;"></div>
            <div class="zm-gallery-main-img" style="display: none;">
                <div class="zm-gallery-main-l"><span></span></div>
                <div class="zm-gallery-main-r"><span></span></div>
                <div class="zm-gallery-main-img-list">
                    <?php
                    foreach($zm_images as $zm_img) {
                        $zm_gallery_link = '';
                        if( $zm_img->post > 0 ) {
                            $zm_gallery_link = get_permalink($zm_img->post);
                        }
                        ?>
                        <a href="<?php echo $zm_gallery_link; ?>" class="zm-gallery-main-img-list-block" style="display: none;">
                            <img src="<?php echo $zm_img->src; ?>" alt="" />
                            <span class="zm-gallery-main-img-list-block-txt">
                                <span class="zm-gallery-main-img-list-block-txt-table">
                                    <span class="zm-gallery-main-img-list-block-txt-table-cell">
                                        <?php
                                        if( $zm_img->post > 0 ) {

                                            $zm_gallery_post = get_post( $zm_img->post );
                                            $zm_gallery_post_datetime = new DateTime($zm_gallery_post->post_date);
                                            $zm_gallery_post_date = $zm_gallery_post_datetime->format('j F');
                                            $zm_gallery_post_date = str_replace(' ', '</span>',$zm_gallery_post_date);
                                            $zm_gallery_post_cat = get_the_category( $zm_img->post );
                                            $zm_gallery_post_category = '';

                                            $zm_i = 0;
                                            foreach($zm_gallery_post_cat as $zm_post_cat) {
                                                $zm_sep = ($zm_i == 0) ? '' : ' / ';
                                                $zm_gallery_post_category .= $zm_sep.$zm_post_cat->name;
                                                $zm_i++;
                                            }
                                            echo '<span class="zm-gallery-main-img-list-block-txt-table-cell-content">';
                                            echo '<span class="zm-gallery-main-img-date"><span class="zm-gallery-main-img-date-day">'.$zm_gallery_post_date.'</span>';
                                            echo $zm_gallery_post->post_title;
                                            echo '<em>' . $zm_gallery_post_category . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . get_the_author_meta( 'display_name', $zm_gallery_post->post_author ) . '</em>';
                                            echo '</span>';
                                            ?>
                                            <?php
                                        }
                                        ?>
                                    </span>
                                </span>
                            </span>
                        </a>
                        <?php
                    }
                    /*
                     * <!-- a href="#" class="zm-gallery-main-img-list-block" style="display: none;">
                        <img src="http://grandfolk.zenmaker.net/wp-content/themes/grandfolk/images/main-slider-2.jpg" alt="post-2" />
                    <span class="zm-gallery-main-img-list-block-txt">
                        <span class="zm-gallery-main-img-list-block-txt-table">
                            <span class="zm-gallery-main-img-list-block-txt-table-cell">
                                <span>Search your life in good times and good people</span>
                            </span>
                        </span>
                    </span>
                    </a>
                    <a href="#" class="zm-gallery-main-img-list-block" style="display: none;">
                        <img src="http://grandfolk.zenmaker.net/wp-content/themes/grandfolk/images/main-slider-1.jpg" alt="post-2" />
                    </a>
                    <a href="#" class="zm-gallery-main-img-list-block" style="display: none;">
                        <img src="http://grandfolk.zenmaker.net/wp-content/themes/grandfolk/images/main-slider-2.jpg" alt="post-2" />
                    </a -->
                     */
                    ?>
                </div>
                <div class="clear"></div>
            </div>
        </div>
        <?php
    }

    function zm_gallery_admin_menu() {
        add_menu_page(
            __('ZM Gallery', 'zm_gallery'),
            __('ZM Gallery', 'zm_gallery'),
            'manage_options',
            'zm_gallery',
            array($this, 'zm_gallery_list'),
            plugins_url( 'zm-gallery/images/ic_view_carousel_white_24px.svg' ),
            6
        );
        add_submenu_page(
            'zm_gallery',
            __('Add new ZM Gallery', 'zm_gallery'),
            __('New Gallery', 'zm_gallery'),
            'manage_options',
            'zm_gallery_add',
            array($this, 'zm_gallery_add' ));
        add_submenu_page(
            'zm_gallery',
            __('Settings', 'zm_gallery'),
            __('Settings', 'zm_gallery'),
            'manage_options',
            'zm_gallery_settings',
            array($this, 'zm_gallery_settings' ));
    }

    function zm_gallery_list() {
        global $wpdb;
        if( isset($_GET['edit']) && (int)$_GET['edit']>0 ) {
            $this->zm_gallery_edit();
        }
        else {
            $this->zm_gallery_preview();
        }
    }

    function zm_gallery_preview() {
        global $wpdb;
        include_once 'zm-gallery-list.php';
    }

    function zm_gallery_edit() {
        global $wpdb;
        include_once 'zm-gallery-edit.php';
    }

    function zm_gallery_edit_save() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'zm_gallery';
        $pictures = array();

        for($i=0; $i<(int)$_POST['len']; $i++) {
            $pictures[ $_POST['img_id_' . $i] ] = array(
                'src' => $_POST['img_' . $i],
                'pos' => $_POST['img_pos_' . $i],
                'post' => ( isset($_POST['img_post_' . $i]) && (int)$_POST['img_post_' . $i]>0 ) ? (int)$_POST['img_post_' . $i] : 0
            );
        }

        $id = (int)$_POST['zm-gallery-itemid'];

        $list = trim(json_encode($pictures));
        $main = ( isset($_POST['zm-gallery-main']) ) ? (int)$_POST['zm-gallery-main'] : 0;
        $name = trim($_POST['zm-gallery-name']);
        $width = (int)($_POST['zm-gallery-width']);
        $authorid = (int)$_POST['zm-gallery-user'];
        $sql = array(
            'name' => $name,
            'data' => $list,
            'width' => $width,
            'main' => $main,
            'authorid' => $authorid
        );

        if($main === 1) {
            $wpdb->query('UPDATE '.$table_name.' SET main=0 WHERE id != ' . $id );
        }
        if(
            $wpdb->update(
                $table_name,
                $sql,
                array( 'id' => $id ),
                array( '%s', '%s', '%d', '%d' ),
                array( '%d' )
            )
        ) {
            print 1;
        }
        else {
            $wpdb->show_errors();
            print $wpdb->print_error();
            print 0;
        }
    }

    function zm_gallery_add() {
        include_once 'zm-gallery-add.php';
    }

    function zm_gallery_add_save() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'zm_gallery';
        $pictures = array();

        for($i=0; $i<(int)$_POST['len']; $i++) {
            $pictures[ $_POST['img_id_' . $i] ] = array(
                'src' => $_POST['img_' . $i],
                'pos' => $_POST['img_pos_' . $i],
                'post' => ( isset($_POST['img_post_' . $i]) && (int)$_POST['img_post_' . $i]>0 ) ? (int)$_POST['img_post_' . $i] : 0
            );
        }

        $list = trim(json_encode($pictures));
        $datetime = new \DateTime('now');
        $datetime_str = $datetime->format('Y-m-d H:i:s');
        $main = ( isset($_POST['zm-gallery-main']) ) ? (int)$_POST['zm-gallery-main'] : 0;
        $name = trim($_POST['zm-gallery-name']);
        $width = (int)($_POST['zm-gallery-width']);
        $authorid = (int)$_POST['zm-gallery-user'];
        $sql = array(
            'name' => $name,
            'data' => $list,
            'width' => $width,
            'time' => $datetime_str,
            'main' => $main,
            'authorid' => $authorid
        );


        if( $wpdb->insert( $table_name, $sql, array( '%s', '%s', '%s', '%d', '%d' ) ) ) {
            if($main === 1) {
                $wpdb->query('UPDATE '.$table_name.' SET main=0 WHERE id != ' . $wpdb->insert_id );
            }
            print $wpdb->insert_id;
        }
        else {
            print 0;
        }
    }

    function zm_gallery_settings() {
        include_once 'zm-gallery-settings.php';
    }

    function zm_gallery_settings_save() {
        $main_slider = ( isset($_POST['zm-slider-main']) && 1 === (int)$_POST['zm-slider-main'] ) ? (int)$_POST['zm-slider-main'] : '';
        $replace_slider = ( isset($_POST['zm-slider-replace']) && 1 === (int)$_POST['zm-slider-replace'] ) ? (int)$_POST['zm-slider-replace'] : '';

        update_option( 'zm_gallery_header_slider', $main_slider );
        update_option( 'zm_gallery_replace_slider', $replace_slider );
        exit();
    }

    function zm_gallery_delete() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'zm_gallery';

        include_once ABSPATH . WPINC . '/pluggable.php';

        $wpdb->delete( $table_name, array( 'id' => (int)$_GET['itemid'] ), array( '%d' ) );
        wp_redirect( admin_url( 'admin.php?page=zm_gallery' ) );
        exit();

    }

    function zm_gallery_shortcode( $atts ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'zm_gallery';

        $table_name = $wpdb->prefix . 'zm_gallery';
        $gallery = $wpdb->get_row( "SELECT * FROM " . $table_name . " WHERE id = ".(int)$atts['id'] );

        $html = '';
        if($gallery) {
            $images = json_decode($gallery->data);
            $img_src = '';
            $j = 0;
            foreach($images as $i) {
                $display = '';
                $class = 'class="curr"';
                if($j>0) {
                    $display = 'style="display: none;"';
                    $class = '';
                }
                $img_src .= '<img src="' . $i->src . '" ' . $class . ' ' . $display . ' />';
                $j++;
            }

            $html .= '<div class="wp-caption">
                    <div class="zm-gallery">
                        <div class="zm-gallery-img">
                            <div class="zm-gallery-img-center">
                                <div class="zm-gallery-l"><span></span></div>
                                <div class="zm-gallery-r"><span></span></div>
                                <div class="zm-gallery-img-list">
                                    ' . $img_src . '
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
        }


        return $html;
    }

    function zm_gallery_wp_shortcode( $html, $attr ) {
        global $post, $wp_locale;

        // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
        if ( isset( $attr['ids'] ) ) {
            apply_filters('gallery_style', "");
            $ids = explode(',',$attr['ids']);
            $img_src = '';

            $j = 0;
            foreach($ids as $id) {
                if( (int)$id > 0 ) {
                    $display = '';
                    $class = 'curr';
                    if($j>0) {
                        $display = 'display: none;';
                        $class = '';
                    }

                    $img_src .= wp_get_attachment_image( (int)$id, 'full', false, array(
                        'class'	=> $class,
                        'style' => $display
                    ) );
                    $j++;
                }
            }

            $html = '<div class="wp-caption">
                    <div class="zm-gallery">
                        <div class="zm-gallery-img">
                            <div class="zm-gallery-img-center">
                                <div class="zm-gallery-l"><span></span></div>
                                <div class="zm-gallery-r"><span></span></div>
                                <div class="zm-gallery-img-list">
                                    ' . $img_src . '
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
        }

        return $html;
    }

    function zm_gallery_plugin_create() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'zm_gallery';

        $charset = '';
        if ( !empty($wpdb -> charset) ) {
            $charset = "DEFAULT CHARACTER SET $wpdb->charset";
        }
        if ( !empty($wpdb -> collate) ) {
            $charset .= " COLLATE $wpdb->collate";
        }

        $query = "CREATE TABLE $table_name (
                id INT(11) NOT NULL AUTO_INCREMENT,
                name tinytext DEFAULT '' NOT NULL,
                data TEXT DEFAULT '' NOT NULL,
                time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
                main INT(1) NOT NULL DEFAULT 0,
                width INT NOT NULL DEFAULT 0,
                authorid tinytext NOT NULL,
                PRIMARY KEY  (id)
            ) $charset;";

        $wpdb->query($query);

        update_option( 'zm_gallery_replace_slider', 1 );
    }

    function zm_gallery_plugin_delete() {
        if ( ! current_user_can( 'activate_plugins' ) )
            return;
        global $wpdb;
        $table_name = $wpdb->prefix . 'zm_gallery';
        $wpdb->query("DROP TABLE IF EXISTS $table_name");
    }
}

$zm_gallery = new ZMGallery();

register_activation_hook( __FILE__, array( 'ZMGallery', 'zm_gallery_plugin_create' ) );
register_uninstall_hook( __FILE__, array( 'ZMGallery', 'zm_gallery_plugin_delete' ) );


if( is_admin() ) {
    function zm_gallery_admin_style_and_script() {
        wp_register_style( 'zm_gallery_admin_css', plugins_url() . '/zm-gallery/css/zm-gallery-admin.css', false, '1.0.0' );
        wp_enqueue_style( 'zm_gallery_admin_css' );

        wp_enqueue_media();

        wp_enqueue_script( 'zm_gallery_admin_js', plugin_dir_url( __FILE__ ) . 'js/zm-gallery-admin.js' );

        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-draggable');
        wp_enqueue_script('jquery-ui-droppable');
        wp_enqueue_script('jquery-ui-mouse');
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-widget');

        wp_enqueue_script('media-upload');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
    }
    add_action( 'admin_enqueue_scripts', 'zm_gallery_admin_style_and_script' );
}
function zm_gallery_style_and_script() {
    wp_register_style( 'zm_gallery_css', plugins_url() . '/zm-gallery/css/zm-gallery.css', false, '1.0.0' );
    wp_enqueue_style( 'zm_gallery_css' );

    wp_register_script( 'zm_gallery_js', plugins_url() . '/zm-gallery/js/zm-gallery.js', false );
    wp_enqueue_script( 'zm_gallery_js' );
}
add_action( 'wp_enqueue_scripts', 'zm_gallery_style_and_script' );












