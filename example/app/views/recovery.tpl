{% include /_includes/header.tpl %}
        {% include /_includes/topbar.tpl %}

        <main class="is--fading">
            <div class="container">
                <div class="main-content">
                    <h1 class="title">{{"Account Recovery"}}</h1>
                    <form data-request="account/recovery">
                        <label for="credential">
                            {{"Username or Email Address"}} <span class="is--required" title="{{'Required'}}">*</span>
                            <input type="text" id="credential" name="credential" maxlength="64" placeholder="{{'Enter username or email address'}}" autocomplete="username email" required/>
                        </label>
                        <button class="btn is--primary is--submit">{{"Request"}}</button>
                        <a href="{{$app->url}}/login" class="btn is--secondary">{{"Back"}}</a>
                    </form>
                </div>
            </div>
        </main>

{% include /_includes/footer.tpl %}