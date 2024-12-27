<!DOCTYPE html>
<html>
    <head>
		<meta charset="UTF-8"/>
        <meta http-equiv="Cache-Control" content="no-cache"/>
		<meta name="viewport" content="width=device-width, shrink-to-fit=yes"/>
	    <meta name="robots" content="noindex nofollow"/>
        <title>:(</title>
        <style>
           body{min-width:480px;color:#000;font-size:16px;line-height:1.5;font-family:system-ui,"Segoe UI",Roboto,Helvetica,Arial,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol"}h1{font-size:4rem}.title{margin:50px;line-height:120%;text-align:center}.container{width:100%;max-width:1200px;margin:0 auto}.alert{margin-bottom:20px;padding:20px;border-left:5px solid}.is--error{color:#dc3545;border-color:#f1aeb5;;background:#f8d7da}
        </style>
    </head>
    <body>
        <main>
            <div class="container">
                <h1 class="title">#<?=$this->getCode();?></h1>
                <div class="alert is--error"><?=$this->getMessage();?></div>
            </div>
        </main>
    </body>
</html>