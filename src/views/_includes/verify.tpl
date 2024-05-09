{% if ($request->ajax === false): %}
    <form data-request="account/email/verify">
{% endif; %}

{% if ($request->ajax === true || $request->code != ""): %}
    {% if ($request->code == ""): %}
        <div class="success">{{"We've sent you a <b>confirmation code</b> to verify to the registered <b>email address</b>."}}</div>
        <br><br>
    {% endif; %}
    <label for="code">
        {{"Confirmation Code"}} <span class="required" title="{{'Required'}}">*</span>
        <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"You can find the 9-digit confirmation code in the email we sent you"}}</span></div>
        <input type="text" id="code" name="code" value="{{$request->code}}" maxlength="11" placeholder="{{'Enter confirmation code'}}" required/>
    </label>
    <br><br>                         
    <div class="response"></div>
    <button class="btn is--primary">{{"Verify Email Address"}}</button>
{% else: %}
    <div class="warning">{{"We will send you a <b>confirmation code</b> to verify to your registered <b>email address</b>."}}</div>
    <br><br>
    <div class="response"></div>
    <button class="btn is--primary">{{"Request Confirmation Code"}}</button>
{% endif; %}

{% if ($request->ajax === false): %}
    </form>
{% endif; %}