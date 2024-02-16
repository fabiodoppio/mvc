{% include /header.tpl %} 
        {% include /topbar.tpl %}

        <main class="account recovery">
            <section class="section is--light">
                <div class="container">
                    <div class="main-content">
                        <h2 class="title">{{"Recover Account"}}</h1>
                        {% if ($request->credential != ""): %}
                            <form data-request="account/recovery/submit">
                                <input type="hidden" name="credential" value="{{$request->credential}}" required/>
                                {% if ($request->code == ""): %}
                                    <div class="success">{{"We've sent you a <b>confirmation code</b> to reset your password to the registered <b>Email Address</b>."}}</div>
                                {% endif; %}
                                <label for="code">
                                    {{"Confirmation Code"}} <span class="required" title="{{'Required'}}">*</span>
                                    <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"You can find the 9-digit confirmation code in the email we sent you"}}</span></div>
                                    <input type="text" id="code" name="code" value="{{$request->code}}" maxlength="11" placeholder="{{'Enter confirmation code'}}" required/>
                                </label><br><br>                            
                                <label for="pw1">
                                    {{"New Password"}} <span class="required" title="{{'Required'}}">*</span>
                                    <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your password must be at least 8 characters long"}}</span></div>
                                    <input type="password" id="pw1" name="pw1" minlength="8" maxlength="64" placeholder="{{'Enter new password'}}" required/>
                                </label>
                                <label for="pw2">
                                    {{"Repeat New Password"}} <span class="required" title="{{'Required'}}">*</span>
                                    <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your password must be at least 8 characters long"}}</span></div>
                                    <input type="password" id="pw2" name="pw2" minlength="8" maxlength="64" placeholder="{{'Repeat new password'}}" required/>
                                </label><br><br>
                                <div class="response"></div>
                                <button class="btn is--primary">{{"Recover Account"}}</button>
                            </form>
                        {% else: %}
                            <form data-request="account/recovery/request">
                                <div class="warning">{{"We will send you a <b>confirmation code</b> to reset your password to the registered <b>Email Address</b>."}}</div><br>
                                <label for="credential">
                                    {{"Username or Email Address"}} <span class="required" title="{{'Required'}}">*</span>
                                    <input type="text" id="credential" name="credential" maxlength="64" placeholder="{{'Enter username or email address'}}" required/>
                                </label><br><br>
                                <div class="response"></div>
                                <button class="btn is--primary">{{"Request Confirmation Code"}}</button>
                            </form>
                        {% endif; %}  
                    </div>
                </div>
            </section>
        </main>

        {% include /footer.tpl %} 
