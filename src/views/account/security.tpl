{% include /_includes/header.tpl %} 
        {% include /_includes/topbar.tpl %} 

        <main class="account security">
            <section class="section is--light">
                <div class="container">
                    {% include /_includes/sidebar.tpl %}
                    <div class="main-content">
                        <h1 class="title">{{"Account & Security"}}</h1>
                        <p>{{"Manage your login credentials and security settings."}}</p>
                        {% if ($account->role < 5): %}
                            <span class="info">
                                {{"Your email address is not verified."}} {{"<a href=\"%s/account/email\">Verify now</a> to gain full access to all features of this app.", $app->url}}                           
                            </span>
                        {% endif; %}
                        <form data-request="account/security/edit">
                            <h2 class="title">{{"Login Credentials"}}</h2>
                            <label for="username">
                                {{"Username"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your username must be between 3 and 18 characters long<br>and cannot contain any special characters"}}</span></div>
                                <input type="text" id="username" minlength="3" maxlength="18" name="username" value="{{$account->username}}" placeholder="{{'Enter username'}}" required disabled/>
                            </label>
                            <label for="pw">
                                {{"Current Password"}}
                                <input type="password" id="pw" name="pw" maxlength="64" placeholder="{{'Enter current password'}}"/>
                            </label>
                            <label for="pw1">
                                {{"New Password"}}
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your password must be at least 8 characters long"}}</span></div>
                                <input type="password" id="pw1" name="pw1" minlength="8" maxlength="64" placeholder="{{'Enter new password'}}"/>
                            </label>
                            <label for="pw2">
                                {{"Repeat New Password"}}
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your password must be at least 8 characters long"}}</span></div>
                                <input type="password" id="pw2" name="pw2" minlength="8" maxlength="64" placeholder="{{'Repeat new password'}}"/>
                            </label>
                            <br><br>
                            <div class="response"></div>
                            <button class="btn is--primary">{{"Save Changes"}}</button>
                        </form>
                        <br><br> 
                        <h2 class="title">{{"Sign Out Everywhere"}}</h2>
                        <form data-request="account/security/logout">
                            <p>{{"Sign out from all other sessions where your account is used, including all other browsers, phones, and devices."}}</p>
                            <br><br>
                            <div class="response"></div>
                            <button class="btn is--primary">{{"Sign Out Other Sessions"}}</button>
                        </form>
                        <br><br> 
                        <h2 class="title">{{"Deactivate Account"}}</h2>
                        <form data-request="account/security/deactivate">
                            <p>{{"When you deactivate your account you have 90 days to recover your account before it will be permanently deleted from our servers."}}</p>
                            <br><br>
                            <div class="response"></div>
                            {{"Are you sure?"}}
                            <button class="btn is--primary">{{"Deactivate Account"}}</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>

{% include /_includes/footer.tpl %}