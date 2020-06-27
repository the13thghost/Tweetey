// FOLLOW / UNFOLLOW BTN
$('#toggleFollow').on('submit', function (event) {
    event.preventDefault();
    let post_url = $(this).attr("action");
    let request_method = $(this).attr("method");
    let form_data = $(this).serialize();

    $.ajax({
        url: post_url,
        type: request_method,
        data: form_data
    }).done(function () {
        let url = window.location.pathname;

        $('.follow-text').fadeOut(0, function () {
            $('.follow-text').remove();
        });

        $(".follow-btn").load(`${url}  .follow-text`, function () {
            $(this).css('opacity', 0).stop().animate({
                'opacity': 1
            });
        });

        // Reload the friends sidebar
        $(".following").load(`${url}  .following-users`, function () {
            $(this).css('opacity', 0).stop().animate({
                'opacity': 1
            });
        });
    });
});

// Change currently clicked div color
function changeCurrentLikeColor(el) {
    if ($(el).hasClass("text-blue-400")) {
        $(el).removeClass('text-blue-400').addClass("text-gray-500");
    } else {
        $(el).removeClass('text-gray-500').addClass("text-blue-400");
    }
}

// Change opposite btn color
function changeLikeColor(div, id) {
    if ($(div).hasClass("text-blue-400")) {
        $(div).removeClass('text-blue-400').addClass("text-gray-500");
    } else if ($(div).hasClass("text-gray-500") && $(".load-ajax-dis-" + id).is(':empty')) {
        $(div).removeClass('text-gray-500').addClass("text-gray-500");
    }
}

// THUMBS UP
$(document).ready(function () {
    $(document).on('click', 'div.submit-like', function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let submit_like = $(this).data('id-like');
        let token = $('meta[name="csrf-token"]').attr('content');
        let all = $(`[data-id-like=${submit_like}]`); // For replies mostly > multiple same tweets > update all at the same time
        let url = window.location.pathname;

        $.ajax({
            url: `/tweets/${submit_like}/like`,
            type: 'POST',
            data: {
                'id': submit_like,
                '__token': token
            }
        }).done(function () {
            changeCurrentLikeColor(all);
            $.each(all, function () {
                let dislike_section = $(this).parent().next().children(":first");
                changeLikeColor(dislike_section, submit_like)
            });

            $(".load-here-" + submit_like).load(`${url}  .load-ajax-${submit_like}`, function () {
                let el = $(this).children().length;
                for (i = 0; i < el; i++) {
                    $(`.load-ajax-${submit_like}:nth-child(${i})`).remove();
                }
            });

            if (url.includes('/tweet/')) {
                $(".th-in").load(`${url}  .th-in-load`);
            }

            $(".load-here-dis-" + submit_like).load(`${url}  .load-ajax-dis-${submit_like}`, function () {
                let el = $(this).children().length;
                for (i = 0; i < el; i++) {
                    $(`.load-ajax-dis-${submit_like}:nth-child(${i})`).remove();
                }
            });
        });
    });
});


//THUMBS DOWN
$(document).ready(function () {
    $(document).on('click', 'div.submit-dislike', function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let submit_id = $(this).data('id-dislike');
        let token = $('meta[name="csrf-token"]').attr('content');
        let el = this;
        let all = $(`[data-id-dislike=${submit_id}]`);
        let url = window.location.pathname;

        $.ajax({
            url: `/tweets/${submit_id}/dislike`,
            type: 'POST',
            data: {
                'id': submit_id,
                '__token': token
            }
        }).done(function () {
            changeCurrentLikeColor(all);
            // Update like color
            $.each(all, function () {
                let like_section = $(this).parent().prev().children(":first");
                changeLikeColor(like_section, submit_id)
            });

            if (url.includes('/tweet/')) {
                $(".th-in").load(`${url}  .th-in-load`);
            }

            $(".load-here-dis-" + submit_id).load(`${url}  .load-ajax-dis-${submit_id}`, function () {
                let el = $(this).children().length;
                for (i = 0; i < el; i++) {
                    $(`.load-ajax-dis-${submit_id}:nth-child(${i})`).remove();
                }

                if (url.includes('/likes')) {
                    $(".load-tweets").load(`${url} .load-tweets-ajax`);
                }
            });

            $(".load-here-" + submit_id).load(`${url}  .load-ajax-${submit_id}`, function () {
                let el = $(this).children().length;
                for (i = 0; i < el; i++) {
                    $(`.load-ajax-${submit_id}:nth-child(${i})`).remove();
                }
            });
        });
    });
});

