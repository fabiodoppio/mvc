<!-- Copyright (C) {{date('Y')}} {{$app->name}} - All Rights Reserved. Powered by {{$framework->package}} -->

<!DOCTYPE html>
<html lang="{{'en'}}">
    <head>
        <base href="{{$app->url}}/">
		<meta charset="UTF-8"/>
        <meta http-equiv="expires" content="3600"/>
		<meta name="viewport" content="width=device-width, shrink-to-fit=yes"/>
		<meta name="author" content="{{$app->author}}"/>
	    <meta name="robots" content="{{$page->meta->robots}}"/>
        <meta name="description" content="{{$page->meta->description}}"/>
        <meta name="keywords" content="{{$page->meta->keywords}}"/>
        <meta name="revisit-after" content="14 days"/>
        <meta property="og:title" content="{{$page->meta->title}}"/>
        <meta property="og:locale" content="{{'en-GB'}}"/>
        <meta property="og:description" content="{{$page->meta->description}}"/>
        <meta property="og:url" content="{{$app->url}}{{$request->uri}}"/>
        <meta property="og:site_name" content="{{$app->name}}"/>
        <meta property="og:image" content=""/>
        <title>{{$page->meta->title}}</title>
        <link rel="icon" type="image/png" href="{{$app->url}}/media/favicon.png"/>
        <link rel="canonical" href="{{$app->url}}{{$request->uri}}"/>
        <link rel="stylesheet" href="{{$app->url}}/assets/styles/reboot.css"/>
        <link rel="stylesheet" href="{{$app->url}}/assets/styles/icons.css"/>
        <link rel="stylesheet" href="{{$app->url}}/assets/styles/root.css"/>
        <link rel="stylesheet" href="{{$app->url}}/assets/styles/effects.css"/>
        <link rel="stylesheet" href="{{$app->url}}/assets/styles/general.css"/>
        <script src="{{$app->url}}/assets/scripts/jquery.js"></script>
        <script src="{{$app->url}}/assets/scripts/jquery-ui.js"></script>
        <script src="{{$app->url}}/assets/scripts/ajax.js"></script>
        <script src="{{$app->url}}/assets/scripts/hooks.js"></script>
        <script src="{{$app->url}}/assets/scripts/main.js"></script>
    </head>
    <body class="{{$page->meta->class}}" data-token="{{$instance->token}}">