<?php const MAX_RESULTS = 4 ?>
    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="antialiased">
<h2>Search Videos by keyword using YouTube Data API</h2>
<input class="input-field" type="search" id="keyword2" name="keyword" placeholder="Enter Search Keyword">
<input id="submit" class="btn-submit" type="submit" name="submit" value="Search">

<div class="video-tile">
    <div class="videoDiv">
    </div>
    <div class="videoInfo">
        {{--        <div class="videoTitle"><b><?php echo $title; ?></b></div>--}}
        {{--        <div class="videoDesc"><?php echo $description; ?></div>--}}
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script>
    jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });
    jQuery(document).ready(function () {
        var counter = 0;
        jQuery('#submit').on('click', function () {
            if (jQuery('#keyword2').val().length > 4) {
                var keyword = jQuery('#keyword2').val();
                jQuery.ajax({
                    url: 'youtube/' + keyword,
                    method: 'post',
                    data: {}
                }).done(function (data) {
                    data = JSON.parse(data);
                    jQuery('.videoDiv').empty();
                    data.items.forEach(function (single) {
                        jQuery('.videoDiv').append('<iframe style="width:100%;height:100%" src="//www.youtube.com/embed/' + single.id.videoId + '" data-autoplay-src="//www.youtube.com/embed/' + single.id.videoId + '"></iframe>')
                    })
                })
            }
        })
    });
</script>
</div>
</body>
</html>

