var zm_gallery_start = false;

jQuery(document).ready(function () {
    jQuery('.zm-gallery-l').click(function () {
        if(zm_gallery_start) {
            return false;
        }
        zm_gallery_start = true;
        var $parent = jQuery(this).parents('.zm-gallery-img');

        var $list = $parent.find('.zm-gallery-img-list img');
        
        var $curr = $parent.find('.zm-gallery-img-list .curr');
        var $curr_index = $curr.index();
        var $next_index = $curr_index-1;
        if($next_index<0) {
            $next_index = $list.length-1
        }
        var $next = $list.eq($next_index);

        $curr.addClass('zm-galery-opacity-hide');
        $next.addClass('zm-galery-opacity-hide-no-animate');
        setTimeout(function () {
            $curr.hide();
            $next.show();
            $next.addClass('zm-galery-opacity-show');
            $list.removeClass('curr');
            $next.addClass('curr');
            setTimeout(function () {
                zm_gallery_start = false;
                $next.removeClass('zm-galery-opacity-hide-no-animate');
                $next.removeClass('zm-galery-opacity-show');
                $list.removeClass('zm-galery-opacity-hide');
            },700);
        },700);
    });

    jQuery('.zm-gallery-r').click(function () {
        if(zm_gallery_start) {
            return false;
        }
        zm_gallery_start = true;
        var $parent = jQuery(this).parents('.zm-gallery-img');
        var $list = $parent.find('.zm-gallery-img-list img');

        var $curr = $parent.find('.zm-gallery-img-list .curr');
        var $curr_index = $curr.index();
        var $next_index = $curr_index+1;
        if($next_index>$list.length-1) {
            $next_index = 0;
        }

        var $next = $list.eq($next_index);

        $curr.addClass('zm-galery-opacity-hide');
        $next.addClass('zm-galery-opacity-hide-no-animate');
        setTimeout(function () {
            $curr.hide();
            $next.show();
            $next.addClass('zm-galery-opacity-show');
            $list.removeClass('curr');
            $next.addClass('curr');
            setTimeout(function () {
                zm_gallery_start = false;
                $next.removeClass('zm-galery-opacity-hide-no-animate');
                $next.removeClass('zm-galery-opacity-show');
                $list.removeClass('zm-galery-opacity-hide');
            },700);
        },700);
    });

    if( jQuery('.zm-gallery-main').length > 0 ) {
        /* Main slider init */
        var ww = jQuery(window).width();
        var $images_list = jQuery('.zm-gallery-main .zm-gallery-main-img img');
        var images_loaded = 0;
        var images_list = [];
        $images_list.each(function () {
            var image = new Image();
            var src = jQuery(this).attr('src');
            image.onload = function () {
                images_loaded++;

                images_list[images_list.length] = {
                    src: src,
                    height: this.height,
                    width: this.width
                };


                if(images_loaded >= $images_list.length) {
                    var max_height = 0;
                    for(var i=0; i<images_loaded; i++) {
                        if(images_list[i]['height'] > max_height) {
                            max_height = images_list[i]['height'];
                        }
                    }
                    jQuery('.zm-gallery-main-img').attr('data-maxheight',max_height);

                    var $curr = $images_list.eq(0).parents('a').first();
                    var $right = $images_list.eq(1).parents('a').first();

                    $curr.addClass('curr').show();
                    $right.addClass('right').show().css({opacity: 0});

                    var ratio = zm_gallery_width/max_height;
                    var curr_width = (ww<zm_gallery_width) ? ww : zm_gallery_width;
                    max_height = curr_width/ratio;

                    var left_index = images_loaded-1;

                    if( images_loaded>=3 ) {
                        var $left = $images_list.eq( left_index ).parents('a').first();
                        $left.addClass('left').show().css({opacity: 0});
                    }
                    else {
                        $left = $curr.prev();
                        if(images_loaded == 2) {
                            jQuery('.zm-gallery-main-l').hide();
                        }
                        if(images_loaded == 1) {
                            jQuery('.zm-gallery-main-l').hide();
                            jQuery('.zm-gallery-main-r').hide();
                        }
                    }

                    jQuery('.zm-gallery-loader').animate({
                        opacity: 0
                    },400, function () {
                        jQuery('.zm-gallery-loader').hide();
                        jQuery('.zm-gallery-main-bg').css({opacity: 0}).show().addClass('zm-galery-opacity-show');
                        jQuery('.zm-gallery-main-img').css({opacity: 0}).show().addClass('zm-galery-opacity-show');
                        jQuery('.zm-gallery-main-img').animate({
                            height: max_height
                        },700,function() {
                            jQuery('.zm-gallery-main-img-list-block-txt').css('height','100%');
                            jQuery('.zm-gallery-main-img-list').css({height: max_height});
                            jQuery('.zm-gallery-main-img').css({opacity: ''});
                            jQuery('.zm-gallery-main-bg').css({opacity: ''});
                        });

                        jQuery('.zm-gallery-main-img-list-block-txt').animate({
                            height: max_height
                        },700, function () {
                            if( $right.length > 0 ) {
                                $right.addClass('zm-galery-opacity-not-full-show');
                            }
                            if( $left.length > 0 ) {
                                $left.addClass('zm-galery-opacity-not-full-show');
                            }
                            setTimeout(function () {
                                if( $right.length > 0 ) {
                                    $right.css({opacity: ''});
                                }
                                if( $left.length > 0 ) {
                                    $left.css({opacity: ''});
                                }
                                jQuery('.zm-gallery-main-img-list-block-txt').css('height','100%');

                                $images_list.each(function () {
                                    jQuery(this).parents('a').first().removeClass('zm-galery-opacity-not-full-show').removeClass('zm-galery-opacity-show').height('100%');
                                });

                            },400);
                        });
                    });
                }
            };
            image.src = src;

        });
        /* Main slider init */
    }

    jQuery('.zm-gallery-main-l').click(function () {
        if(zm_gallery_start) {
            return false;
        }
        zm_gallery_start = true;
        var animation_time = 1400;
        var ww = jQuery(window).width();
        var $parent = jQuery(this).parents('.zm-gallery-main').first();
        var $list = $parent.find('.zm-gallery-main-img-list-block');
        var $curr = $parent.find('.zm-gallery-main-img-list-block.curr');
        var $right = $parent.find('.zm-gallery-main-img-list-block.right');
        var $left = $parent.find('.zm-gallery-main-img-list-block.left');


        $curr.addClass('zm-gallery-main-leftbtn-curr');
        if($right.length>0) {
            $right.addClass('zm-gallery-main-leftbtn-right');
        }
        if($left.length>0) {
            $left.addClass('zm-gallery-main-leftbtn-left');

            if($list.length > 2) {
                var $new_left = $left.prev('a');
                if($new_left.length<1) {
                    $new_left = $parent.find('.zm-gallery-main-img-list-block').last();
                }
                if($new_left.hasClass('right')) {
                    $new_left = $right.clone(true).off();
                    jQuery('.zm-gallery-main-img-list').append( $new_left );
                    $new_left.removeClass('right').removeClass('zm-gallery-main-leftbtn-right');
                    $new_left.attr('data-clone', 1);
                }
                $new_left.addClass('prev-left');
                $new_left.show().addClass('zm-gallery-main-leftbtn-prev-left');
            }
            else {
                jQuery('.zm-gallery-main-l').fadeOut();
                jQuery('.zm-gallery-main-r').fadeIn();
            }
        }

        setTimeout(function () {
            zm_gallery_start = false;
            if($right.length>0) {
                $right.hide().removeClass('right').removeClass('zm-gallery-main-leftbtn-right');
            }
            if($left.length>0) {
                $left.removeClass('left').removeClass('zm-gallery-main-leftbtn-left').addClass('curr');

                if($list.length > 2) {
                    if($new_left.length>0) {
                        if($new_left.attr('data-clone') !== undefined) {
                            $right.css({left:'-100%'}).addClass('left').show().css({left: ''});
                            $new_left.remove();
                        }
                        else {
                            $new_left.removeClass('prev-left').removeClass('zm-gallery-main-leftbtn-prev-left').addClass('left');
                        }
                    }
                }
            }
            $curr.removeClass('curr').removeClass('zm-gallery-main-leftbtn-curr').addClass('right');
        },animation_time);

        if( ww > 1170 ) {
            var max_val = 100;
            var min_val = 0;
            var steps = 30;
            var step = max_val/steps;

            var time = (animation_time/2)/steps;

            if($left.length>0) {
                var left_elem = {
                    'elem': $left.find('img'),
                    'time': time,
                    'val': max_val,
                    'end_val': min_val,
                    'step': step,
                    'type': 0
                };
                ZmAnimate(left_elem);
            }
            var curr_elem = {
                'elem': $curr.find('img'),
                'time': time,
                'val': min_val,
                'end_val': max_val,
                'step': step,
                'type': 1
            };

            ZmAnimate(curr_elem);
        }
    });
    jQuery('.zm-gallery-main-r').click(function () {
        if(zm_gallery_start) {
            return false;
        }
        zm_gallery_start = true;
        var animation_time = 1400;
        var ww = jQuery(window).width();
        var $parent = jQuery(this).parents('.zm-gallery-main').first();
        var $list = $parent.find('.zm-gallery-main-img-list-block');
        var $curr = $parent.find('.zm-gallery-main-img-list-block.curr');
        var $right = $parent.find('.zm-gallery-main-img-list-block.right');
        var $left = $parent.find('.zm-gallery-main-img-list-block.left');


        $curr.addClass('zm-gallery-main-rightbtn-curr');
        if($right.length>0) {
            $right.addClass('zm-gallery-main-rightbtn-right');

            if($list.length > 2) {
                var $new_right = $right.next('a');
                if($new_right.length < 1) {
                    $new_right = $parent.find('.zm-gallery-main-img-list-block').eq(0);
                }
                if($new_right.hasClass('left')) {
                    $new_right = $left.clone(true).off();
                    jQuery('.zm-gallery-main-img-list').append( $new_right );
                    $new_right.removeClass('left').removeClass('zm-gallery-main-rightbtn-left');
                    $new_right.attr('data-clone', 1);
                }
                $new_right.addClass('prev-right');
                $new_right.show().addClass('zm-gallery-main-rightbtn-prev-right');
            }
            else {
                jQuery('.zm-gallery-main-r').fadeOut();
                jQuery('.zm-gallery-main-l').fadeIn();
            }
        }
        if($left.length>0) {
            $left.addClass('zm-gallery-main-rightbtn-left');
        }

        setTimeout(function () {
            zm_gallery_start = false;
            if($left.length>0) {
                $left.hide().removeClass('left').removeClass('zm-gallery-main-rightbtn-left');
            }
            if($right.length>0) {
                $right.removeClass('right').removeClass('zm-gallery-main-rightbtn-right').addClass('curr');

                if($list.length > 2) {
                    if($new_right.length>0) {
                        if($new_right.attr('data-clone') !== undefined) {
                            $left.hide().css({right:'-100%'}).addClass('right').show().css({right: ''});
                            $new_right.remove();
                        }
                        else {
                            $new_right.removeClass('prev-right').removeClass('zm-gallery-main-rightbtn-prev-right').addClass('right');
                        }
                    }
                }
            }
            $curr.removeClass('curr').removeClass('zm-gallery-main-rightbtn-curr').addClass('left');
        },animation_time);

        if( ww > 1170 ) {
            var max_val = 100;
            var min_val = 0;
            var steps = 30;
            var step = max_val/steps;

            var time = animation_time/steps;

            if($right.length>0) {
                var left_elem = {
                    'elem': $right.find('img'),
                    'time': time,
                    'val': max_val,
                    'end_val': min_val,
                    'step': step,
                    'type': 0
                };
                ZmAnimate(left_elem);
            }
            var curr_elem = {
                'elem': $curr.find('img'),
                'time': time,
                'val': min_val,
                'end_val': max_val,
                'step': step,
                'type': 1
            };

            ZmAnimate(curr_elem);
        }
    });
});
jQuery(window).resize(function () {
    if ( jQuery('.zm-gallery-main').length > 0 ) {
        ZmResize();
    }
});

