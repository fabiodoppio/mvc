{% include /_includes/header.tpl %}
        {% include /_includes/topbar.tpl %}

        <main>
            <div class="container">
                <div class="main-content is--fading">
                    <h1 class="title">{{"Log In"}}</h1>
                    <form data-request="account/login">
                        <label for="credential">
                            {{"Username or Email Address"}} <span class="is--required" title="{{'Required'}}">*</span>
                            <input type="text" id="credential" name="credential" maxlength="64" placeholder="{{'Enter username or email address'}}" autocomplete="username email" required/>
                        </label>
                        <label for="pw">
                            {{"Password"}} <span class="is--required" title="{{'Required'}}">*</span>
                            <input type="password" id="pw" name="pw" maxlength="64" placeholder="{{'Enter password'}}" autocomplete="current-password" required/>
                            <a href="{{$app->url}}/recovery" title="{{'Recover account'}}">{{"Forgot your password?"}}</a>
                        </label>
                        <label for="remember">
                            <input type="checkbox" name="remember" id="remember" value="1">{{"Remember me!"}}
                        </label>
                        {% if (!empty($request->get->redirect)): %}
                            <input type="hidden" name="redirect" value="{{$request->get->redirect}}"/>
                        {% endif; %}
                        <button class="btn is--primary is--submit">{{"Log In"}}</button>
                        {% if ($app->signup): %}
                            {% if (!empty($request->get->redirect)): %}
                                <a href="{{$app->url}}/signup?redirect={{urlencode($request->get->redirect)}}" class="btn is--secondary">{{"Sign Up"}}</a>
                            {% else: %}
                                <a href="{{$app->url}}/signup" class="btn is--secondary">{{"Sign Up"}}</a>
                            {% endif; %}
                        {% endif; %}
                    </form>
                </div>
            </div>
        </main>

{% include /_includes/footer.tpl %}