<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$option_action = admin_url( 'admin.php?page=zm_gallery_settings' );
?>
<div class="status" style="display: none;">
    <div class="status_txt">
        <img src="<?php echo plugins_url(); ?>/zm-gallery/images/loader.gif" alt=""/> <strong>Saving...</strong>
    </div>
</div>
<div class="wrap">
    <h1><?php _e('ZM Gallery Settings'); ?></h1>
    <div class="layout">
        <form action="<?php echo $option_action; ?>" class="ajax-form" method="post">
            <?php
            wp_nonce_field( 'zm_gallery_settings' );
            ?>
            <input type="hidden" id="zm-gallery-user" name="zm-gallery-user" value="<?php echo get_current_user_id(); ?>"/>
            <table class="admin_table">
                <tr>
                    <td class="fld">
                        <?php
                        $header_slider = get_option( 'zm_gallery_header_slider' );
                        if($header_slider && 1 == $header_slider) {
                            $header_slider_checked = ' checked="checked"';
                        }
                        else {
                            $header_slider_checked = '';
                        }
                        ?>
                        <h4><?php _e('Slider in header'); ?></h4>
                        <input type="checkbox" value="1" name="zm-slider-main" id="zm-slider-main" <?php echo $header_slider_checked; ?> /> <label for="zm-slider-main"><?php _e('Show slider in header'); ?></label>
                    </td>
                    <td>
                        <?php _e('If checked, slider in the header will be shown on the home page', 'zm_gallery'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="fld">
                        <?php
                        $replace_slider = get_option( 'zm_gallery_replace_slider' );
                        if($replace_slider && 1 == $replace_slider) {
                            $replace_slider_checked = ' checked="checked"';
                        }
                        else {
                            $replace_slider_checked = '';
                        }
                        ?>
                        <h4><?php _e('Replace wordpress gallery'); ?></h4>
                        <input type="checkbox" value="1" name="zm-slider-replace" id="zm-slider-replace"  <?php echo $replace_slider_checked; ?> /> <label for="zm-slider-replace"><?php _e('Replace wordpress gallery to the ZM Gallery'); ?></label>
                    </td>
                    <td>
                        <?php _e('If checked, ZM Gallery will customizing default wordpress gallery.', 'zm_gallery'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="fld">
                        <input type="submit" name="submit" class="button button-primary button-large" value="<?php _e('Save Changes'); ?>">
                        <input type="hidden" name="settings_zm_gallery" value="Y">
                    </td>
                    <td></td>
                </tr>
            </table>
        </form>
    </div>
</div>
