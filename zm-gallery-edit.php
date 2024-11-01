<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$option_action = admin_url( 'admin.php?page=zm_gallery' );

$id = (int)$_GET['itemid'];
$table_name = $wpdb->prefix . 'zm_gallery';
$gallery = $wpdb->get_row('SELECT * FROM ' . $table_name . ' WHERE id=' . $id);


$name = $gallery->name;
$width = ( ! isset($gallery->width) ) ? 1170 : $gallery->width;
$main_checked = ( (int)$gallery->main === 1 ) ? 'checked="checked"' : '';
$images = json_decode( $gallery->data );

$images_html = '';
foreach($images as $key => $val) {
    $select_class = $val->post > 0 ? 'link-selected' : '';
    $images_html .= '<li class="ui-state-default" style="background: url(' . $val->src . ') 0 0 no-repeat; -webkit-background-size: cover;background-size: cover;" data-post="' . $val->post . '" data-id="' . $key . '" data-src="' . $val->src . '"><a href="#TB_inline?width=600&height=550&inlineId=zm-gallery-posts-list" class="zm-slider-images-link ' . $select_class . '"></a><div class="zm-slider-images-delete"></div></li>';
}
?>

<div class="status" style="display: none;">
    <div class="status_txt">
        <img src="<?php echo plugins_url(); ?>/zm-gallery/images/loader.gif" alt=""/> <strong>Saving...</strong>
    </div>
</div>
<div class="wrap">
    <h1><?php _e('Edit "' . $name . '" ZM Gallery', 'zm_gallery'); ?></h1>
    <div class="layout">
        <form action="<?php echo $option_action; ?>" class="ajax-form" method="post">
            <?php
            wp_nonce_field( 'zm_gallery_edit' );
            ?>
            <input type="hidden" id="zm-gallery-user" name="zm-gallery-user" value="<?php echo get_current_user_id(); ?>"/>
            <input type="hidden" id="zm-gallery-itemid" name="zm-gallery-itemid" value="<?php echo $id; ?>"/>
            <table class="admin_table">
                <tr>
                    <td class="fld">
                        <h4><?php _e('Name', 'zm_gallery'); ?></h4>
                        <input type="text" id="zm-gallery-name" name="zm-gallery-name" style="width: 100%;" value="<?php echo $name; ?>">
                    </td>
                    <td><?php _e('Name of the slider', 'zm_gallery'); ?></td>
                </tr>
                <tr>
                    <td class="fld">
                        <h4><?php _e('Add pictures', 'zm_gallery'); ?></h4>
                        <input type="button" class="button upload" value="Browse pictures">
                        <?php
                        if( $images_html != '' ) {
                            ?>
                            <ul id="zm-slider-images" data-empty="<?php _e('Select pictures for gallery!', 'zm_gallery'); ?>">
                                <?php echo $images_html;?>
                            </ul>
                            <?php
                        }
                        else {
                            ?>
                            <ul id="zm-slider-images" style="display: none;" data-empty="<?php _e('Select pictures for gallery!', 'zm_gallery'); ?>"></ul>
                            <?php
                        }
                        ?>
                    </td>
                    <td>
                        <p><?php _e('Click `Browse pictures` button and select a picture.', 'zm_gallery'); ?></p>
                        <p><?php _e('After adding a picture you can delete it or add a link to some post.', 'zm_gallery'); ?></p>
                        <p><?php _e('For deleting click at delete icon in right top corner of the picture', 'zm_gallery'); ?></p>
                        <p><?php _e('For adding a link click at link icon in right bottom corner of the picture. If icon has gray background thats mean that a picture has no link to the posts.', 'zm_gallery'); ?></p>
                        <p><?php _e('Also you can change pictures position by drag n drop method.', 'zm_gallery'); ?></p>
                    </td>
                </tr>
                <tr style="display: none;">
                    <td class="fld">
                        <h4><?php _e('Slider image width', 'zm_gallery'); ?></h4>
                        <input type="text" id="zm-gallery-width" name="zm-gallery-width" style="width: 20%;" value="<?php echo $width; ?>">
                    </td>
                    <td></td>
                </tr>
                <tr>
                    <td class="fld">
                        <h4><?php _e('Main slider', 'zm_gallery'); ?></h4>
                        <input type="checkbox" value="1" name="zm-gallery-main" id="zm-gallery-main" <?php echo $main_checked; ?>/> <label for="zm-gallery-main"><?php _e('Set Gallery In header of the site', 'zm_gallery'); ?></label>
                    </td>
                    <td>
                        <?php _e('Check it, if you want set this gallery in the header of the blog', 'zm_gallery'); ?>
                        <?php _e('If you check it, other gallery which has been checked will set as unchecked.', 'zm_gallery'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="fld">
                        <input type="submit" name="submit" class="button button-primary button-large" value="<?php _e('Save Changes', 'zm_gallery'); ?>">
                        <input type="hidden" name="edit_zm_gallery" value="Y">
                    </td>
                    <td></td>
                </tr>
            </table>
        </form>
    </div>
</div>

<div id="zm-gallery-posts-list" style="display:none"></div>
