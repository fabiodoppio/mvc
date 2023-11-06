{% include header.tpl %}

<main class="admin pages">
    <section class="section is--light">
        <div class="container">
            {% include admin/sidebar.tpl %}
            <div class="main-content">
                <h1 class="title">Seiten</h1>
                <p>Hier hast du die Möglichkeit Seiten anzulegen, zu bearbeiten oder zu löschen.</p>
                <h2>Eigene Seiten</h2>
                {% include admin/elements/PageList.tpl %}
                <br>
                <h2>Neue Seite hinzufügen</h2>
                <form data-request="admin/page/add">
                    <label for="title">
                        Titel <span class="required" title="Pflichtfeld">*</span>
                        <input type="text" id="title" name="title" placeholder="Titel eingeben" required/>
                    </label>
                    <label for="slug">
                        URL-Slug <span class="required" title="Pflichtfeld">*</span>
                        <input type="text" id="slug" name="slug" placeholder="URL-Slug eingeben" required/>
                    </label>
                    <label for="description">
                        Beschreibung
                        <input type="text" id="description" name="description" placeholder="Beschreibung eingeben"/>
                    </label>
                    <label for="robots">
                        Robots
                        <input type="text" id="robots" name="robots" placeholder="Robots eingeben"/>
                    </label>
                    <label for="template">
                        Template-Pfad <span class="required" title="Pflichtfeld">*</span>
                        <input type="text" id="template" name="template" placeholder="Template-Pfad eingeben" required/>
                    </label>
                    <label for="role">
                        Mindestanforderung <span class="required" title="Pflichtfeld">*</span>
                        <select name="role" required>
                            <option value="1">Gesperrt</option>
                            <option value="2">Deaktiviert</option>
                            <option value="3" selected>Besucher:in</option>
                            <option value="4">Benutzer:in</option>
                            <option value="5">Verifiziert</option>
                            <option value="6">Moderator:in</option>
                            <option value="7">Administrator:in</option>
                        </select>
                    </label>
                    <br><br>
                    <button class="btn is--primary">Seite hinzufügen</button>
                </form>
            </div>
        </div>
    </section>
    <div class="response"></div>
</main>

{% include footer.tpl %}