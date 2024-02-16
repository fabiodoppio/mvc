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
        <link rel="icon" href="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/media/favicon.svg"/>
        <link rel="canonical" href="{{$page->canonical}}"/>
        <link rel="stylesheet" href="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/assets/styles/root.css"/>
        <link rel="stylesheet" href="{{$app->url}}{{$app->directory->vendor}}/twbs/bootstrap/dist/css/bootstrap-reboot.min.css"/>
        <link rel="stylesheet" href="{{$app->url}}{{$app->directory->vendor}}/components/font-awesome/css/all.min.css"/>
        <link rel="stylesheet" href="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/assets/styles/general.css"/>
        <link rel="stylesheet" href="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/assets/styles/admin.css"/>
        <script src="/app/vendor/fabiodoppio/mvc/src/assets/scripts/codemirror/codemirror.min.js"></script>
        <script src="/app/vendor/fabiodoppio/mvc/src/assets/scripts/codemirror/javascript.min.js"></script>
        <script src="/app/vendor/fabiodoppio/mvc/src/assets/scripts/codemirror/css.min.js"></script>
        <script src="/app/vendor/fabiodoppio/mvc/src/assets/scripts/codemirror/xml.min.js"></script>
        <link rel="stylesheet" href="/app/vendor/fabiodoppio/mvc/src/assets/styles/codemirror/codemirror.min.css" />
        <script src="{{$app->url}}{{$app->directory->vendor}}/components/jquery/jquery.min.js"></script>
        <script src="{{$app->url}}{{$app->directory->vendor}}/components/jqueryui/jquery-ui.min.js"></script>
        <script src="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/assets/scripts/ajax.js"></script>
        <script src="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/assets/scripts/hooks.js"></script>
        <script src="{{$app->url}}{{$app->directory->vendor}}/{{$framework->package}}/src/assets/scripts/admin.js"></script>
    </head>
    <body data-client="{{$client->id}}">