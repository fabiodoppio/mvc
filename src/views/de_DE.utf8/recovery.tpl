{% include header.tpl %}

    <main class="page recovery">
        <section class="section is--light">
            <div class="container">
                <div class="main-content">
                    <h2 class="title">Account wiederherstellen</h1>
                    {% if ($credential != ""): %}
                        <form data-request="account/recovery/submit">
                            <input type="hidden" name="credential" value="{{$credential}}" required/>
                            {% if ($code == ""): %}
                                <div class="success">Wir haben dir einen <b>Bestätigungscode</b> zum Zurücksetzen deines Passwortes an die hinterlegte <b>E-Mail Adresse</b> geschickt.</div>
                            {% endif; %}
                            <label for="code">Bestätigungscode <span class="required" title="Pflichtfeld">*</span><br>
                            <input type="text" id="code" name="code" value="{{$code}}" placeholder="Bestätigungscode eingeben" required/></label><br><br>                            
                            <label for="pw1">Neues Passwort <span class="required" title="Pflichtfeld">*</span><br>
                            <input type="password" id="pw1" name="pw1" placeholder="Neues Passwort eingeben"/></label><br>
                            <label for="pw2">Neues Passwort bestätigen <span class="required" title="Pflichtfeld">*</span><br>
                            <input type="password" id="pw2" name="pw2" placeholder="Neues Passwort bestätigen"/></label><br><br>
                            <div class="response"></div>
                            <button>Account wiederherstellen</button>
                        </form>
                    {% else: %}
                        <form data-request="account/recovery/request">
                            <div class="warning">Wir senden dir einen <b>Bestätigungscode</b> zum Zurücksetzen deines Passwortes an die hinterlegte <b>E-Mail Adresse</b>.</div><br>
                            <label for="credential">Benutzername oder E-Mail Adresse <span class="required" title="Pflichtfeld">*</span><br>
                            <input type="text" id="credential" name="credential" placeholder="Benutzername oder E-Mail Adresse eingeben" required/></label><br><br>
                            <div class="response"></div>
                            <button>Bestätigungscode anfordern</button>
                        </form>
                    {% endif; %}  
                </div>
            </div>
        </section>
    </main>

{% include footer.tpl %}