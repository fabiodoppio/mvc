{{"Hello %s,", $account->meta->displayname ?? $account->username}} 
<br/><br/>
{{"you are receiving this email because your account on %s has been deactivated.", $app->name}}
<br/><br/>
{{"If this was a mistake, you have 90 days to recover your account before it will be permanently deleted from our servers. You can click on this link to start the recovery:"}} <a href="{{$app->url}}/recovery">{{$app->url}}/recovery</a>
<br/><br/><br/>
{{"Best regards"}}<br/>
{{$app->name}}
<div style="font-size:12px; line-height: 150%; margin-top:50px;">
    - - -<br/>
    {{"This is an automatically generated email, please do not reply."}}
</div>