{% use MVC\App as App; %}
{% use MVC\Auth as Auth; %}
{% use MVC\Request as Request; %}

<!-- Copyright (C) {{date('Y')}} {{App::get('APP_NAME')}} - All Rights Reserved. -->

<!DOCTYPE html>
<html lang="en">
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
        <meta property="og:locale" content="en-EN"/>
        <meta property="og:description" content="{{$description}}"/>
        <meta property="og:url" content="{{$canonical}}"/>
        <meta property="og:site_name" content="{{App::get('APP_NAME')}}"/>
        <meta property="og:image" content=""/>
        <title>{{$title}}</title>
        <link rel="icon" type="image/png" href="{{App::get('APP_URL')}}{{App::get('DIR_MEDIA')}}/favicon.png"/>
        <link rel="canonical" href="{{$canonical}}"/>
        <link rel="stylesheet" href="{{App::get('APP_URL')}}{{App::get('DIR_VENDOR')}}/{{App::get('SRC_PACKAGE')}}/src/assets/css/general.css"/>
        {% foreach (json_decode(App::get("FILES_CSS")) as $file): %}
            <link rel="stylesheet" href="{{App::get('APP_URL')}}{{App::get('DIR_STYLES')}}/{{$file}}"/>
        {% endforeach; %}
        <script src="{{App::get('APP_URL')}}{{App::get('DIR_VENDOR')}}/components/jquery/jquery.min.js"></script>
        <script src="{{App::get('APP_URL')}}{{App::get('DIR_VENDOR')}}/{{App::get('SRC_PACKAGE')}}/src/assets/js/ajax.js"></script>
        {% foreach (json_decode(App::get("FILES_JS")) as $file): %}
            <script src="{{App::get('APP_URL')}}{{App::get('DIR_SCRIPTS')}}/{{$file}}"></script>
        {% endforeach; %}
	</head>
    <body data-client="{{Auth::get_client_token()}}">
