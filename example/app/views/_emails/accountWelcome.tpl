<!doctype html>
<html>
    <body style="background-color:#ebebeb;margin:0;padding:0;font-family:'Helvetica Neue',Arial,sans-serif;">
        <div style="background-color:#fff;color:#1a1825;margin:50px auto 20px;max-width:600px;padding:20px;text-align:center;border:1px solid #e3e3e3;border-top:5px solid #3f49f7;">
            <div style="text-align:center;padding:10px;">
                <img src="{{$app->url}}/media/favicon.png" alt="_logo" style="width:64px;height:auto;display:block;margin:0 auto;" />
            </div>
            <div style="text-align:center;font-size:28px;font-weight:bold;margin:10px 0 50px;">
                {{"Welcome to %s", $app->name}}
            </div>
            <div style="font-size:16px;line-height:22px;text-align:left;">
                {{"Hello %s,", $account->username}}
                <br/><br/>
                {{"many thanks for your registration on %s.", $app->name}}
                <br/><br/>
                {{"We hope you enjoy all the features our app has to offer."}}
                <br/><br/><br/>
                {{"Best regards"}}<br/>
                {{$app->name}}
            </div>
        </div>
        <div style="text-align:center;font-size:12px;line-height:18px;color:#76747e;margin:20px 0;">
            {{"This is an automatically generated email, please do not reply."}}<br/>
            {{"<a href=\"%s/account/email\" style=\"color:#76747e;\">Unsubscribe</a> from newsletter", $app->url}}
        </div>
    </body>
</html>