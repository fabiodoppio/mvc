{% include header.tpl %}

        <main class="account security">
            <section class="section is--light">
                <div class="container">
                    {% include account/sidebar.tpl %}
                    <div class="main-content">
                        <h1 class="title">Passwort & Sicherheit</h1>
                        <form data-request="user/edit">
                            <h2>Anmeldedaten</h2>
                            {% if (in_array("username", json_decode(App::get("META_PUBLIC")))): %}
                                <label for="username">Benutzername<br>
                                <input type="text" id="username" name="username" value="{{$account->get('username')}}" placeholder="Benutzername eingeben"/></label><br>
                            {% endif; %}
                            {% if (in_array("password", json_decode(App::get("META_PUBLIC")))): %}
                                <label for="pw">Aktuelles Passwort<br>
                                <input type="password" id="pw" name="pw" placeholder="Aktuelles Passwort eingeben"/></label><br>
                                <label for="pw1">Neues Passwort<br>
                                <input type="password" id="pw1" name="pw1" placeholder="Neues Passwort eingeben"/></label><br>
                                <label for="pw2">Neues Passwort wiederholen<br>
                                <input type="password" id="pw2" name="pw2" placeholder="Neues Passwort wiederholen"/></label><br>
                            {% endif; %}
                            <div class="response"></div>
                            <button>Änderungen speichern</button>
                        </form>
                        <form data-request="account/glogout">
                            <h2>Überall abmelden</h2>
                            <p>Melde dich überall ab, wo dein Account sonst noch verwendet wird, einschließlich aller anderen Browser, Telefone und sonstigen Geräte.</p>
                            <button>Andere Sitzungen abmelden</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>

{% include footer.tpl %}