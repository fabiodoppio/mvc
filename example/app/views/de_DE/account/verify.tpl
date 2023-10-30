{% include header.tpl %}

    <main class="verify">
        <section class="section is--light">
            <div class="container">
                <div class="main-content">
                    <h2 class="title">E-Mail Adresse verifizieren</h1>
                    {% if ($email != ""): %}
                        <form data-request="user/verify/submit">
                            {% if ($code == ""): %}
                                <div class="success">Wir haben dir einen <b>Bestätigungscode</b> zum Zurücksetzen deines Passwortes an die hinterlegte <b>E-Mail Adresse</b> geschickt.</div>
                            {% endif; %}
                            <label for="code">Bestätigungscode <span class="required" title="Pflichtfeld">*</span><br>
                            <input type="text" name="code" value="{{$code}}" placeholder="Bestätigungscode eingeben" required/></label><br><br>
                            {% if (Request::isset("redirect")): %}
                                <input type="hidden" name="redirect" value="{{Request::get('redirect')}}"/>
                            <div class="response"></div>
                            <button>E-Mail Adresse verifizieren</button>
                        </form>
                    {% else: %}
                        <form data-request="user/verify/request">
                            <div class="warning">Wir senden dir einen <b>Bestätigungscode</b> zum Verifizieren an die hinterlegte <b>E-Mail Adresse</b>.</div><br>
                            <br><br>
                            {% if (Request::isset("redirect")): %}
                                <input type="hidden" name="redirect" value="{{Request::get('redirect')}}"/>
                            <div class="response"></div>
                            <button>Bestätigungscode anfordern</button>
                        </form>
                    {% endif; %}  
                </div>
            </div>
        </section>
    </main>

{% include footer.tpl %}