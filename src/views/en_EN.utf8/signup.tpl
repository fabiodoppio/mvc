{% include header.tpl %}

<main class="signup">
    <section class="section is--light">
        <div class="container">
            <div class="main-content">
                <h2 class="title">Sign Up</h2>
                <form data-request="account/signup">
                    <label for="username">Username <span class="required" title="Mandatory">*</span><br>
                    <input type="text" id="username" name="username" placeholder="Enter username" autocomplete="off" required></label><br>
                    <label for="email">Email Address <span class="required" title="Mandatory">*</span><br>
                    <input type="email" id="email" name="email" placeholder="Enter email address" autocomplete="off" required></label><br>
                    <label for="pw1">Password <span class="required" title="Mandatory">*</span><br>
                    <input type="password" id="pw1" name="pw1" placeholder="Enter password" autocomplete="off" required></label><br>
                    <label for="pw2">Repeat Password <span class="required" title="Mandatory">*</span><br>
                    <input type="password" id="pw2" name="pw2" placeholder="Repeat password" autocomplete="off" required></label><br>
                    <div class="response"></div>
                    <button class="btn is--primary">Sign Up</button>
                    {% if (App::get("APP_LOGIN")): %}
                        <a href="{{App::get('APP_URL')}}/login" class="btn is--secondary">Log In</a>
                    {% endif; %}
                </form>
            </div>
        </div>
    </section>
</main>

{% include footer.tpl %}