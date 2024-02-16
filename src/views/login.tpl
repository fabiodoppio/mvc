{% include /header.tpl %} 
        {% include /topbar.tpl %}

        <main class="account login">
            <section class="section is--light">
                <div class="container">
                    <div class="main-content">
                    <h2 class="title">{{"Log In"}}</h1>
                        <form data-request="account/login">
                            <label for="credential">
                                {{"Username or Email Address"}} <span class="required" title="{{'Required'}}">*</span>
                                <input type="text" id="credential" name="credential" maxlength="64" placeholder="{{'Enter username or email address'}}" autocomplete="off" required/>
                            </label>
                            <label for="pw">
                                {{"Password"}} <span class="required" title="{{'Required'}}">*</span>
                                <input type="password" id="pw" name="pw" maxlength="64" placeholder="{{'Enter password'}}" autocomplete="off" required/>
                            </label>
                            <a href="{{$app->url}}/recovery" title="{{'Recover account'}}">{{"Forgot your password?"}}</a><br><br>
                            <label for="stay">
                                <input type="checkbox" name="stay" id="stay" value="1">{{"Stay logged in"}}
                            </label><br><br>
                            {% if ($request->get->redirect??""): %}
                                <input type="hidden" name="redirect" value="{{$request->get->redirect}}"/>
                            {% endif; %}
                            <div class="response"></div>
                            <button class="btn is--primary">{{"Log In"}}</button>
                            {% if ($app->signup): %}
                                <a href="{{$app->url}}/signup" class="btn is--secondary">{{"Sign Up"}}</a>
                            {% endif; %}
                        </form>
                    </div>
                </div>
            </section>
        </main>

        {% include /footer.tpl %} 
