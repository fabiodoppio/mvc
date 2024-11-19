{{"Hello %s,", $account->meta->displayname ?? $account->username}} 
<br/><br/>
{{"you are receiving this email because you are trying to verify your email address on %s.", $app->name}}
<br/><br/>
{{"Please enter the following code to complete the verification:"}}
<br/><br/>
<span style="font-size:24px; font-weight:bold; letter-spacing:.15em;">{{$var->code}}</span>
<br/><br/>
{{"The code expires <b>15 minutes</b> after receiving this email."}}
<br/><br/><br/>
{{"Best regards"}}<br/>
{{$app->name}}
<div style="font-size:12px; line-height: 150%; margin-top:50px;">
    - - -<br/>
    {{"This is an automatically generated email, please do not reply."}}
</div>