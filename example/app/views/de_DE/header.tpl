{% use MVC\App as App; %}
<!-- Copyright (C) {{date('Y')}} {{App::get('APP_NAME')}} - All Rights Reserved. -->

<!DOCTYPE html>
<html lang="de">
    <head>
        <base href="{{App::get('APP_URL')}}/">
		<meta charset="UTF-8"/>
        <meta http-equiv="Cache-Control" content="no-cache"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, shrink-to-fit=yes"/>
		<meta name="author" content="{{App::get('APP_AUTHOR')}}"/>
	    <meta name="robots" content="{{$robots}}"/>
        <meta name="description" content="{{$description}}"/>
        <meta name="keywords" content=""/>
        <meta name="revisit-after" content="14 days"/>
        <meta property="og:title" content="{{$title}}"/>
        <meta property="og:locale" content="de-DE"/>
        <meta property="og:description" content="{{$description}}"/>
        <meta property="og:url" content="{{$canonical}}"/>
        <meta property="og:site_name" content="{{App::get('APP_NAME')}}"/>
        <meta property="og:image" content=""/>
        <title>{{$title}}</title>
        <link rel="icon" type="image/png" href="{{App::get('APP_URL')}}{{App::get('DIR_MEDIA')}}/favicon.png"/>
        <link rel="canonical" href="{{$canonical}}"/>
        <link rel="stylesheet" href="{{App::get('APP_URL')}}{{App::get('DIR_STYLES')}}/general.css"/>
        <script src="{{App::get('APP_URL')}}{{App::get('DIR_VENDOR')}}/components/jquery/jquery.min.js"></script>
        <script src="{{App::get('APP_URL')}}{{App::get('DIR_SCRIPTS')}}/ajax.js"></script>
	</head>
    <body data-client="{{$client}}">
