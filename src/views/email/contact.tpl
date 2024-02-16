{{"Hello"}},
<br/><br/>
{{"you received a new message via the contact form"}}:
<br/><br/>
<b>{{"From"}}:</b> {{$var->name}} <<a href="mailto:{{$var->email}}">{{$var->email}}</a>><br/>
{% if ($var->subject != ""): %} <b>{{"Subject"}}:</b> {{$var->subject}}<br/>{% endif; %}
{% if ($var->platform != ""): %} <b>{{"Platform"}}:</b> {{$var->platform}}<br/>{% endif; %}
<br/>
<b>{{"Message"}}:</b><br/>
{{$message}}
{% if ($var->attachment != ""): %} 
    <br/>
    <br/>
    <b>{{"Attachment"}}:</b><br/>
    <a href="{{$app->url}}{{$app->directory->media}}/{{$var->attachment}}">{{$var->attachment}}</a>
{% endif; %}
<br/><br/><br/>
{{"Best regards"}}<br/>
{{$app->name}}
<div style="font-size:12px; line-height: 150%; margin-top:50px;">
- - -<br>
{{"This is an automatically generated email, please do not reply."}}
</div>