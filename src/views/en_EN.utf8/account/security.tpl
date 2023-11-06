{% include header.tpl %}

<main class="account security">
    <section class="section is--light">
        <div class="container">
            {% include account/sidebar.tpl %}
            <div class="main-content">
                <h1 class="title">Password & Security</h1>
                <form data-request="user/security">
                    <h2>Login Information</h2>
                    {% if (in_array("username", json_decode(App::get("META_PUBLIC")))): %}
                        <label for="username">Username<br>
                        <input type="text" id="username" name="username" value="{{$account->get('username')}}" placeholder="Enter username"/></label><br>
                    {% endif; %}
                    {% if (in_array("password", json_decode(App::get("META_PUBLIC")))): %}
                        <label for="pw">Current Password<br>
                        <input type="password" id="pw" name="pw" placeholder="Enter current password"/></label><br>
                        <label for="pw1">New Password<br>
                        <input type="password" id="pw1" name="pw1" placeholder="Enter new password"/></label><br>
                        <label for="pw2">Confirm New Password<br>
                        <input type="password" id="pw2" name="pw2" placeholder="Confirm new password"/></label><br>
                    {% endif; %}
                    <div class="response"></div>
                    <button>Save Changes</button>
                </form>
                <form data-request="user/glogout">
                    <h2>Sign Out Everywhere</h2>
                    <p>Sign out from all other sessions where your account is used, including all other browsers, phones, and devices.</p>
                    <button>Sign Out Other Sessions</button>
                </form>
            </div>
        </div>
    </section>
</main>

{% include footer.tpl %}