{% include /_includes/header.tpl %} 
        {% include /_includes/topbar.tpl %} 

        <main>
            <div class="container">
                {% include /_includes/sidebar.tpl %}
                <div class="main-content is--fading">
                    <h1 class="title">{{"Account & Security"}}</h1>
                    <p>{{"Manage your login credentials and security settings. These data are extremely sensitive and will <b>never</b> be shown to other users on this platform. An employee will never ask for your password!"}}</p>
                    <form data-request="account/security/edit">
                        <h2>{{"Login Credentials"}}</h2>
                        <label for="username">
                            {{"Username"}} <span class="is--required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your username must be between 3 and 18 characters long<br/>and cannot contain any special characters."}}</span></div>
                            <input type="text" id="username" minlength="3" maxlength="18" name="username" value="{{$account->username}}" placeholder="{{'Enter username'}}"  autocomplete="username" required disabled/>
                        </label>
                        <label for="pw">
                            {{"Current Password"}} <span class="is--required" title="{{'Required'}}">*</span>
                            <input type="password" id="pw" name="pw" maxlength="64" placeholder="{{'Enter current password'}}" autocomplete="current-password" required/>
                        </label>
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
                        <button class="btn is--primary is--submit">{{"Save Changes"}}</button>
                    </form>
                    <h2>{{"2-Factor Authentication"}}</h2>
                    <p>{{"The 2-Factor authentication is a security function for your account. If you use this, a PIN code will be sent to your email address everytime you log in from a new device or browser."}}</p>
                    <form data-request="account/security/2fa">
                        {% if ($account->role < $account->roles->verified): %}
                            <div class="alert is--error"><i class="fas fa-shield-halved"></i> {{"Your account is <b>not</b> protected by the 2-Factor Authentication."}}</div><br/>
                            <div class="alert is--info">{{"You have to verify your email address before you can acitvate the 2-Factor Authentication."}} {{"<a href=\"%s/account/email\">Verify now</a> to gain full access to all features of this app.", $app->url}}</div>                    
                        {% else: %}  
                            {% if (!empty($account->meta->{'2fa'})): %}
                                <div class="alert is--success"><i class="fas fa-shield-heart"></i> {{"Your account is protected by the 2-Factor Authentication."}}</div>
                                <button class="btn is--primary is--submit">{{"Deactivate"}}</button>
                            {% else: %}
                                <div class="alert is--error"><i class="fas fa-shield-halved"></i> {{"Your account is <b>not</b> protected by the 2-Factor Authentication."}}</div>
                                <button class="btn is--primary is--submit">{{"Activate"}}</button>
                            {% endif; %}
                        {% endif; %}
                    </form>
                    <h2>{{"Sign Out Everywhere"}}</h2>
                    <form data-request="account/security/logout">
                        <p>{{"Sign out from all other sessions where your account is used, including all other browsers, phones, and devices."}}</p>
                        <button class="btn is--primary is--submit">{{"Sign Out Other Sessions"}}</button>
                    </form>
                    <h2>{{"Deactivate Account"}}</h2>
                    <p>{{"When you deactivate your account you have 90 days to recover your account before it will be permanently deleted from our servers."}}</p>
                    <button class="btn is--warning is--submit" data-trigger="modalbox">{{"Deactivate Account"}}</button>
                    <div class="modalbox">
                        <div>
                            <h3>{{"Are you sure?"}}</h3>
                            <p>{{"You will be logged out immediately and will no longer have access to your account."}}</p>
                            <button class="btn is--warning is--submit" data-request="account/security/deactivate">{{"Deactivate Account"}}</button>
                            <button class="btn is--secondary">{{"Abort"}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

{% include /_includes/footer.tpl %}