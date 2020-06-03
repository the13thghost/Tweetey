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
           }).done(function () { //
               //let url = post_url.replace('/follow', ''); // we replaced the current url so it removes /follow
               let url = window.location.pathname;
               $('.follow-text').fadeOut(0, function () {
                   $('.follow-text').remove();
               });
               $(".follow-btn").load(`${url}  .follow-text`, function () {
                   $(this).css('opacity', 0).stop().animate({
                       'opacity': 1
                   });
               });

               //reload the friends sidebar
               $(".following").load(`${url}  .following-users`, function () {
                   $(this).css('opacity', 0).stop().animate({
                       'opacity': 1
                   });
               });
           });
       });

       //change currents clicked div color
       function changeCurrentLikeColor(el) {
           if ($(el).hasClass("text-blue-400")) {
               $(el).removeClass('text-blue-400').addClass("text-gray-500");
           } else {
               $(el).removeClass('text-gray-500').addClass("text-blue-400");
           }
       }

       //change the oposite btn color
       function changeLikeColor(div, id) {
           if ($(div).hasClass("text-blue-400")) {
               $(div).removeClass('text-blue-400').addClass("text-gray-500");
           } else if ($(div).hasClass("text-gray-500") && $(".load-ajax-dis-" + id).is(':empty')) {
               $(div).removeClass('text-gray-500').addClass("text-gray-500");
           }
       }

       //thumbs up without form
       $(document).ready(function () {
           $(document).on('click', 'div.submit-like', function () {

               $.ajaxSetup({
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   }
               });

               let submit_like = $(this).data('id');
               let token = $('meta[name="csrf-token"]').attr('content');
               let el = this;
               let url = window.location.pathname;

               $.ajax({
                   url: `/tweets/${submit_like}/like`,
                   type: 'POST',
                   data: {
                       'id': submit_like,
                       '__token': token
                   }

               }).done(function () {

                   // $('.load-ajax-' + submit_like).remove();
                   // $('.load-ajax-dis-' + submit_like).remove();
                   changeCurrentLikeColor(el);
                   let dislike_section = $(el).parent().next().children(":first");
                   //update like color
                   changeLikeColor(dislike_section, submit_like);
                   $(".load-here-" + submit_like).load(`${url}  .load-ajax-${submit_like}`);
                   $(".load-here-dis-" + submit_like).load(`${url}  .load-ajax-dis-${submit_like}`);
               });
           });
       });

       //THUMBS DOWN

       //thumbs down without form

       $(document).ready(function () {
           $(document).on('click', 'div.submit-dislike', function () {

               $.ajaxSetup({
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   }
               });

               let submit_id = $(this).data('id');
               let token = $('meta[name="csrf-token"]').attr('content');
               let el = this;
               let url = window.location.pathname;

               $.ajax({
                   url: `/tweets/${submit_id}/dislike`,
                   type: 'POST',
                   data: {
                       'id': submit_id,
                       '__token': token
                   }
               }).done(function () {
                   changeCurrentLikeColor(el);
                   //update like color
                   let like_section = $(el).parent().prev().children(":first"); // previous sibling
                   changeLikeColor(like_section, submit_id);
                   $(".load-here-dis-" + submit_id).load(`${url}  .load-ajax-dis-${submit_id}`, function() {
                    $(".load-here-" + submit_id).load(`${url}  .load-ajax-${submit_id}`);
                   });
               });
           });
       });



       //DELETE A tweet

       $(document).ready(function () {
           $(document).on('click', 'li.delete-post', function () {

               // if(!confirm("Do you really want to do this?")) {
               //     return false;
               //   } 

               $.ajaxSetup({
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   }
               });

               let delete_id = $(this).data('id');
               let token = $('meta[name="csrf-token"]').attr('content');
               let el = this;
               let url = window.location.pathname;
               let me = $(el).closest('.whole');
               let ze = $(me).next();

               $.ajax({
                   url: `/tweets/${delete_id}/delete`,
                   type: 'DELETE',
                   data: {
                       'id': delete_id,
                       '__token': token
                   },

               }).done(function () {

                   $(me).remove();
                   $(ze).remove();
                   $(".load-tweets").load(`${url}  .load-tweets-ajax`);
               });
           });
       });



       //             $.get("refresh.php")
       // .done(function(r) {
       //     var newDom = $(r);
       //     $('#avunits').replaceWith($('#avunits',newDom));
       //     $('#assigned').replaceWith($('#assigned',newDom));
       //     $('#pending').replaceWith($('#pending',newDom));
       //  });


       // let url = post_url.replace('/follow', ''); // we replaced the current url so it removes /follow
       // //alert(get);
       //    /tweets/{{$tweet->id}}/like replce with current url
       // var pathname = window.location.pathname; 


       // PUBLISH FORM WITH PHOTOS -> sending files via ajay -> FormData()s

       $('#publish').on('submit', function (event) {
           event.preventDefault();

           let form = $(this)[0];
           //    console.log(form);
           let form_data = new FormData(form);

           let post_url = $(this).attr("action");
           let request_method = $(this).attr("method"); // /tweets post

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
           }).done(function () { //
               // alert(response);
               //let url = post_url.replace('/follow', ''); // we replaced the current url so it removes /follow
               //alert(get);
               //    alert(form_data);
               let url = window.location.pathname;

               // // ajax is asynchronous or whatever if we would write without callback it wouldnt be ceratin
               // // which .load() gets executed first but with method below we get order, but its lag so no to this?
               $(".counter-1").load(`${url}  .counter-fresh`);
               $(".tweets-1").load(`${url}  .tweets-fresh`);
               $(".textarea-1").load(`${url}  .textarea-fresh`);
               $('.gallery').children().remove();

               $(".notify-published").fadeIn(function () {
                   $(this).delay(6000).fadeOut(1000);
               });





               // //     });
               // // });

               // // because ajax is async by nature we can do this i think but its the same lag, 1 then half a second
               // //the other and so on
               // $(".textarea-1").load(`${url}  .textarea-fresh`);
               // $(".counter-1").load(`${url}  .counter-fresh`);
               // $(".tweets-1").load(`${url}  .tweets-fresh`);


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
                   let me = $(el).closest('.modal2'); // i need me[0] so i get dom el
                   $(me[0]).remove();
                   $(".load-tweets").load(`${url}  .load-tweets-ajax`);
               });
           });
       });

       //RETWEET WITH COMMENT - send to server the id of that tweet and return the tweet so we can use its
       //body in form to show the origigi tweet maybe ajax inside of ajax?

       $(document).on('click', '.retweet-comment', function (event) {
           event.preventDefault();
           let token = $('meta[name="csrf-token"]').attr('content');
           let id = $(this).data('id');
           console.log(id);

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

               // split into Array, let lefover = splice(char where to start), 
               // in the remaining array push 3 DOMStringList, then join("")
               //check if the last char is space so you go back a few: name ... is bad
               let array = response['body'];
               let splittedArr = array.split("");
               let hay = splittedArr.splice(100);
               splittedArr.push('...');
               let hhhh = splittedArr.join("");
               console.log(hhhh);

               // alert(response['body']);
               $div = `<div class="text-sm text-left border border-gray-300 rounded-lg p-2 mb-3 before-div word-break-popup" style="max-width:500px">${hhhh}</div>`;
               $('.counter-1').before($div);
               $('.retweet-comment-form').attr('action', `/retweets/${response['id']}/comment`);



           });
       });


       // RETWEET WITH COMMENT - (final) send record to DB

       $(document).on('submit', '.retweet-comment-form', function (event) {
           // console.log(23);
           //the action was added via jquery
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
               let me = $(el).closest('.modal2'); // i need me[0] so i get dom el
               $(me[0]).remove();
               $(".popup-overlay, .popup-content").removeClass("active");
               $('.retweet-comment-form').removeAttr('action');
               $(".load-tweets").load(`${url}  .load-tweets-ajax`);
               $("body").css("overflow", "visible");
           });
       });
       // end

       // SHOW REPLYING TO POPUP - 1. ADD THE ORIGINAL TWEET TO THE FORM, like replying to @josepgmorgan and his tweet

       $(document).on('click', '.comment', function (event) {
        event.preventDefault();
        let token = $('meta[name="csrf-token"]').attr('content');
        let id = $(this).data('id');
        // console.log(id);

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

            // split into Array, let lefover = splice(char where to start), 
            // in the remaining array push 3 DOMStringList, then join("")
            //check if the last char is space so you go back a few: name ... is bad
            let array = response['body'];
            let splittedArr = array.split("");
            let hay = splittedArr.splice(100);
            splittedArr.push('...');
            let hhhh = splittedArr.join("");

            //check if its a retweet or original
            if(response['comment']) {
                $('.original-poster').attr('src', `${response['reposter_avatar']}`);
                $('.response-name').text(`${response['reposter_name']}`);
                $('.response-username').text(`@${response['reposter_username']}`);
                $('.datetime').text(`${response['reposted_datetime']}`);
                $('.response-body').text(`${response['comment']}`);
                $(`<div class="original-tweet border border-gray-400 rounded-xlt p-3 mt-2"><div class="flex items-center"><img class="rounded-full object-cover mr-2" style="width:20px;height:20px" src="${response['avatar']}"><div class="mr-2 font-bold">${response['name']}</div><div class="text-gray-600 mr-1">${response['username']}</div><div class="text-gray-600">&middot; ${response['datetime']}</div></div><div class="word-break">${response['body']}</div></div>`).insertAfter('.response-body');
                let beforeHeight = $('.calc-h').innerHeight();
                let height = beforeHeight - 50;
                $('.enter-h').attr('style', `height:${height}px;width:2px`);
                $('.comment-form').attr('action', `/replies/${response['id']}`);
            } 
            else 
            {
                $('.original-poster').attr('src', `${response['avatar']}`);
                $('.response-name').text(`${response['name']}`);
                $('.response-username').text(`@${response['username']}`);
                $('.datetime').text(`${response['datetime']}`);
                $('.response-body').text(`${response['body']}`);
                $('.enter-h').removeAttr('style');
                let beforeHeight = $('.calc-h').innerHeight();
                let height = beforeHeight - 50;
                $('.enter-h').attr('style', `height:${height}px;width:2px`);
                $('.comment-form').attr('action', `/replies/${response['id']}`);
            }
            
        });
    });

    // SAVE REPLY TO DATABASE - continue from above, goes to replies/#id

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
            $(".comment-overlay, .comment-content").removeClass("active");
            $('.comment-form').removeAttr('action');
            $("body").css("overflow", "visible");
            $(".load-tweets").load(`${url} .load-tweets-ajax`);
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
                   let me = $(el).closest('.modal2'); // i need me[0] so i get dom el
                   $(me[0]).remove();
                   $(".load-tweets").load(`${url}  .load-tweets-ajax`);
               });
           });
       });

       //AUTOCOMPLETE

       // $('textarea.mention').mentionsInput({
       //     onDataRequest:function (mode, query, callback) {
       //     $.getJSON('a', function(responseData) {
       //     responseData = _.filter(responseData, function(item) { return item.name.toLowerCase().indexOf(query.toLowerCase()) &amp;gt; -1 });
       //     callback.call(this, responseData);
       //     });
       //     }
       //     });

       // UPDATE BIO

       $(document).on('submit', '.bio-form', function (event) {
           // console.log(23);
           event.preventDefault();
           let post_url = $(this).attr("action");
           console.log(post_url);
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
       // end


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
               console.log(el);
               let url = window.location.pathname;

               $.ajax({
                   url: `/tweets/comment/${delete_id}/delete`,
                   type: 'DELETE',
                   data: {
                       'id': delete_id,
                       '__token': token
                   }

               }).done(function () {
                   let me = $(el).closest('.modal2'); // i need me[0] so i get dom el
                   $(me[0]).remove();
                   $(".load-tweets").load(`${url}  .load-tweets-ajax`);
               });
           });
       });

    //    // DYNAMIC PROFILE NAV LINKS

       //tweets and replies
       $(document).ready(function () {
        $(document).on('click', '.with-replies', function (e) {
            e.preventDefault();

            // $(document).ajaxStart(function(){ start animation while ajax fetches the data
            //     // $(".load-tweets").children().hide();
            //     // $(".lds-spinner").show();
            //   });

            let url = window.location.pathname;
            let splitted = url.split("");
            let spliced = splitted.splice(9);
        
        //after spliced before it was /profile/mincoder/...
        // /mindcoder23/with-replies
        // /mindcoder23/media
        // /mindcoder23/links

        // iterate over every char and if it encounters / break out of loop
        let arr = [];
        for(let i = 0; i < spliced.length; i++) {
            if(spliced[i] == '/') {
                break;
            } else {
                arr.push(spliced[i]);
            }
        }

        let joined = arr.join("");

            $.ajax({
                url: `/profile/${joined}/with-replies-ajax`,
                type: 'GET'

            }).done(function (response) {
                // $(document).ajaxComplete(function(){ when ajax completes the request stop animatino loading spinner
                //     $(".load-tweets").css("background", "white");
                //   });
                history.pushState({}, null, `/profile/${joined}/with-replies`);
                $(".load-tweets").html(response['with-replies']);
                

            });
        });
    });

    //tweets nav link
    $(document).ready(function () {
        $(document).on('click', '.profile-tweets', function (e) {
            e.preventDefault();
            let url = window.location.pathname;
        let splitted = url.split("");
        let spliced = splitted.splice(9);
        let arr = [];
        for(let i = 0; i < spliced.length; i++) {
            if(spliced[i] == '/') {
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

    
