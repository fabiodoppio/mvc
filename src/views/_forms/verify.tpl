<form data-request="account/email/verify">       
    <div class="alert is--warning">{{"We've sent you a <b>confirmation code</b> to verify to the registered <b>email address</b>."}}</div>
    <label for="code">
        {{"Confirmation Code"}} <span class="is--required" title="{{'Required'}}">*</span>
        <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"You can find the 6-digit confirmation code in the email we sent you."}}</span></div>
        <input type="text" id="code" name="code" value="" maxlength="9" placeholder="{{'Enter confirmation code'}}" autocomplete="one-time-code" required/>
    </label>
    <button class="btn is--primary is--submit">{{"Verify Email Address"}}</button>
    <a href="{{$app->url}}/account/email" class="btn is--secondary">{{"Abort"}}</a>
</form>