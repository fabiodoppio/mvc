{% use MVC\App as App; %}
{% use MVC\Auth as Auth; %}
{% use MVC\Database as Database; %}
{% use MVC\Models as Model; %}
{% use MVC\Request as Request; %}
{% use MVC\Template as Template; %}

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
        {% foreach (json_decode(App::get("FILES_CSS")) as $file): %}
            {% if (file_exists(App::get("DIR_ROOT").$file)): %}
                <link rel="stylesheet" href="{{App::get('APP_URL')}}{{$file}}"/>
            {% endif; %}
        {% endforeach; %}
        {% foreach (json_decode(App::get("FILES_JS")) as $file): %}
            {% if (file_exists(App::get("DIR_ROOT").$file)): %}
                <script src="{{App::get('APP_URL')}}{{$file}}"></script>
            {% endif; %}
        {% endforeach; %}
	</head>
    <body data-client="{{Auth::get_client_token()}}">