jQuery(document).ready(function($) {
        
    var $h = $('#slideout-hover'),
        hoverWidth = $h.width();
        $h.hide();

    var cache = {}; //our cache object
    $('#courses li').hover(function(event) {
        /* Stuff to do when the mouse enters the element */
        var $tgt = $(this),
            // thisWidth = $tgt.width();
            // if ( thisWidth > 300 ) { 
            //     hoverWidth = 316; 
            // } else {
            //     hoverWidth = 235;
            // }
            $root = $(':root'),
            tgtPos = $tgt.offset(), // top left corner of tgt position (need to get to top right)
            rootPos = $root.offset(),
            tgtRight = tgtPos.left + $tgt.width(), // puts us to the top right
            rootRight = rootPos.left + $root.width(), // top right corner of the window
            bodyWidth = $('body').width(),
            left = tgtRight + hoverWidth <= rootRight ? tgtRight : '', // if it fits on the right use tgtRight else left is 0
            right = left ? '' : bodyWidth - tgtPos.left, // if right = left do nothing else get right position
            css = { 
                'left' : left,
                'right' : right,
                'width' : 0,
                'top' : tgtPos.top
            };
        //console.log(rootRight);

        // Ajax call 
        var theId = $(this).attr("id");
        var handle = $(this).find('#course-title').html(); //davidwalshblog, for example
        var cacheHandle = handle.toLowerCase();
        if(cache[cacheHandle] != undefined) {
            $(".hover-details-content").html(cache[cacheHandle]);
            //console.log(cache);
        }
        else {
            //console.log('undefined - run ajax');
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
                    console.log(cache);
                },
                error: function(MLHttpRequest, textStatus, errorThrown){
                    alert(errorThrown);
                }
            });
            // End Ajax
        }


    $('#slideout-hover').stop().show().css(css).animate({ width: hoverWidth }, 225);
    $($tgt).find('#center-link').stop().fadeIn(225);
    //$($tgt).find('> a').stop().animate({opacity: .25}, 225);
        
    }, function() {
        var $tgt = $(this);
        /* Stuff to do when the mouse leaves the element */
        $('#slideout-hover').stop().animate({width: 0}, 225, function(){ $('#slideout-hover').hide(); });
        $($tgt).find('#center-link').stop().fadeOut(225);
        //$($tgt).find('> a').stop().animate({opacity: 1}, 225);
    });


    /*
    ** Slider 
    */

    var sliderIndex;
    var slides = ('#featured-slider > ul > li')

    $(slides).eq(0).addClass('opacity-one');
    $i = 0;

    var slides = $(slides);

    function rotate(newIndex) {
        var currentIndex = $('.opacity-one').index();
        $(slides).eq(currentIndex).removeClass('opacity-one');
        if ( newIndex === undefined ) {
            currentIndex++
            if ( currentIndex > 3 ) {
                currentIndex = 0;
            }
        } else {
            currentIndex += newIndex;
            if ( currentIndex > 3 ) {
                currentIndex = 0;
            }
        }
        console.log(currentIndex);
        $(slides).eq(currentIndex).addClass('opacity-one');
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
            5000  
        );
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

    /* Set min height on .sub-featured-course h4 */

    // elHeights = [];
    // $('.sub-featured-course h4').each(function(index, el) {
    //     elHeights[index] = $(el).height();
    //     tallest = Math.max.apply(Math, elHeights);
    // });
    // $('.sub-featured-course h4').height(tallest);

});