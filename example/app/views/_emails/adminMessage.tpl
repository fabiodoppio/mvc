<!doctype html>
<html>
    <body style="background-color:#ebebeb;margin:0;padding:0;font-family:'Helvetica Neue',Arial,sans-serif;">
        <div style="background-color:#fff;color:#1a1825;margin:50px auto 20px;max-width:600px;padding:20px;text-align:center;border:1px solid #e3e3e3;border-top:5px solid #3f49f7;">
            <div style="text-align:center;padding:10px;">
                <img src="{{$app->url}}/media/logo.png" alt="_logo" style="width:60px;height:auto;display:block;margin:0 auto;"/>
            </div>
            <div style="text-align:center;font-size:28px;font-weight:bold;margin:10px 0 30px;">
                {{$var->prefix}}{{"New Message"}}
            </div>
            <div style="font-size:16px;line-height:22px;text-align:left;">
                {{"Hello,"}}
                <br/><br/>
                {{"you received a new message via the contact form:"}}
                <br/><br/>
                <b>{{"From"}}:</b> {{$var->displayname}} <<a href="mailto:{{$var->email}}">{{$var->email}}</a>><br/>
                {% if ($var->subject != ""): %} <b>{{"Subject"}}:</b> {{$var->subject}}<br/>{% endif; %}
                {% if ($var->platform != ""): %} <b>{{"Platform"}}:</b> {{$var->platform}}<br/>{% endif; %}
                <br/>
                <b>{{"Message"}}:</b><br/>
                {{$var->message}}
                {% if ($var->attachment != ""): %}
                    <br/>
                    <br/>
                    <b>{{"Attachment"}}:</b><br/>
                    <a href="{{$app->url}}/media/uploads/{{$var->attachment}}">{{$var->attachment}}</a>
                {% endif; %}
                <br/><br/><br/>
                {{"Best regards"}}<br/>
                {{$app->name}}
            </div>
        </div>
        <div style="text-align:center;font-size:12px;line-height:18px;color:#76747e;margin:20px 0 50px;">
            {{"This is an automatically generated email, please do not reply."}}
        </div>
    </body>
</html>