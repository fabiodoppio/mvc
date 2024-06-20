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
        <link rel="icon" type="image/png" href="{{$app->url}}{{$app->media->favicon}}"/>
        <link rel="canonical" href="{{$page->canonical}}"/>
        <link rel="stylesheet" href="{{$app->url}}{{$app->asset->style->reboot}}"/>
        <link rel="stylesheet" href="{{$app->url}}{{$app->asset->style->icons}}"/>
        <link rel="stylesheet" href="{{$app->url}}{{$app->asset->style->root}}"/>
        <link rel="stylesheet" href="{{$app->url}}{{$app->asset->style->effects}}"/>
        <link rel="stylesheet" href="{{$app->url}}{{$app->asset->style->general}}"/>
        <script src="{{$app->url}}{{$app->asset->script->jquery}}"></script>
        <script src="{{$app->url}}{{$app->asset->script->jqueryui}}"></script>
        <script src="{{$app->url}}{{$app->asset->script->ajax}}"></script>
        <script src="{{$app->url}}{{$app->asset->script->hooks}}"></script>
        <script src="{{$app->url}}{{$app->asset->script->main}}"></script>
    </head>
    <body class="{{$page->class}}" data-token="{{$instance->token}}">