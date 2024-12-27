<form data-request="account/login">
    <div class="alert is--warning">{{"You are trying to log in from a new device or browser. We've sent you a <b>PIN code</b> to the registered <b>email address</b>."}}</div>
    <label for="code">
        {{"PIN Code"}} <span class="is--required" title="{{'Required'}}">*</span>
        <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"You can find the 6-digit PIN code in the email we sent you."}}</span></div>
        <input type="text" id="code" name="code" value="" maxlength="9" placeholder="{{'Enter PIN code'}}" autocomplete="one-time-code" required/>
    </label>
    <button class="btn is--primary is--submit">{{"Authenticate"}}</button>
    <a href="{{$app->url}}/login" class="btn is--secondary">{{"Back"}}</a>
</form>