{% include /header.tpl %} 
        {% include /topbar.tpl %}

        <main class="account signup">
            <section class="section is--light">
                <div class="container">
                    <div class="main-content">
                    <h2 class="title">{{"Signup"}}</h1>
                        <form data-request="account/signup">
                            <label for="firstname">
                                <input type="text" id="firstname" name="firstname" tabindex="-1"/>
                            </label>
                            <label for="username">
                                {{"Username"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your username must be between 3 and 18 characters long<br>and cannot contain any special characters"}}</span></div>
                                <input type="text" id="username" name="username" minlength="3" maxlength="18" placeholder="{{'Enter username'}}" autocomplete="off" required/>
                            </label>
                            <label for="email">
                                {{"Email Address"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"In the next step you will have to verify your email address"}}</span></div>
                                <input type="email" id="email" name="email" maxlength="64" placeholder="{{'Enter email address'}}" autocomplete="off" required/>
                            </label>
                            <label for="pw1">
                                {{"Password"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your Password must be at least 8 characters long"}}</span></div>
                                <input type="password" id="pw1" name="pw1" minlength="8" maxlength="64" placeholder="{{'Enter password'}}" autocomplete="off" required/>
                            </label>
                            <label for="pw2">{{"Repeat Password"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your Password must be at least 8 characters long"}}</span></div>
                                <input type="password" id="pw2" name="pw2" minlength="8" maxlength="64" placeholder="{{'Repeat password'}}" autocomplete="off" required/>
                            </label>
                            <label for="confirm">
                                <input type="checkbox" name="confirm" id="confirm" value="1" required>{{"I agree to the Terms of Service and Privacy Policy."}} <span class="required" title="{{'Required'}}">*</span>
                            </label><br><br>
                            {% if ($request->redirect != ""): %}
                                <input type="hidden" name="redirect" value="{{$request->redirect}}"/>
                            {% endif; %}
                            <div class="response"></div>
                            <button class="btn is--primary">{{"Sign Up"}}</button>
                            {% if ($app->login): %}
                                <a href="{{$config->url}}/login" class="btn is--secondary">{{"Log In"}}</a>
                            {% endif; %}
                        </form>
                    </div>
                </div>
            </section>
        </main>

        {% include /footer.tpl %} 
