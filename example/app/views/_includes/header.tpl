<!-- Copyright (C) {{date('Y')}} {{$app->name}} - All Rights Reserved. Powered by {{$framework->package}} -->

<!DOCTYPE html>
<html lang="{{'en'}}">
    <head>
        <base href="{{$app->url}}/">
		<meta charset="UTF-8"/>
        <meta http-equiv="Cache-Control" content="no-cache"/>
		<meta name="viewport" content="width=device-width, shrink-to-fit=yes"/>
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
        <link rel="icon" type="image/png" href="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/media/favicon.png"/>
        <link rel="canonical" href="{{$page->canonical}}"/>
        <link rel="stylesheet" href="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/assets/styles/reboot.css"/>
        <link rel="stylesheet" href="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/assets/styles/icons.css"/>
        <link rel="stylesheet" href="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/assets/styles/root.css"/>
        <link rel="stylesheet" href="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/assets/styles/effects.css"/>
        <link rel="stylesheet" href="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/assets/styles/general.css"/>
        <script src="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/assets/scripts/jquery.js"></script>
        <script src="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/assets/scripts/jquery-ui.js"></script>
        <script src="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/assets/scripts/ajax.js"></script>
        <script src="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/assets/scripts/hooks.js"></script>
        <script src="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/assets/scripts/main.js"></script>
    </head>
    <body class="{{$page->class}}" data-token="{{$instance->token}}">