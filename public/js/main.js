$(document).ready(function() {

    // Add images to container
    $(".add-image").click(function () {
        $("input[id='gallery-photo-add']").click();
    });

    // Show div upon 3 dots svg click
    $(document).on('click', 'svg.dots', function (e) {
        e.stopPropagation();
        $edit = $(this).get(0); // Get current clicked dom element (with children)
        $div_modal1 = $($edit).next(); 
        $($div_modal1).stop().fadeToggle();
    });

    $(document).mouseup(function (e) {
        if ($(e.target).closest(".modal1").length === 0) {
            $(".modal1").stop().fadeOut();
        }
    });

    // Active class on nav links
    $(document).on('click', '.tablist div', function() {
        $('div').removeClass('active-link');
        $(this).addClass("active-link");
    });

    // Show popup div to retweet/unretweet or retweet with comment
    $(document).on('click', '.retweet-click', function (e) { 
        e.stopPropagation();
        $edit = $(this).get(0); 
        $div_modal1 = $($edit).next(); 
        $($div_modal1).stop().fadeIn();

        $(document).mouseup(function (e) {
            if ($(e.target).closest(".modal2").length === 0) {
                $(".modal2").stop().fadeOut();
            }
        });
    });

    // Remove notification of successful tweet
    $(document).on('click', '.remove-notify', function () {
        $('.notify-published').css({
            'display': 'none'
        });
    });

    // Show popup to retweet with comment
    $(document).on("click", ".open-popup", function (e) {
        e.stopPropagation();
        $('.retweet-comment-form textarea').val('');

        $(document).mouseup(function (e) {
            if ($(e.target).closest(".popup-overlay").length === 0) {
                $('.retweet-comment-form').removeAttr('action');
                $("body").css("overflow", "visible");
                $(".publish-errors-comment").html('');
                $('.counter-1 input').val(255);
                $('.before-div').remove();
                $('.images-comment').remove();
                $(".popup-overlay, .popup-content, .overlay-shade").removeClass("active");
                $(document).unbind('mouseup');
 
            }
        });

        $(".close").on("click", function () {
            $(".popup-overlay, .popup-content, .overlay-shade").removeClass("active");
            $(".publish-errors-comment").html('');
            $('.counter-1 input').val(255);
            $('.images-comment').remove();
            $("body").css("overflow", "visible");
        });
    });

    // Show popup to reply
    $(document).on("click", ".open-comment", function (e) {
        e.stopPropagation();
        $(".comment-form textarea").val("");
        

        $(document).mouseup(function (e) {
            if ($(e.target).closest(".comment-overlay").length === 0) {
                $('.comment-form').removeAttr('action');
                $(".publish-errors-reply").html('');
                $("body").css("overflow", "visible");
                $('.counter-3 input').val(255);
                $('.before1-div').remove();
                $('.images-reply').remove();
                $('.original-tweet').remove();
                $('.enter-h').removeAttr('style');
                $(".comment-overlay, .comment-content, .overlay-shade").removeClass("active");
                $(document).unbind('mouseup');
            }
        });
 
        $(".close").on("click", function () {
            $(".comment-overlay, .comment-content, .overlay-shade").removeClass("active");
            $('.counter-3 input').val(255);
            $(".publish-errors-reply").html('');
            $('.original-tweet').remove();
            $('.images-reply').remove();
            $('.enter-h').removeAttr('style');
            $("body").css("overflow", "visible");
        });
    });

    // Show popup to edit bio
    $(document).on("click", ".open-bio", function () {
        $(".bio-overlay, .bio-content, .overlay-shade").addClass("active");
        $(".bio-form textarea").val("");
        $("body").css({
            "overflow": "hidden"
        });

        $(document).mouseup(function (e) {
            if ($(e.target).closest(".bio-overlay").length === 0) {
                $('.retweet-comment-form').removeAttr('action');
                $("body").css("overflow", "visible");
                $('.counter-2 input').val(140);
                $('.before-div').remove();
                $(".bio-overlay, .bio-content, .overlay-shade").removeClass("active");
                $(document).unbind('mouseup');

            }
        });

        $(".close").on("click", function () {
            $(".bio-overlay, .bio-content, .overlay-shade").removeClass("active");
            $('.counter-2 input').val(140);
            $("body").css("overflow", "visible");
        });
    });

    // Prevent redirecting to thread
    $(document).on('click', 'div.submit-like, div.submit-dislike, li.retweet, li.unretweet, li.delete-post, li.pin-post', function(e) {
        e.stopPropagation();
    });

    // Remove images from gallery after consequative click
    $(document).on('click', '.add-image', function () {
        $('.publish-errors').html('');
        $('.gallery').children().remove();
    });

    // Navigation to threads
    $(document).on('click', '.thread', function() {
        let me = $(this).find('.open-comment').data('id');
        window.location.href = `/tweet/${me}`;
    });

    // Navigation to sub threads
    $(document).on('click', '.sub-thread', function() {
        let me = $(this).find('.open-comment').data('id');
        window.location.href = `/tweet/${me}`;
    });


});

// Counter for publishing tweets/comments/replies
function textCounter(field, countfield, maxlimit) {
    if (field.value.length > maxlimit) {
        field.value = field.value.substring(0, maxlimit);
        field.blur();
        field.focus();
        return false;
    } else {
        countfield.value = maxlimit - field.value.length;
    }
}

// Multiple images preview
$(function () {
    var imagesPreview = function (input, placeToInsertImagePreview) {
        if (input.files) {
            
            var filesAmount = input.files.length;
            for (i = 0; i < filesAmount; i++) {
                var reader = new FileReader();

                reader.onload = function (event) {
                    $($.parseHTML('<img>')).attr('src', event.target.result).attr({
                        'class': 'w-2/5 object-cover m-2 border-white rounded-lg border-8',
                        'style': 'width:80px;height:80px'
                    }).appendTo(placeToInsertImagePreview);
                }
                reader.readAsDataURL(input.files[i]);
            }
        }
    };

    $(document).on('change', '#gallery-photo-add', function () {
        if(this.files.length > 5) {
            $('.publish-errors').html('Max 4 files accepted!');
            $('#gallery-photo-add').val(''); // empty out the input files
        } else {
            imagesPreview(this, 'div.gallery');
        }
    });
});