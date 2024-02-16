{{"Hello %s", $account->meta->displayname}},
<br/><br/>
{{"you are receiving this email because someone is trying to verify your email address on %s. If this wasn't you, you can simply ignore this email.", $app->name}}
<br/><br/>
{{"Please enter the following code to complete the verification:"}}<br><br><span style="font-size:20px; font-weight:bold;">{{$var->code}}</span><br/><br/>
{{"Alternatively, you can click on this link:"}}<br/><a href="{{$var->link}}">{{$var->link}}</a>
<br/><br/><br/>
{{"Best regards"}}<br/>
{{$app->name}}
<div style="font-size:12px; line-height: 150%; margin-top:50px;">
- - -<br>
{{"This is an automatically generated email, please do not reply."}}
</div>