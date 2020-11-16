<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aphix Youtube Search</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- Styles -->
    <link rel="stylesheet" type="text/css" href="css/style.css">
</head>
<body class="antialiased">
    <div class="search">
        <h2>Search Videos by keyword using YouTube Data API</h2>
        <input class="input-field" type="search" id="keyword2" name="keyword" placeholder="Enter Search Keyword">
        <input id="submit" class="btn-submit" type="submit" name="submit" value="Search">
    </div>

    <div class="video-tile">
        <div class="videoDiv">
        </div>
        <div class="videoInfo">
        </div>
    </div>

<script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
<script src="js/youtubeajax.js"></script>
</body>
</html>

