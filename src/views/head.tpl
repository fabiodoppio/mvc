<!-- Copyright (C) {{date('Y')}} {{$app->name}} - All Rights Reserved. Powered by {{$framework->package}} -->

<!DOCTYPE html>
<html lang="{{'en'}}">
    <head>
        <base href="{{$app->url}}/">
		<meta charset="UTF-8"/>
        <meta http-equiv="Cache-Control" content="no-cache"/>
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0, shrink-to-fit=yes"/>
		<meta name="author" content="{{$app->author}}"/>
	    <meta name="robots" content="{{$page->robots}}"/>
        <meta name="description" content="{{$page->description}}"/>
        <meta name="keywords" content=""/>
        <meta name="revisit-after" content="14 days"/>
        <meta property="og:title" content="{{$page->title}}"/>
        <meta property="og:locale" content="{{'en-EN'}}"/>
        <meta property="og:description" content="{{$page->description}}"/>
        <meta property="og:url" content="{{$page->canonical}}"/>
        <meta property="og:site_name" content="{{$app->name}}"/>
        <meta property="og:image" content=""/>
        <title>{{$page->title}}</title>
        <link rel="icon" type="image/png" href="{{$app->url}}{{$directory->media}}/favicon.png"/>
        <link rel="canonical" href="{{$page->canonical}}"/>
        <link rel="stylesheet" href="{{$app->url}}{{$directory->vendor}}/{{$framework->package}}/src/assets/styles/{{$app->theme}}.css"/>
        <link rel="stylesheet" href="{{$app->url}}{{$directory->vendor}}/twbs/bootstrap/dist/css/bootstrap-reboot.min.css"/>
        <link rel="stylesheet" href="{{$app->url}}{{$directory->vendor}}/components/font-awesome/css/all.min.css"/>
        <link rel="stylesheet" href="{{$app->url}}{{$directory->vendor}}/{{$framework->package}}/src/assets/styles/general.css"/>
        {% foreach ($custom->css as $file): %}
            <link rel="stylesheet" href="{{$app->url}}{{$file}}"/>
        {% endforeach; %}
        <script src="{{$app->url}}{{$directory->vendor}}/components/jquery/jquery.min.js"></script>
        <script src="{{$app->url}}{{$directory->vendor}}/components/jqueryui/jquery-ui.min.js"></script>
        {% if (!in_array($directory->scripts.'/ajax.js', $custom->js)): %}
            <script src="{{$app->url}}{{$directory->vendor}}/{{$framework->package}}/src/assets/scripts/ajax.js"></script>
        {% endif; %}
        {% if (!in_array($directory->scripts.'/hooks.js', $custom->js)): %}
            <script src="{{$app->url}}{{$directory->vendor}}/{{$framework->package}}/src/assets/scripts/hooks.js"></script>
        {% endif; %}
        {% if (!in_array($directory->scripts.'/main.js', $custom->js)): %}
            <script src="{{$app->url}}{{$directory->vendor}}/{{$framework->package}}/src/assets/scripts/main.js"></script>
        {% endif; %}
        {% foreach ($custom->js as $file): %}
            <script src="{{$app->url}}{{$file}}"></script>
        {% endforeach; %}
    </head>
    <body data-client="{{$client->id}}">