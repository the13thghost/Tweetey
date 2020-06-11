$(document).ready(function() {

    // add images to container
    $(".add-image").click(function () {
        $("input[id='gallery-photo-add']").click();
    });

    // Show div upon 3 dots svg click
    $(document).on('click', 'svg.dots', function () {
        $edit = $(this).get(0); // get current clicked dom element (with children)
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
        // console.log(this);
        // let me= $('.tablist div.active-class')[0];
        $('div').removeClass('active-link');
        $(this).addClass("active-link");
    });

    // Show popup div for retweeting 
    $(document).on('click', '.retweet-click', function () { 
        $edit = $(this).get(0); 
        $div_modal1 = $($edit).next(); 
        $($div_modal1).stop().fadeIn();

        $(document).mouseup(function (e) {
            if ($(e.target).closest(".modal2").length === 0) {
                $(".modal2").stop().fadeOut();
            }
        });
    });

    // Show popup that tweet was published
    $(document).on('click', '.remove-notify', function () {
        $('.notify-published').css({
            'display': 'none'
        });
    });

    // Show popup to retweet with comment
    $(document).on("click", ".open-popup", function () {
        $(".popup-overlay, .popup-content").addClass("active");
        $('.retweet-comment-form textarea').val('');

        // Disable scroll
        $("body").css({
            "overflow": "hidden"
        });

        $(document).mouseup(function (e) {
            if ($(e.target).closest(".popup-overlay").length === 0) {
                $('.retweet-comment-form').removeAttr('action');
                $("body").css("overflow", "visible");
                $(".publish-errors-comment").html('');
                $('.counter-1 input').val(255);
                $('.before-div').remove();
                $('.images-comment').remove();
                $(".popup-overlay, .popup-content").removeClass("active");
            }
        });

        // Remove popup and show scrollbar
        $(".close").on("click", function () {
            $(".popup-overlay, .popup-content").removeClass("active");
            $(".publish-errors-comment").html('');
            $('.counter-1 input').val(255);
            $('.images-comment').remove();
            $("body").css("overflow", "visible");
        });
    });

    // Show popup to reply
    $(document).on("click", ".open-comment", function () {
        $(".comment-overlay, .comment-content").addClass("active");
        $(".comment-form textarea").val("");
        $("body").css({
            "overflow": "hidden"
        });

        $(document).mouseup(function (e) {
            if ($(e.target).closest(".comment-overlay").length === 0) {
                $('.comment-form').removeAttr('action');
                $(".publish-errors-reply").html('');
                $("body").css("overflow", "visible");
                $('.counter-3 input').val(255);
                $('.before1-div').remove();
                $('.images-reply').remove();
                $('.original-tweet').remove();
                $(".comment-overlay, .comment-content").removeClass("active");
            }
        });
 
        $(".close").on("click", function () {
            $(".comment-overlay, .comment-content").removeClass("active");
            $('.counter-3 input').val(255);
            $(".publish-errors-reply").html('');
            $('.original-tweet').remove();
            $('.images-reply').remove();
            $("body").css("overflow", "visible");
        });
    });

    // Show popup to edit bio
    $(document).on("click", ".open-bio", function () {
        $(".bio-overlay, .bio-content").addClass("active");
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
                $(".bio-overlay, .bio-content").removeClass("active");
            }
        });

        $(".close").on("click", function () {
            $(".bio-overlay, .bio-content").removeClass("active");
            $('.counter-2 input').val(140);
            $("body").css("overflow", "visible");
        });
    });

    // Remove images from gallery after consequative click
    $(document).on('click', '.add-image', function () {
        $('.publish-errors').html('');
        $('.gallery').children().remove();
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