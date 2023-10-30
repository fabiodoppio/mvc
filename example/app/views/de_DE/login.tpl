{% include header.tpl %}

    <main class="login">
        <section class="section is--light">
            <div class="container">
                <div class="main-content">
                <h2 class="title">Anmelden</h1>
                    <form data-request="account/login">
                        <label for="credential">Benutzername oder E-Mail Adresse <span class="required" title="Pflichtfeld">*</span><br>
                        <input type="text" name="credential" placeholder="Benutzername eingeben" autocomplete="off" required/></label><br>
                        <label for="pw">Passwort <span class="required" title="Pflichtfeld">*</span><br>
                        <input type="password" name="pw" placeholder="Passwort eingeben" autocomplete="off" required/></label><br>
                        <a href="{{App::get('APP_URL')}}/recovery" title="Account wiederherstellen">Passwort vergessen?</a><br><br>
                        <label for="stay"><input type="checkbox" name="stay" id="stay" value="1">Angemeldet bleiben</label><br><br>
                        {% if (Request::isset("redirect")): %}
                            <input type="hidden" name="redirect" value="{{Request::get('redirect')}}"/>
                        {% endif; %}
                        <div class="response"></div>
                        <button>Anmelden</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

{% include footer.tpl %}