//DELETE TWEET
$(document).ready(function () {
    $(document).on('click', 'li.delete-post', function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let delete_id = $(this).data('id');
        let token = $('meta[name="csrf-token"]').attr('content');
        let el = this;
        let url = window.location.pathname;
        let whole = $(el).closest('.whole');
        let wholeNext = $(whole).next();

        $.ajax({
            url: `/tweets/${delete_id}/delete`,
            type: 'DELETE',
            data: {
                'id': delete_id,
                '__token': token
            },
        }).done(function () {
            $(whole).remove();
            $(wholeNext).remove();
            $(".load-tweets").load(`${url}  .load-tweets-ajax`);
        });
    });
});

// PUBLISH WITH PHOTOS
$('#publish').on('submit', function (event) {
    event.preventDefault();
    let form = $(this)[0];
    let form_data = new FormData(form);
    let post_url = $(this).attr("action");
    let request_method = $(this).attr("method");
    let url = window.location.pathname;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: post_url,
        type: request_method,
        enctype: 'multipart/form-data',
        data: form_data,
        processData: false,
        contentType: false,
        cache: false
    }).done(function () {

        $(".textarea-1").load(`${url}  .textarea-fresh`, function () {
            $('.publish-errors').html('');
            $('.gallery').children().remove();
            $(".counter-1").load(`${url}  .counter-fresh`);
            $(".tweets-1").load(`${url}  .tweets-fresh`);
            $('#gallery-photo-add').val(''); // Empty out the input
        });

        $(".notify-published").fadeIn(function () {
            $(this).delay(6000).fadeOut(1000);
        });

    }).fail(function (xhr) {
        $.each(xhr.responseJSON.errors, function (index, val) {
            // console.log(xhr.responseJSON.errors);
            $('[name=' + index + ']');

            if (xhr.responseJSON.errors['image']) { // string: Max 4 files accepted!
                $('.gallery').children().remove();
            }

            $.each(val, function (i, error) {
                $('.publish-errors').html(error);
            });
        });
    });
});

// RETWEET
$(document).ready(function () {
    $(document).on('click', 'li.retweet', function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let retweet_id = $(this).data('id');
        let token = $('meta[name="csrf-token"]').attr('content');
        let el = this;
        let url = window.location.pathname;

        $.ajax({
            url: `/retweets/${retweet_id}`,
            type: 'POST',
            data: {
                'id': retweet_id,
                '__token': token
            }
        }).done(function () {
            let me = $(el).closest('.modal2');
            $(me[0]).remove();

            // Thread page, refresh likes, dislikes ...
            if (url.includes('/tweet/')) {
                $(".fi-so").load(`${url}  .fi-so-load`);
                $(".th-in").load(`${url}  .th-in-load`);
            }

            $(".load-tweets").load(`${url}  .load-tweets-ajax`);
        });
    });
});

//RETWEET WITH COMMENT - Send the id of the tweet > return the tweet > show body of tweet in popup
$(document).on('click', '.retweet-comment', function (event) {
    event.preventDefault();
    let token = $('meta[name="csrf-token"]').attr('content');
    let id = $(this).data('id');

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: `/retweets/${id}/getTweet`,
        type: 'POST',
        data: {
            'id': id,
            '__token': token
        }
    }).done(function (response) {
        let array = response['body'];
        let splittedArr = array.split("");
        splittedArr.splice(100);
        splittedArr.push('...');
        splittedArr.join("");

        function imgEmpty(param) {
            if (response['images'] != 0) {
                $(`<div class="images-comment"></div>`).insertAfter(param);
                $('.images-comment').html(response['images']);
            }
        }

        $div = `<div class="original-tweet-comment before-div border border-gray-400 rounded-xlt p-3 mt-2 mb-3 mr-3"><div class="flex items-center"><img class="rounded-full object-cover mr-2" style="width:20px;height:20px" src="${response['avatar']}"><div class="mr-2 font-bold">${response['name']}</div><div class="text-gray-600 mr-1">${response['username']}</div><div class="text-gray-600">&middot; ${response['datetime']}</div></div><div class="word-break text-left res-ori-body-comment">${response['body']}</div></div>`;
        $('textarea.mention').after($div);
        imgEmpty('.res-ori-body-comment');
        $('.retweet-comment-form').attr('action', `/retweets/${response['id']}/comment`);
        $(".popup-overlay, .popup-content, .overlay-shade").addClass("active");
        $("body").css({
            "overflow": "hidden"
        });
    });
});

