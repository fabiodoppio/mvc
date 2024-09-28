<form data-request="account/recovery">
    <div class="alert is--warning">{{"We've sent you a <b>confirmation code</b> to reset your password to the registered <b>email address</b>."}}</div>
    <label for="code">
        {{"Confirmation Code"}} <span class="is--required" title="{{'Required'}}">*</span>
        <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"You can find the 6-digit confirmation code in the email we sent you."}}</span></div>
        <input type="text" id="code" name="code" value="" maxlength="9" placeholder="{{'Enter confirmation code'}}" autocomplete="one-time-code" required/>
    </label>
    <br/><br/>                      
    <label for="pw1">
        {{"New Password"}} <span class="is--required" title="{{'Required'}}">*</span>
        <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your password must be at least 8 characters long."}}</span></div>
        <input type="password" id="pw1" name="pw1" minlength="8" maxlength="64" placeholder="{{'Enter new password'}}" autocomplete="new-password" required/>
    </label>
    <label for="pw2">
        {{"Repeat New Password"}} <span class="is--required" title="{{'Required'}}">*</span>
        <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your password must be at least 8 characters long."}}</span></div>
        <input type="password" id="pw2" name="pw2" minlength="8" maxlength="64" placeholder="{{'Repeat new password'}}" autocomplete="new-password" required/>
    </label>
    <button class="btn is--primary is--submit">{{"Recover Account"}}</button>
    <a href="{{$app->url}}/login" class="btn is--secondary">{{"Abort"}}</a>
</form>