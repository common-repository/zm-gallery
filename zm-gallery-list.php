<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
$option_action = admin_url( 'admin.php?page=zm_gallery' );

if( isset($_GET['set_main']) && (int)$_GET['set_main'] > 0 ) {
    $table_name = $wpdb->prefix . 'zm_gallery';

    $wpdb->query('UPDATE '.$table_name.' SET main=0 WHERE id != ' . (int)$_GET['set_main'] );
    $wpdb->update(
        $table_name,
        array(
            'main' => 1
        ),
        array( 'id' => (int)$_GET['set_main'] ),
        array( '%d' ),
        array( '%d' )
    );
}
?>

<div class="wrap">
    <h1><?php _e('ZM Gallery Plugin'); ?> <a href="<?php echo admin_url() . 'admin.php?page=zm_gallery_add'; ?>" class="add-new-h2"> Add new</a></h1>
    <form id="slider-list-table" method="post" action="<?php echo $option_action; ?>">
        <?php
        wp_nonce_field( 'zm_gallery_list' );
        ?>
        <?php
        /*
         *
        <div class="tablenav top">

            <div class="alignleft actions bulkactions">
                <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label><select name="action" id="bulk-action-selector-top">
                    <option value="-1" selected="selected">Bulk Actions</option>
                    <option value="delete">Delete</option>
                </select>
                <input type="submit" id="doaction" class="button action" value="Apply">
            </div>

            <br class="clear">
        </div>
         */
        ?>
        <?php
            $link = admin_url( 'admin.php?page=zm_gallery' );
        ?>
        <table class="wp-list-table widefat fixed striped">
            <thead>
            <tr>
                <td id="cb" class="manage-column column-cb check-column">
                    <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
                    <input id="cb-select-all-1" type="checkbox">
                </td>
                <?php
                $id_order = 'desc';
                $id_order_class = 'asc';
                $id_order_sorted = '';
                if( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'id' && isset( $_GET['order'] ) && $_GET['order'] == 'desc' ) {
                    $id_order = 'asc';
                    $id_order_class = 'desc';
                }
                if( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'id') {
                    $id_order_sorted = 'sorted';
                }
                $id_link = $link . '&orderby=id&order=' . $id_order;

                $name_order = 'desc';
                $name_order_class  = 'asc';
                $name_order_sorted = '';
                if( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'name' && isset( $_GET['order'] ) && $_GET['order'] == 'desc' ) {
                    $name_order = 'asc';
                    $name_order_class  = 'desc';
                }
                if( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'name') {
                    $name_order_sorted = 'sorted';
                }
                $name_link = $link . '&orderby=name&order=' . $name_order;

                $time_order = 'desc';
                $time_order_class  = 'asc';
                $time_order_sorted = '';
                if( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'time' && isset( $_GET['order'] ) && $_GET['order'] == 'desc' ) {
                    $time_order = 'asc';
                    $time_order_class  = 'desc';
                }
                if( isset( $_GET['orderby'] ) && $_GET['orderby'] == 'time') {
                    $time_order_sorted = 'sorted';
                }
                $time_link = $link . '&orderby=time&order=' . $time_order;
                ?>
                <th scope="col" id="id" class="manage-column column-id column-primary sortable <?php echo $id_order_sorted; ?> <?php echo $id_order_class; ?>">
                    <a href="<?php echo $id_link; ?>">
                        <span>ID</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" id="name" class="manage-column column-name sortable <?php echo $name_order_sorted; ?> <?php echo $name_order_class; ?>">
                    <a href="<?php echo $name_link; ?>">
                        <span>Name</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" id="shortcode" class="manage-column column-shortcode">Shortcode</th>
                <th scope="col" id="phpcode" class="manage-column column-phpcode">PHP code</th>
                <th scope="col" id="time" class="manage-column column-time sortable <?php echo $time_order_sorted; ?> <?php echo $time_order_class; ?>">
                    <a href="<?php echo $time_link; ?>">
                        <span>Created</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" id="main-slider" class="manage-column column-main-slider"></th>
            </tr>
            </thead>

            <tbody id="the-list">
            <?php
            $order = 'id';
            if( isset($_GET['orderby']) ) {
                $order = ' ORDER BY ' . esc_sql($_GET['orderby']);

                if( isset($_GET['order']) ) {
                    $order .= ' ' . esc_sql($_GET['order']);
                }
            }

            $table_name = $wpdb->prefix . 'zm_gallery';
            $gallery = $wpdb->get_results("SELECT * FROM $table_name " . $order );
            foreach ( $gallery as $g ) {
                $id = (int)$g->id;
                $name = $g->name;
                $date = $g->time;
                $main = (int)$g->main;
                ?>
                <tr>
                    <th scope="row" class="check-column">
                        <input type="checkbox" name="itemid[]" value="1">
                    </th>
                    <td class="id column-id has-row-actions column-primary" data-colname="ID">
                        <?php echo $id; ?> <div class="row-actions">
                            <span class="delete"><a href="?page=zm_gallery&amp;delete=1&amp;itemid=<?php echo $id; ?>">Delete</a> | </span>
                            <span class="edit"><a href="?page=zm_gallery&amp;itemid=<?php echo $id; ?>&amp;edit=1">Edit</a></span>
                        </div>
                        <button type="button" class="toggle-row">
                            <span class="screen-reader-text">Show more details</span>
                        </button>
                        <button type="button" class="toggle-row">
                            <span class="screen-reader-text">Show more details</span>
                        </button>
                    </td>
                    <td class="name column-name" data-colname="Name"><?php echo $name; ?></td>
                    <td class="shortcode column-shortcode" data-colname="Shortcode">[zm_gallery id="<?php echo $id; ?>"]</td>
                    <td class="phpcode column-phpcode" data-colname="PHP code">
                        &lt;?php echo do_shortcode('[zm_gallery id="<?php echo $id; ?>"]'); ?&gt;

                        <?php
                        if( 1 === (int)$main) {
                            ?><br/><br/>
                            &lt;?php<br/>
                            $ZMGallery = new ZMGallery();<br/>
                            $ZMGallery->zm_gallery_main_slider();<br/>
                            ?&gt;
                            <?php
                        }
                        ?>
                    </td>
                    <td class="time column-time" data-colname="Created"><?php echo $date; ?></td>
                    <td class="time column-main-slider" data-colname="Main slider">
                        <?php
                        if( 1 === (int)$main) {
                            _e('Main slider (show in header)');
                        }
                        else {
                            echo '<a href="' . $link . '&set_main=' . $id . '">';
                            _e('Set as main slider');
                            echo '</a>';
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
            </tbody>

            <tfoot>
            <tr>
                <td class="manage-column column-cb check-column">
                    <label class="screen-reader-text" for="cb-select-all-2">Select All</label>
                    <input id="cb-select-all-2" type="checkbox">
                </td>
                <th scope="col" class="manage-column column-id column-primary sortable <?php echo $id_order_sorted; ?> <?php echo $id_order_class; ?>">
                    <a href="<?php echo $id_link; ?>">
                        <span>ID</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-name sortable <?php echo $name_order_sorted; ?> <?php echo $name_order_class; ?>">
                    <a href="<?php echo $name_link; ?>">
                        <span>Name</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-shortcode">Shortcode</th>
                <th scope="col" class="manage-column column-phpcode">PHP code</th>
                <th scope="col" class="manage-column column-time sortable <?php echo $time_order_sorted; ?> <?php echo $time_order_class; ?>">
                    <a href="<?php echo $time_link; ?>">
                        <span>Created</span>
                        <span class="sorting-indicator"></span>
                    </a>
                </th>
                <th scope="col" class="manage-column column-main-slider"></th>
            </tr>
            </tfoot>

        </table>
        <?php
        /*
         *
        <div class="tablenav bottom">

            <div class="alignleft actions bulkactions">
                <label for="bulk-action-selector-bottom" class="screen-reader-text">Select bulk action</label><select name="action2" id="bulk-action-selector-bottom">
                    <option value="-1" selected="selected">Bulk Actions</option>
                    <option value="delete">Delete</option>
                </select>
                <input type="submit" id="doaction2" class="button action" value="Apply">
            </div>

            <br class="clear">
        </div>
         */
        ?>

    </form>
</div>
