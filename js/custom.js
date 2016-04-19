// jQuery(document).ready(function($) {
(function($) {
    $(function() {
        var $h = $('#slideout-hover'),
            hoverWidth = $h.width();
            $h.hide();

        var cache = {}; //our cache object
        // $('#courses li').hover(function(event) {
            $( '#courses-container' ).on( 'mouseenter', '#courses li', function() {
                $('.hover-details-content').html('<p>loading...</p>');
            /* Stuff to do when the mouse enters the element */

            var $tgt = $(this),
                $root = $(':root'),
                tgtPos = $tgt.offset(), // top left corner of tgt position (need to get to top right)
                rootPos = $root.offset(),
                tgtRight = tgtPos.left + $tgt.width(), // puts us to the top right
                rootRight = rootPos.left + $root.width(), // top right corner of the window
                bodyWidth = $('body').width(),
                left = tgtRight + hoverWidth <= rootRight ? tgtRight : '', // if it fits on the right use tgtRight else left is 0
                right = left ? '' : bodyWidth - tgtPos.left; // if right = left do nothing, else get right position
                if ( $(window).width() < 515 ) {
                    var css = { 
                        'left' : tgtRight - 222,
                        'width' : 222,
                        'height': 0,
                        'top' : tgtPos.top + 222
                        }
                } else {
                    var css = { 
                        'left' : left,
                        'right' : right,
                        'width' : 0,
                        'top' : tgtPos.top,
                        'height' : 222
                    }
                }

            // Ajax call 
            var theId = $(this).attr("id");
            var handle = $(this).find('#course-title').html(); //davidwalshblog, for example
            var cacheHandle = handle.toLowerCase();
            if(cache[cacheHandle] != undefined) {
                $(".hover-details-content").html(cache[cacheHandle]);
            }
            else {
                $.ajax(myAjax.ajaxurl,{
                    type: "post",
                    data: {
                        action: 'MyAjaxFunction',
                        id: theId,
                    },
                    success: function(data, textStatus, XMLHttpRequest){
                        $(".hover-details-content").html('');
                        $(".hover-details-content").append(data);
                        cache[cacheHandle] = (data);
                    },
                    error: function(MLHttpRequest, textStatus, errorThrown){
                        console.log(errorThrown);
                    }
                });
                // End Ajax
            }

            if ( windowSize() ) {
                $('#slideout-hover').stop().show().css(css).animate({ height: "100%" }, 225);
            } else {
                $('#slideout-hover').stop().show().css(css).animate({ width: hoverWidth }, 225);
            }
            $($tgt).find('#center-link').stop().fadeIn(225);
            
        });
        $( '#courses-container' ).on( 'mouseleave', '#courses li', function() {
            var $tgt = $(this);
            /* Stuff to do when the mouse leaves the element */
            if ( windowSize() ) {
                $('#slideout-hover').stop().animate({height: 0}, 225, function(){ $('#slideout-hover').hide(); });
            } else {
                $('#slideout-hover').stop().animate({width: 0}, 225, function(){ $('#slideout-hover').hide(); });
            }
            $($tgt).find('#center-link').stop().fadeOut(225);
        });

        $(window).resize(function() {
            windowSize();
        });

        function windowSize() {
            if ( $(window).width() < 644 ) {
                return true;
            } else {
                return false; 
            }
        }

        /*
        ** Slider 
        */

        var sliderIndex;
        var slides = ('#featured-slider > ul > li');
        var numSlides = $(slides).length;
        $(slides).eq(0).addClass('opacity-one');
        $i = 0;

        //var slides = $(slides);

        function rotate(newIndex) {
            var currentIndex = $('.opacity-one').index();
            $(slides).eq(currentIndex).removeClass('opacity-one');
            if ( newIndex === undefined ) {
                currentIndex++
                if ( currentIndex > numSlides - 1 ) {
                    currentIndex = 0;
                }
            } else {
                currentIndex += newIndex;
                if ( currentIndex > numSlides - 1 ) {
                    currentIndex = 0;
                }
            }
            $(slides).eq(currentIndex).addClass('opacity-one');
            adjustHeight(750);
        }

        $('#featured-slider').hover(function() {
            clearInterval(timer);
        }, function() {
            rotateTimer();
        });
        
        function rotateTimer() {
            timer = setInterval( function() {
                rotate();
                }, 
                6000  
            );
        }

        function adjustHeight(newSpeed) {
            if ( windowSize() ) {
                var newHeight = $('.opacity-one #slider-background').height() + $('.opacity-one #slider-content').height();
                $('#top-container').animate({
                    height: newHeight
                }, newSpeed);
            }
        }

        $('#previous').click(function(event) {
            /* Act on the event */
            rotate(-1);
        });

        $('#next').click(function(event) {
            /* Act on the event */
            rotate(1);
        });

        rotateTimer();
        adjustHeight(0);
        windowSize();

        $( '.filters-dropdown' ).click(function(event) {
            $( '.row.hidden' ).slideToggle( 'fast' );
            $( '.filters-dropdown' ).toggleClass( 'open' );
            // if ( $( '.row.hidden' ).is(":visible") ) {
            //     $( '.filters-dropdown' ).addClass( 'open' );
            // } else {
            //     $( '.filters-dropdown' ).removeClass( 'open' );
            // }

        });

        $( '#courses-facets .filter-container h4' ).click(function(event) {
            $(this).next( '#courses-facets .filter-container .facetwp-facet' ).slideToggle('fast');
        });


        window.fwp_is_paging = false;

        $(document).on('facetwp-refresh', function() { // gets triggered before FacetWP begins the refresh process
            if (! window.fwp_is_paging) {
                window.fwp_page = 1;
                FWP.extras.per_page = 'default';
            }
            window.fwp_is_paging = false;
        });

        $(document).on('facetwp-loaded', function() { // gets triggered when FacetWP finishes refreshing
            window.fwp_total_rows = FWP.settings.pager.total_rows;
            triggerOnce = 0;
            var rows_loaded = (window.fwp_page * window.fwp_default_per_page);
            if (rows_loaded >= window.fwp_total_rows) {
                $( '.done-loading' ).show();
            }

            $( '.loading' ).hide();
            
            var str = FWP.build_query_string();
            if ( str ) {
                $( '.reset-facets' ).show();
            } else {
                $( '.reset-facets' ).hide();
            }

            if (! FWP.loaded) {
                window.fwp_default_per_page = FWP.settings.pager.per_page;
                
                $(window).on( "scroll", function() {
                    var windowSTT = $(window).scrollTop();
                    var windowHeight = $(window).height();
                    var el = $( '.home #tertiary' )
                    var oT = $( el ).offset();
                    elOffsetTop = oT.top - windowHeight;
                    if ( windowSTT > elOffsetTop && triggerOnce == 0 ) {
                    // if ($(window).scrollTop() == $(document).height() - ( $(window).height() ) ) {
                        var rows_loaded = (window.fwp_page * window.fwp_default_per_page);
                        if (rows_loaded < window.fwp_total_rows) {
                            $( '.loading' ).show();
                            window.fwp_page++;
                            window.fwp_is_paging = true;
                            FWP.extras.per_page = (window.fwp_page * window.fwp_default_per_page);
                            FWP.soft_refresh = true;
                            FWP.refresh();
                            triggerOnce = 1;
                        }
                    }
                });
            }
        });

        $( '.click-reset-facets' ).click(function(event) {
            FWP.reset();
            $( '.done-loading' ).hide();
        });

        $( '.close' ).click(function( event ) {
            event.preventDefault();
            $( '.class-full' ).fadeOut('400');
        });
    })
})(jQuery);
