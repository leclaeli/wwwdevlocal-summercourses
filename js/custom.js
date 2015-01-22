jQuery(document).ready(function($) {
    
    var $h = $('#slideout-hover'),
        hoverWidth = $h.width();
        $h.hide();
    var cache = {}; //our cache object
    $('#courses li').hover(function(event) {
        /* Stuff to do when the mouse enters the element */
        var $tgt = $(this),
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
        var handle = $(this).find('span').html(); //davidwalshblog, for example
        var cacheHandle = handle.toLowerCase();
        if(cache[cacheHandle] != undefined) {
            $(".hoverDetailsContent").html(cache[cacheHandle]);
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
                    $(".hoverDetailsContent").html('');
                    $(".hoverDetailsContent").append(data);
                    cache[cacheHandle] = (data);
                    console.log(cache);
                },
                error: function(MLHttpRequest, textStatus, errorThrown){
                    alert(errorThrown);
                }
            });
            // End Ajax
        }


    $('#slideout-hover').stop().show().css(css).animate({width: hoverWidth}, 225);
        
    }, function() {
        /* Stuff to do when the mouse leaves the element */
        $('#slideout-hover').stop().animate({width: 0}, 225, function(){ $('#slideout-hover').hide(); });
    });


    /*
    ** Slider 
    */

    var sliderIndex;
    $( '#featured-slider ul li' ).each( function( index, el ) {
        console.log( index );
        //$(el).addClass('opacity-zero');
    });



        $('#featured-slider ul li').eq(0).addClass('opacity-one');


    $i = 0;

    var slides = $('#featured-slider ul li');

    function rotate() {
        var currentIndex = $('.opacity-one').index();
        $('#featured-slider ul li').eq(currentIndex).removeClass('opacity-one');
        currentIndex++
        if ( currentIndex > 4 ) {
            currentIndex = 0;
        }
        $('#featured-slider ul li').eq(currentIndex).addClass('opacity-one');
    }

    $('#featured-slider ul li').hover(function() {
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

    rotateTimer();
                
});