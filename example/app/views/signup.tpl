{% include /_includes/header.tpl %}
        {% include /_includes/topbar.tpl %}

        <main class="is--fading">
            <div class="container">
                <div class="main-content">
                    <h1 class="title">{{"Sign Up"}}</h1>
                    <form data-request="account/signup">
                        {% if ($request->get->redirect??"" != ""): %}
                            <input type="hidden" name="redirect" value="{{$request->get->redirect}}"/>
                        {% endif; %}
                        <label for="firstname">
                            <input type="text" id="firstname" name="firstname" autocomplete="off" tabindex="-1"/>
                        </label>
                        <label for="username">
                            {{"Username"}} <span class="is--required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your username must be between 3 and 18 characters long<br/>and cannot contain any special characters."}}</span></div>
                            <input type="text" id="username" name="username" minlength="3" maxlength="18" placeholder="{{'Enter username'}}" autocomplete="off" required/>
                        </label>
                        <label for="email">
                            {{"Email Address"}} <span class="is--required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"You will have to verify your email address to<br/>gain full access to all features of this app."}}</span></div>
                            <input type="email" id="email" name="email" maxlength="64" placeholder="{{'Enter email address'}}" autocomplete="off" required/>
                        </label>
                        <label for="pw1">
                            {{"Password"}} <span class="is--required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your password must be at least 8 characters long."}}</span></div>
                            <input type="password" id="pw1" name="pw1" minlength="8" maxlength="64" placeholder="{{'Enter password'}}" autocomplete="off" required/>
                            </label>
                        <label for="pw2">{{"Repeat Password"}} <span class="is--required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your password must be at least 8 characters long."}}</span></div>
                            <input type="password" id="pw2" name="pw2" minlength="8" maxlength="64" placeholder="{{'Repeat password'}}" autocomplete="off" required/>
                        </label>
                        <label for="confirm">
                            <input type="checkbox" name="confirm" id="confirm" value="1" required>{{"I agree to the Terms of Service and Privacy Policy."}} <span class="is--required" title="{{'Required'}}">*</span>
                        </label>
                        <button class="btn is--primary is--submit">{{"Sign Up"}}</button>
                        {% if ($app->login): %}
                            {% if ($request->get->redirect??""): %}
                                    <a href="{{$app->url}}/login?redirect={{urlencode($request->get->redirect)}}" class="btn is--secondary">{{"Log In"}}</a>
                            {% else: %}
                                <a href="{{$app->url}}/login" class="btn is--secondary">{{"Log In"}}</a>
                            {% endif; %}
                        {% endif; %}
                    </form>
                </div>
            </div>
        </main>

{% include /_includes/footer.tpl %}