// RETWEET WITH COMMENT - Save record in DB
$(document).on('submit', '.retweet-comment-form', function (event) {
    // Action attr was added via jquery in main.js
    event.preventDefault();
    let post_url = $(this).attr("action");
    let request_method = $(this).attr("method");
    let el = this;
    let url = window.location.pathname;
    let form_data = $(this).serialize();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: post_url,
        type: request_method,
        data: form_data
    }).done(function () {
        let me = $(el).closest('.modal2');
        $(me[0]).remove();
        $(".popup-overlay, .popup-content").removeClass("active");
        $('.retweet-comment-form').removeAttr('action');
        $(".publish-errors-comment").html('');

        // Thread page, refresh likes, dislikes ...
        if (url.includes('/tweet/')) {
            $(".fi-so").load(`${url}  .fi-so-load`);
            $(".th-in").load(`${url}  .th-in-load`);
        }

        $(".load-tweets").load(`${url}  .load-tweets-ajax`);
        $("body").css("overflow", "visible");
        $(".overlay-shade").removeClass("active");

    }).fail(function (xhr) {
        $.each(xhr.responseJSON.errors, function (index, val) {
            $('[name=' + index + ']');
            $.each(val, function (i, error) {
                $('.publish-errors-comment').html(error);
            });
        });
    });
});

// SHOW "REPLYING TO" POPUP - Add original tweet to popup
$(document).on('click', '.comment', function (event) {
    event.preventDefault();
    let token = $('meta[name="csrf-token"]').attr('content');
    let id = $(this).data('id');
    let closestHeight = $(this).closest('.calc-h').innerHeight() - 49;

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: `/retweets/${id}/getTweet`,
        type: 'POST',
        data: {
            'id': id,
            '__token': token
        }
    }).done(function (response) {

        function popupContent(avatar, name, username, datetime, body) {
            $('.original-poster').attr('src', `${response[avatar]}`);
            $('.response-name').text(`${response[name]}`);
            $('.response-username').text(`@${response[username]}`);
            $('.datetime').text(`${response[datetime]}`);
            $('.response-body').text(`${response[body]}`);
        }

        function imgEmpty(param) {
            let height;
            if (response['images'] != 0) {
                $(`<div class="images-reply"></div>`).insertAfter(param);
                $('.images-reply').html(response['images']);
                height = $('.calc-h-media').innerHeight() + 36; // Height of grey line

            } else {
                height = closestHeight;
            }

            $('.enter-h').attr('style', `height:${height}px;width:2px`);
            $('.comment-form').attr('action', `/replies/${response['id']}`);
            $(".comment-overlay, .comment-content, .overlay-shade").addClass("active");
            $("body").css({
                "overflow": "hidden"
            });

        }

        // Check if it's a retweet or an original
        if (response['comment']) {
            popupContent('reposter_avatar', 'reposter_name', 'reposter_username', 'reposted_datetime', 'comment');
            $(`<div class="original-tweet border border-gray-400 rounded-xlt p-3 mt-2 mr-3"><div class="flex items-center"><img class="rounded-full object-cover mr-2" style="width:20px;height:20px" src="${response['avatar']}"><div class="mr-2 font-bold">${response['name']}</div><div class="text-gray-600 mr-1">${response['username']}</div><div class="text-gray-600">&middot; ${response['datetime']}</div></div><div class="word-break res-ori-body">${response['body']}</div></div>`).insertAfter('.response-body');
            imgEmpty('.res-ori-body');
        } else {
            popupContent('avatar', 'name', 'username', 'datetime', 'body');
            imgEmpty('.response-body');
        }
    });
});

// SAVE REPLY TO DB
$(document).on('submit', '.comment-form', function (event) {
    event.preventDefault();
    let post_url = $(this).attr("action");
    let request_method = $(this).attr("method");
    let url = window.location.pathname;
    let form_data = $(this).serialize();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: post_url,
        type: request_method,
        data: form_data
    }).done(function () {
        
        $(".comment-overlay, .comment-content, .overlay-shade").removeClass("active");
        $('.comment-form').removeAttr('action');
        $("body").css("overflow", "visible");
        $(".load-tweets").load(`${url} .load-tweets-ajax`);

    }).fail(function (xhr) {
        $.each(xhr.responseJSON.errors, function (index, val) {
            $('[name=' + index + ']');
            $.each(val, function (i, error) {
                $('.publish-errors-reply').html(error);
            });
        });
    });
});

// UNRETWEET
$(document).ready(function () {
    $(document).on('click', 'li.unretweet', function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let retweet_id = $(this).data('id');
        let token = $('meta[name="csrf-token"]').attr('content');
        let el = this;
        let url = window.location.pathname;

        $.ajax({
            url: `/unretweets/${retweet_id}`,
            type: 'DELETE',
            data: {
                'id': retweet_id,
                '__token': token
            }

        }).done(function () {
            let me = $(el).closest('.modal2'); 
            $(me[0]).remove();

            if (url.includes('/tweet/')) {
                $(".fi-so").load(`${url}  .fi-so-load`);
                $(".th-in").load(`${url}  .th-in-load`);
            }

            $(".load-tweets").load(`${url}  .load-tweets-ajax`);
        });
    });
});

