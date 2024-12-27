{{"Hello %s,", $account->meta->displayname ?? $account->username}}
<br/><br/>
{% echo $var->message %}
<br/><br/><br/>
{{"Best regards"}}<br/>
{{$app->name}}
<div style="font-size:12px; line-height: 150%; margin-top:50px;">
    - - -<br/>
    {{"This is an automatically generated email, please do not reply."}}
</div>