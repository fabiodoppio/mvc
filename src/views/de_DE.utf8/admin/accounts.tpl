{% include header.tpl %}

<main class="admin accounts">
    <section class="section is--light">
        <div class="container">
            {% include admin/sidebar.tpl %}
            <div class="main-content">
                <h1 class="title">Accounts</h1>
                <p>Hier hast du die Möglichkeit Accounts anzulegen, zu bearbeiten oder zu löschen.</p>
                <h2>Registrierte Accounts</h2>
                {% include admin/elements/AccountList.tpl %}
                <br>
                <h2>Neuen Account hinzufügen</h2>
                <form data-request="admin/account/add">
                    <label for="username">
                        Benutzername <span class="required" title="Pflichtfeld">*</span>
                        <input type="text" id="username" name="username" placeholder="Benutzername eingeben" required/>
                    </label>
                    <label for="email">
                        E-Mail Adresse <span class="required" title="Pflichtfeld">*</span>
                        <input type="email" id="email" name="email" placeholder="E-Mail Adresse eingeben" required/>
                    </label>
                    <label for="role"> 
                        Rolle <span class="required" title="Pflichtfeld">*</span>
                        <select name="role" id="role" required> 
                            <option value="1">Gesperrt</option>
                            <option value="2">Deaktiviert</option>
                            <option value="3">Besucher:in</option>
                            <option value="4" selected>Benutzer:in</option>
                            <option value="5">Verifiziert</option>
                            <option value="6">Moderator:in</option>
                            <option value="7">Administrator:in</option>
                        </select>
                    </label>
                    <label for="pw1">
                        Passwort <span class="required" title="Pflichtfeld">*</span>
                        <input type="password" name="pw1" placeholder="Passwort eingeben" required/>
                    </label>
                    <label for="pw2">
                        Passwort wiederholen <span class="required" title="Pflichtfeld">*</span>
                        <input type="password" name="pw2" placeholder="Passwort wiederholen" required/>
                    </label>
                    <br><br>
                    <button class="btn is--primary">Account hinzufügen</button>
                </form>
            </div>
        </div>
    </section>
    <div class="response"></div>
</main>

{% include footer.tpl %}