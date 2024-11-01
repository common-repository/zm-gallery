<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$option_action = admin_url( 'admin.php?page=zm_gallery_add' );
?>
<div class="status" style="display: none;">
    <div class="status_txt">
        <img src="<?php echo plugins_url(); ?>/zm-gallery/images/loader.gif" alt=""/> <strong>Saving...</strong>
    </div>
</div>
<div class="wrap">
    <h1><?php _e('Add new ZM Gallery'); ?></h1>
    <div class="layout">
        <form action="<?php echo $option_action; ?>" class="ajax-form" method="post" data-insert="1" data-redirect="<?php echo admin_url( 'admin.php?page=zm_gallery' ); ?>">
            <?php
            wp_nonce_field( 'zm_gallery_add' );
            ?>
            <input type="hidden" id="zm-gallery-user" name="zm-gallery-user" value="<?php echo get_current_user_id(); ?>"/>
            <table class="admin_table">
                <tr>
                    <td class="fld">
                        <h4><?php _e('Name', 'zm_gallery'); ?></h4>
                        <input type="text" id="zm-gallery-name" name="zm-gallery-name" style="width: 100%;" value="">
                    </td>
                    <td><?php _e('Name of the slider', 'zm_gallery'); ?></td>
                </tr>
                <tr>
                    <td class="fld">
                        <h4><?php _e('Add pictures', 'zm_gallery'); ?></h4>
                        <input type="button" class="button upload" value="Browse pictures">
                        <ul id="zm-slider-images" style="display: none;" data-empty="<?php _e('Select pictures for gallery!', 'zm_gallery'); ?>"></ul>
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
                        <input type="text" id="zm-gallery-width" name="zm-gallery-width" style="width: 20%;" value="" placeholder="1170">
                    </td>
                    <td>Recommended size 1170px &times; 550px</td>
                </tr>
                <tr>
                    <td class="fld">
                        <h4><?php _e('Main slider', 'zm_gallery'); ?></h4>
                        <input type="checkbox" value="1" name="zm-gallery-main" id="zm-gallery-main"/> <label for="zm-gallery-main"><?php _e('Set Gallery In header of the site', 'zm_gallery'); ?></label>
                    </td>
                    <td>
                        <?php _e('Check it, if you want set this gallery in the header of the blog', 'zm_gallery'); ?>
                        <?php _e('If you check it, other gallery which has been checked will set as unchecked.', 'zm_gallery'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="fld">
                        <input type="submit" name="submit" class="button button-primary button-large" value="<?php _e('Save Changes', 'zm_gallery'); ?>">
                        <input type="hidden" name="add_zm_gallery" value="Y">
                    </td>
                    <td></td>
                </tr>
            </table>
        </form>
    </div>
</div>

<div id="zm-gallery-posts-list" style="display:none"></div>
