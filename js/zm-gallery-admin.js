var gk_media_init = function(selector, button_selector)  {
    var clicked_button = false;

    jQuery(selector).each(function (i, input) {
        var button = jQuery(button_selector);
        button.click(function (event) {
            event.preventDefault();
            var selected_img;
            clicked_button = jQuery(this);

            // check for media manager instance
            if(wp.media.frames.gk_frame) {
                wp.media.frames.gk_frame.open();
                return;
            }
            // configuration of the media manager new instance
            wp.media.frames.gk_frame = wp.media({
                title: 'Select image',
                multiple: false,
                library: {
                    type: 'image'
                },
                button: {
                    text: 'Use selected image'
                }
            });

            // Function used for the image selection and media manager closing
            var gk_media_set_image = function() {
                var selection = wp.media.frames.gk_frame.state().get('selection');

                // no selection
                if (!selection) {
                    return;
                }

                selection.each(function(attachment) {
                    var src = attachment.attributes.url;
                    var element = '<li class="ui-state-default" style="background: url(' + src + ') 0 0 no-repeat; -webkit-background-size: cover;background-size: cover;" data-id="' + attachment.cid + '" data-src="' + src + '"><a href="#TB_inline?width=600&height=550&inlineId=zm-gallery-posts-list" class="zm-slider-images-link"></a><div class="zm-slider-images-delete"></div></li>';
                    
                    jQuery(selector).append(element);
                    jQuery(selector).show();
                });
            };

            // closing event for media manger
            //wp.media.frames.gk_frame.on('close', gk_media_set_image);
            // image selection event
            wp.media.frames.gk_frame.on('select', gk_media_set_image);
            // showing media manager
            wp.media.frames.gk_frame.open();
        });
    });
};

var zm_gallery_curr_image = null;
var zm_gallery_curr_post_id = null;

jQuery(document).ready(function($){
    gk_media_init('#zm-slider-images', '.upload');

    jQuery('#zm-slider-images').sortable();

    $( 'form.ajax-form' ).submit(function() {
        var $form = jQuery(this);
        var data_str = $(this).serialize();
        if( $('#zm-slider-images').length > 0 ) {
            var $list = $('#zm-slider-images li');
            var length = $list.length;
            if(length>0) {
                $list.each(function (i) {
                    var post = (  $(this).attr('data-post') === undefined ) ? 0 : $(this).attr('data-post');
                    data_str += '&img_' + i + '=' + $(this).data('src');
                    data_str += '&img_id_' + i + '=' + $(this).data('id');
                    data_str += '&img_pos_' + i + '=' + (i+1);
                    data_str += '&img_post_' + i + '=' + post;
                });

                data_str += '&len=' + length;
            }
            else {
                alert( jQuery('#zm-slider-images').data('empty') );
                return false;
            }
        }

        $.ajax({
            data: data_str,
            type: "POST",
            beforeSend: function() {
                $( '.status strong' ).html( 'Saving...' );
                $( '.status' ).removeClass( 'done' );
                $( '.status' ).fadeIn();
            },
            success: function(data) {
                if( $form.data('insert') !== undefined ) {
                    location.href = $form.data('redirect');
                }
                $( '.status' ).addClass( 'done' );
                $( '.status strong' ).html( 'Done.' );
                $( '.status' ).delay( 1000 ).fadeOut();
            }
        });
        return false;
    });
    
    $('#insert-zm-gallery').click(function () {

    });
});

function checkSelectedPost() {
    var $curr = jQuery('#TB_ajaxContent .zm-slider-images-links-elem[data-id="'+zm_gallery_curr_post_id+'"]');

    if($curr.length>0) {
        $curr.addClass('curr')
    }
}

jQuery(document).on('click','.zm-slider-images-delete',function () {
    jQuery(this).parents('li').first().remove();
    if( jQuery('#zm-slider-images li').length < 1 ) {
        jQuery('#zm-slider-images').hide();
    }
});
jQuery(document).on('click','.zm-slider-images-link',function () {
    var data = {
        'zm_gallery_posts': 1,
        'page' : 0
    };
    jQuery.ajax({
        data: data,
        type: "POST",
        beforeSend: function() {},
        success: function(data) {
            jQuery('#TB_ajaxContent').html(data);

            checkSelectedPost();
        }
    });

    zm_gallery_curr_image = jQuery(this).parents('li').data('id');
    zm_gallery_curr_post_id = jQuery(this).parents('li').attr('data-post');

    tb_show("Posts", jQuery(this).attr('href'));
    return false;
});
jQuery(document).on('click','.zm-slider-images-paginator-link',function () {
    if( jQuery(this).hasClass('curr') ) {
        return false;
    }

    var page = jQuery(this).html();

    var data = {
        'zm_gallery_posts': 1,
        'page' : page-1
    };
    jQuery.ajax({
        data: data,
        type: "POST",
        beforeSend: function() {},
        success: function(data) {
            jQuery('#TB_ajaxContent').html(data);

            checkSelectedPost();
        }
    });

    return false;
});
jQuery(document).on('click','.zm-slider-images-paginator-prev',function () {
    if( jQuery(this).hasClass('disabled') ) {
        return false;
    }

    var page = jQuery('.zm-slider-images-paginator-link.curr').html();

    var data = {
        'zm_gallery_posts': 1,
        'page' : page-2
    };
    jQuery.ajax({
        data: data,
        type: "POST",
        beforeSend: function() {},
        success: function(data) {

            jQuery('#TB_ajaxContent').html(data);

            checkSelectedPost();
        }
    });

    return false;
});
jQuery(document).on('click','.zm-slider-images-paginator-next',function () {
    if( jQuery(this).hasClass('disabled') ) {
        return false;
    }

    var page = jQuery('.zm-slider-images-paginator-link.curr').html();

    var data = {
        'zm_gallery_posts': 1,
        'page' : page
    };
    jQuery.ajax({
        data: data,
        type: "POST",
        beforeSend: function() {},
        success: function(data) {

            jQuery('#TB_ajaxContent').html(data);

            checkSelectedPost();
        }
    });

    return false;
});
jQuery(document).on('click','.zm-slider-images-links-elem',function () {
    var $img = jQuery('li[data-id="' + zm_gallery_curr_image + '"]');

    $img.attr('data-post', jQuery(this).data('id'));
    $img.find('.zm-slider-images-link').addClass('link-selected');

    tb_remove();

    return false;
});


jQuery(document).on('click','.zm-gallery-list li',function () {
    jQuery('.zm-gallery-list li').removeClass('curr');
    jQuery(this).addClass('curr');
});

jQuery(document).on('click','#zm-gallery-list-select',function () {
    var $list = jQuery('.zm-gallery-list li.curr');
    if( $list.length < 1 ) {
        alert(jQuery(this).data('empty') );
    }
    else {
        var id = $list.data('id')*1;
        var shortcode = '[zm_gallery id="' + id + '"]';
        window.tb_remove();

        tinyMCE.execCommand('mceInsertContent',false,shortcode);
    }
    return false;
});