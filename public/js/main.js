function textCounter( field, countfield, maxlimit ) {
    if ( field.value.length > maxlimit ) {
     field.value = field.value.substring( 0, maxlimit );
     field.blur();
     field.focus();
     return false;
    } else {
     countfield.value = maxlimit - field.value.length;
    }
   }

   $(document).ready(function() {
    $(".add-image").click(function() {
        $("input[id='gallery-photo-add']").click();
    });
   });
   
   $(function() {
    // Multiple images preview in browser
    var imagesPreview = function(input, placeToInsertImagePreview) {

        if (input.files) {
            var filesAmount = input.files.length;

            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function(event) {
                    $($.parseHTML('<img>')).attr('src', event.target.result).attr({'class':'w-2/5 object-cover m-2 border-white rounded-lg border-8', 'style':'width:80px;height:80px'}).appendTo(placeToInsertImagePreview);
                }

                reader.readAsDataURL(input.files[i]);
            }
        }

    };

    $('#gallery-photo-add').on('change', function() {
        imagesPreview(this, 'div.gallery');
    });
});

//show 3 dots div on tweet

$(document).ready(function() {
    $(document).on('click', 'svg.dots',function() {
        $edit = $(this).get(0); // get current clicked dom element (with children)
        $div_modal1 = $($edit).next(); // get next sibling
        $($div_modal1).stop().fadeToggle();
    });

    $(document).mouseup(function (e) { 
        if ($(e.target).closest(".modal1").length === 0) { 
            $(".modal1").stop().fadeOut(); 
        } 
    }); 

});


//show popup div for retweeting 

$(document).ready(function() {
    $(document).on('click', '.retweet-click',function() {// when i click on svg icon for retweet
        $edit = $(this).get(0); // get current clicked dom element (with children)
        $div_modal1 = $($edit).next(); // get next sibling
        $($div_modal1).stop().fadeIn();
    });

    $(document).mouseup(function (e) { 
        if ($(e.target).closest(".modal2").length === 0) { 
            $(".modal2").stop().fadeOut(); 
        } 
    }); 
});

//popup that tweet was published
$(document).on('click', '.remove-notify', function() {
    $('.notify-published').css({'display':'none'});
});


//on status textarea click show the save button, on click elsewhere hide it
$(document).on('click', '.expandable', function() {
    $('.save-status').css({'display':'flex'});
});

// to retweet with comment show popup

$(document).on("click", ".open-popup", function() {
    $(".popup-overlay, .popup-content").addClass("active");
    $('.retweet-comment-form textarea').val('');

    //disable scroll when popup shows
    $("body").css({"overflow":"hidden"});

    $(document).mouseup(function (e) { 
        if ($(e.target).closest(".popup-overlay").length === 0) { 
            $('.retweet-comment-form').removeAttr('action');
            $("body").css("overflow","visible");
            $('.before-div').remove();
            $(".popup-overlay, .popup-content").removeClass("active");
            //when you click add comment the previous post gets added to the dom
            //and if you click outside and again inside at adds another div wich means a duplicate and so on
            //so when you click out of the div it should remove the div
            //also after you save the new record in db, it should remove the same div because we are not refreshing 
            //the page because of ajax
        } 
    });
  });

  //removes the "active" class to .popup and .popup-content when the "Close" button is clicked 
  $(".close").on("click", function() {
    $(".popup-overlay, .popup-content").removeClass("active");
    $("body").css("overflow","visible");
  });

  //show popup when click on comment icon - COMMENT
  $(document).on("click", ".open-comment", function() {
    $(".comment-overlay, .comment-content").addClass("active");
    $(".comment-form textarea").val("");

    //disable scroll when popup shows
    $("body").css({"overflow":"hidden"});

    $(document).mouseup(function (e) { 
        if ($(e.target).closest(".comment-overlay").length === 0) { 
            $('.comment-form').removeAttr('action');
            $("body").css("overflow","visible");
            $('.before1-div').remove();
            $('.original-tweet').remove();
            $(".comment-overlay, .comment-content").removeClass("active");
            //when you click add comment the previous post gets added to the dom
            //and if you click outside and again inside at adds another div wich means a duplicate and so on
            //so when you click out of the div it should remove the div
            //also after you save the new record in db, it should remove the same div because we are not refreshing 
            //the page because of ajax      
    
        } 
    });
  });

  //removes the "active" class to comment .popup and .popup-content when the "Close" button is clicked 
  $(".close").on("click", function() {
    $(".comment-overlay, .comment-content").removeClass("active");
    $('.original-tweet').remove();
    $("body").css("overflow","visible");
  });
  //end
  
  


  //show popup to edit bio - BIO

  $(document).on("click", ".open-bio", function() {
    $(".bio-overlay, .bio-content").addClass("active");
    $(".bio-form textarea").val("");
      

    //disable scroll when popup shows
    $("body").css({"overflow":"hidden"});

    $(document).mouseup(function (e) { 
        if ($(e.target).closest(".bio-overlay").length === 0) { 
            $('.retweet-comment-form').removeAttr('action');
            $("body").css("overflow","visible");
            $('.before-div').remove();
            $(".bio-overlay, .bio-content").removeClass("active");        
        } 
    });
  });
  
  //removes the "active" class to .popup and .popup-content when the "Close" button is clicked 
  $(".close").on("click", function() {
    $(".bio-overlay, .bio-content").removeClass("active");
    $("body").css("overflow","visible");
  });

  //remove images from gallery after consequative click

  $(document).on('click', '.add-image', function() {
      $('.gallery').children().remove();
  });