function ZmResize() {
    var ww = jQuery(window).width();
    var $list = jQuery('.zm-gallery-main .zm-gallery-main-img-list-block').not('.curr');
    if( ww > 1170 ) {
        $list.find('img').css({
            '-webkit-filter': 'grayscale(100%)',
            '-moz-filter': 'grayscale(100%)',
            '-o-filter': 'grayscale(100%)',
            'filter': 'grayscale(100%)'
        });
    }
    else {
        $list.find('img').css({
            '-webkit-filter': 'grayscale(0%)',
            '-moz-filter': 'grayscale(0%)',
            '-o-filter': 'grayscale(0%)',
            'filter': 'grayscale(0%)'
        });
    }

    var ratio = zm_gallery_width/jQuery('.zm-gallery-main-img').data('maxheight');
    var curr_width = (ww<zm_gallery_width) ? ww : zm_gallery_width;
    var height = curr_width/ratio;
    jQuery('.zm-gallery-main-img').height(height);
    jQuery('.zm-gallery-main-img-list').height(height);
}

function ZmAnimate(obj) {
    obj['elem'].css({
        '-webkit-filter': 'grayscale(' + obj['val'] + '%)',
        '-moz-filter': 'grayscale(' + obj['val'] + '%)',
        '-o-filter': 'grayscale(' + obj['val'] + '%)',
        'filter': 'grayscale(' + obj['val'] + '%)'
    });

    if( obj['type'] == 0 ) {
        var new_val = obj['val'] - obj['step'];
        obj['val'] = new_val;

        if( new_val > obj['end_val'] ) {
            setTimeout(function() {
                ZmAnimate(obj);
            }, obj['time']);
        }
    }
    else {
        var new_val = obj['val'] + obj['step'];
        obj['val'] = new_val;

        if( new_val < obj['end_val'] ) {
            setTimeout(function() {
                ZmAnimate(obj);
            }, obj['time']);
        }
    }
}
