<form data-request="account/help">
    <label for="firstname">
        <input type="text" id="firstname" name="firstname" autocomplete="off" tabindex="-1"/>
    </label>
    <label for="name">
        {{"Name"}} <span class="is--required" title="{{'Required'}}">*</span>
        <input type="text" id="name" minlength="3" maxlength="18" name="name" value="{{$account->meta->displayname??$account->username}}" autocomplete="username name" placeholder="{{'Enter name'}}" required/>
    </label>
    <label for="email">
        {{"Email Address"}} <span class="is--required" title="{{'Required'}}">*</span>
        <input type="email" id="email" name="email" maxlength="64" placeholder="{{'Enter email address'}}" value="{{$account->email}}" autocomplete="email" required/>
    </label>
    {% if (isset($request->subject)): %}
        <label for="subject">
            {{"Subject"}} <span class="is--required" title="{{'Required'}}">*</span>
            <select id="subject" name="subject" required>
                <option value="" selected disabled>-- {{"Select Subject"}} --</option>
                <option value="{{'Help'}}">{{"Help"}}</option>
                <option value="{{'Feedback'}}">{{"Feedback"}}</option>
                <option value="{{'Report Bug'}}">{{"Report Bug"}}</option>
                <option value="{{'General Question'}}">{{"General Question"}}</option>
                <option value="{{'Legal Question'}}">{{"Legal Question"}}</option>
                <option value="{{'Account Issues'}}">{{"Account Issues"}}</option>
                <option value="{{'Change Username'}}">{{"Change Username"}}</option>
                <option value="{{'Other'}}">{{"Other"}}</option>
            </select>
        </label>
    {% endif; %}
    {% if (isset($request->platform)): %}
        <label for="platform">
            {{"Platform"}} <span class="is--required" title="{{'Required'}}">*</span>
            <select id="platform" name="platform" required>
                <option value="" selected disabled>-- {{"Select Platform"}} --</option>
                <option value="{{'All'}}">{{'All'}}</option>
                <option value="Google Chrome">Google Chrome</option>
                <option value="Microsoft Edge">Microsoft Edge</option>
                <option value="Mozilla Firefox">Mozilla Firefox</option>
                <option value="Safari">Safari</option>
                <option value="Opera">Opera</option>
                <option value="Brave">Brave</option>
                <option value="Internet Explorer">Internet Explorer</option>
                <option value="Chrome {{'for'}} Android">Chrome {{"for"}} Android</option>
                <option value="Safari {{'on'}} iOS">Safari {{"on"}} iOS</option>
                <option value="Samsung Internet">Samsung Internet</option>
                <option value="Opera Mini">Opera Mini</option>
                <option value="Opera Mobile">Opera Mobile</option>
                <option value="Android Browser">Android Browser</option>
                <option value="Firefox {{'on'}} Android">Firefox {{"on"}} Android</option>
                <option value="{{'Not listed'}}">{{'Not listed'}}</option>
            </select>
        </label>
    {% endif; %}
    <label for="message">
        {{"Message"}} <span class="is--required" title="{{'Required'}}">*</span>
        <textarea id="message" name="message" placeholder="{{'Enter message'}}" rows="5" autocomplete="off" required></textarea>
    </label>
    {% if (isset($request->attachment)): %}
        {% if ($account->role >= $account->roles->verified): %}  
            <label>
                {{"Attachment"}}
                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your attachment must not exceed the maximum allowed file size of %s KB.", 12288}}</span></div>
            </label>
            <span class="attachment-info"></span>
            <button class="btn is--secondary is--submit" data-trigger="attachment">{{"Upload Attachment"}}</button>
            <input type="file" name="attachment" accept="image/jpeg, image/jpg, image/png, application/pdf, text/plain" hidden/>
            <br/><br/>
        {% else: %}
            <label> 
                {{"Attachment"}}
            </label>
            <div class="alert is--info">{{"You have to verify your email address before you can send a message with an attachment."}}<br/>{{"<a href=\"%s/account/email\">Verify now</a> to gain full access to all features of this app.", $app->url}}</div>
        {% endif; %}
    {% endif; %}
    <p>{{"By sending your message, you agree to the processing of personal data. Don't worry, we will only use your email address to contact you."}}</p>
    <div class="response"></div>
    <button class="btn is--primary is--submit">{{"Send Message"}}</button>
</form>