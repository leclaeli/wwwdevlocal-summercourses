jQuery(document).ready(function($) {
  var GreetingAll = $("#GreetingAll").val();

  $("#PleasePushMe").click(function(){ 
    $.ajax(myAjax.ajaxurl,{
      type: "post",
      data: {
        action: 'MyAjaxFunction',
        GreetingAll: GreetingAll,
      },
      success: function(data, textStatus, XMLHttpRequest){
        $("#test-div1").html('');
        $("#test-div1").append(data);
      },
      error: function(MLHttpRequest, textStatus, errorThrown){
        alert(errorThrown);
      }
    });
  });
  $('#PaginationExample a').live('click', function(e){
    e.preventDefault();
    var link = $(this).attr('href');
    var page = link.charAt(link.length-2);
    console.log(page);
    //jQuery('#tabs').html('Loading...');
    //jQuery('#speakers').load(link+' #speakers');
    var placeHolder = $('#speaker-container-2').load(link+' #speaker-container');
    $('#PaginationExample a').load(link+' #PaginationExample a')
    $('#speaker-container-2').append(placeHolder);
    // jQuery.ajax(myAjax.ajaxurl,{
    //   type: "post",
    //   data: {
    //     link: link,
    //   },
    //   success: function(data, textStatus, XMLHttpRequest){
    //     jQuery("#test-div1").html('');
    //     jQuery("#test-div1").append(data);
    //   },
    //   error: function(MLHttpRequest, textStatus, errorThrown){
    //     alert(errorThrown);
    //   }
    // });
  });
});