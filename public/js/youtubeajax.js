jQuery(document).ready(function () {
    jQuery('#keyword2').keydown(function(e){
        if(e.keyCode === 13){
            sendAjaxRequest();
        }
    });

    jQuery('#submit').on('click', function () {
        sendAjaxRequest();
    });

    function sendAjaxRequest() {
        jQuery.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
            }
        });

        var originalString = jQuery('#keyword2').val();
        var keyword = originalString.replace(/(<([^>]+)>)/gi, "");

        if (keyword.length >= 4) {
            jQuery.ajax({
                url: 'youtube/' + keyword,
                method: 'post',
                data: {}
            }).done(function (data) {
                data = JSON.parse(data);
                if(typeof  data == "object"){
                    jQuery('.videoDiv').empty();
                    jQuery('.videoInfo').empty();
                    data.items.forEach(function (single) {
                        jQuery('.videoDiv').append('<iframe style="width:50%;height:300px" src="//www.youtube.com/embed/' + single.id.videoId + '" data-autoplay-src="//www.youtube.com/embed/' + single.id.videoId + '"></iframe>')
                        jQuery('.videoDiv').append('<div class="videoTitle"><b>' + single.snippet.title + '</b></div>')
                        jQuery('.videoDiv').append('<div class="videoDesc"><b>' + single.snippet.description + '</b></div>')
                    })
                } else {
                    jQuery('.videoDiv').empty();
                    jQuery('.videoDiv').append(data);
                }
            })
        } else {
            jQuery('.videoDiv').empty();
            jQuery('.videoDiv').append('<h2>Enter more than 3 letters!</h2>');
        }
    }
});
