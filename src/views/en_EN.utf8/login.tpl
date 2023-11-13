{% include header.tpl %}

<main class="page login">
    <section class="section is--light">
        <div class="container">
            <div class="main-content">
                <h2 class="title">Log In</h2>
                <form data-request="account/login">
                    <label for="credential">Username or Email Address <span class="required" title="Mandatory">*</span><br>
                    <input type="text" id="credential" name="credential" placeholder="Enter username or email" autocomplete="off" required/></label><br>
                    <label for="pw">Password <span class="required" title="Mandatory">*</span><br>
                    <input type="password" id="pw" name="pw" placeholder="Enter password" autocomplete="off" required/></label><br>
                    <a href="{{App::get('APP_URL')}}/recovery" title="Recover Account">Forgot your password?</a><br><br>
                    <label for="stay"><input type="checkbox" name="stay" id="stay" value="1">Stay logged in</label><br><br>
                    {% if (Request::isset("redirect")): %}
                        <input type="hidden" name="redirect" value="{{$redirect}}"/>
                    {% endif; %}
                    <div class="response"></div>
                    <button class="btn is--primary">Log In</button>
                    {% if (App::get("APP_SIGNUP")): %}
                            <a href="{{App::get('APP_URL')}}/signup" class="btn is--secondary">Sign Up</a>
                    {% endif; %}
                </form>
            </div>
        </div>
    </section>
</main>

{% include footer.tpl %}