// UPDATE BIO
$(document).on('submit', '.bio-form', function (event) {
    event.preventDefault();
    let post_url = $(this).attr("action");
    let request_method = $(this).attr("method");
    let el = this;
    let url = window.location.pathname;
    let form_data = $(this).serialize();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: post_url,
        type: request_method,
        data: form_data
    }).done(function () {
        $(".bio-overlay, .bio-content").removeClass("active");
        $(".bio").load(`${url} .user-bio, .open-bio`);
        $("body").css("overflow", "visible");
    });
});


//DELETE COMMENT
$(document).ready(function () {
    $(document).on('click', 'li.delete-comment', function () {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let delete_id = $(this).data('id');
        let token = $('meta[name="csrf-token"]').attr('content');
        let el = this;
        let url = window.location.pathname;

        $.ajax({
            url: `/tweets/comment/${delete_id}/delete`,
            type: 'DELETE',
            data: {
                'id': delete_id,
                '__token': token
            }

        }).done(function () {
            let me = $(el).closest('.modal2'); 
            $(me[0]).remove();
            $(".load-tweets").load(`${url}  .load-tweets-ajax`);
        });
    });
});

// DYNAMIC PROFILE NAV LINKS - replies
$(document).ready(function () {
    $(document).on('click', '.with-replies', function (e) {
        e.preventDefault();

        let url = window.location.pathname; // /profile/username/...
        let splitted = url.split("");
        let spliced = splitted.splice(9); // username/....

        // Iterate over every char and if it encounters '/' break out of loop
        let arr = [];
        for (let i = 0; i < spliced.length; i++) {
            if (spliced[i] == '/') {
                break;
            } else {
                arr.push(spliced[i]);
            }
        }

        let joined = arr.join("");

        $.ajax({
            url: `/profile/${joined}/with-replies-res`,
            type: 'GET'

        }).done(function (response) {
            history.pushState({}, null, `/profile/${joined}/with-replies`);
            $(".load-tweets").html(response['with-replies']);
        });
    });
});

// DYNAMIC PROFILE NAV LINKS - tweets
$(document).ready(function () {
    $(document).on('click', '.profile-tweets', function (e) {
        e.preventDefault();
        let url = window.location.pathname;
        
        let splitted = url.split("");
        let spliced = splitted.splice(9);
        let arr = [];
        for (let i = 0; i < spliced.length; i++) {
            if (spliced[i] == '/') {
                break;
            } else {
                arr.push(spliced[i]);
            }
        }

        let joined = arr.join("");

        $.ajax({
            url: `/profile/${joined}/tweets`,
            type: 'GET'

        }).done(function (response) {
            history.pushState({}, null, `/profile/${joined}`);
            $(".load-tweets").html(response['tweets-timeline']);
        });
    });
});

// DYNAMIC PROFILE NAV LINKS - media
$(document).ready(function () {
    $(document).on('click', '.media', function (e) {
        e.preventDefault();
        let url = window.location.pathname;
        
        let splitted = url.split("");
        let spliced = splitted.splice(9);
        let arr = [];
        for (let i = 0; i < spliced.length; i++) {
            if (spliced[i] == '/') {
                break;
            } else {
                arr.push(spliced[i]);
            }
        }

        let joined = arr.join("");

        $.ajax({
            url: `/profile/${joined}/media-res`,
            type: 'GET'

        }).done(function (response) {
            history.pushState({}, null, `/profile/${joined}/media`);
            $(".load-tweets").html(response['media']);
        });
    });
});

// DYNAMIC PROFILE NAV LINKS - likes
$(document).ready(function () {
    $(document).on('click', '.likes-nav', function (e) {
        e.preventDefault();
        let url = window.location.pathname;
        
        let splitted = url.split("");
        let spliced = splitted.splice(9);
        let arr = [];
        for (let i = 0; i < spliced.length; i++) {
            if (spliced[i] == '/') {
                break;
            } else {
                arr.push(spliced[i]);
            }
        }

        let joined = arr.join("");

        $.ajax({
            url: `/profile/${joined}/likes-res`,
            type: 'GET'

        }).done(function (response) {
            history.pushState({}, null, `/profile/${joined}/likes`);
            $(".load-tweets").html(response['likes']);
        });
    });
});
