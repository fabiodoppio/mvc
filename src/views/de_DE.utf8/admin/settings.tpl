{% include header.tpl %}

<main class="admin settings">
    <section class="section is--light">
        <div class="container">
            {% include admin/sidebar.tpl %}
            <div class="main-content">
                <h1 class="title">Einstellungen</h1>
                <p>Hier hast du die Möglichkeit Einstellungen deiner App vorzunehmen anzulegen, zu bearbeiten oder zu löschen.</p>
                <h2>App</h2>
                <form data-request="admin/settings/edit">
                    <label for="APP_URL">
                        App URL <span class="required" title="Pflichtfeld">*</span>
                        <input type="text" id="APP_URL" name="APP_URL" value="{{App::get('APP_URL')}}" placeholder="App URL eingeben" required/>
                    </label>
                    <label for="APP_NAME">
                        App Name <span class="required" title="Pflichtfeld">*</span>
                        <input type="text" id="APP_NAME" name="APP_NAME" value="{{App::get('APP_NAME')}}" placeholder="App Name" required/>
                    </label>
                    <label for="APP_TITLE">
                        Startseiten-Titel
                        <input type="text" id="APP_TITLE" name="APP_TITLE" value="{{App::get('APP_TITLE')}}" placeholder="Startseiten-Titel eingeben"/>
                    </label>
                    <label for="APP_AUTHOR"> 
                        App Author
                        <input type="text" id="APP_AUTHOR" name="APP_AUTHOR" value="{{App::get('APP_AUTHOR')}}" placeholder="App Autor eingeben"/>
                    </label>
                    <label for="APP_DESCRIPTION">
                        App Beschreibung
                        <input type="text" id="APP_DESCRIPTION" name="APP_DESCRIPTION" value="{{App::get('APP_DESCRIPTION')}}" placeholder="App Beschreibung eingeben"/>
                    </label>
                    <label for="APP_LANGUAGE"> 
                        App Sprache <span class="required" title="Pflichtfeld">*</span>
                        <input type="text" id="APP_LANGUAGE" name="APP_LANGUAGE" value="{{App::get('APP_LANGUAGE')}}" placeholder="App Sprache eingeben" required/>
                    </label>
                    <label for="APP_LOGIN">
                        Anmelden <span class="required" title="Pflichtfeld">*</span>
                        <select id="APP_LOGIN" name="APP_LOGIN" required>
                            <option value="1" {{(App::get('APP_LOGIN') == 1) ? "selected" : ""}}>Aktiviert</option>
                            <option value="0" {{(App::get('APP_LOGIN') == 0) ? "selected" : ""}}>Deaktiviert</option>
                        </select>
                    </label>
                    <label for="APP_SIGNUP">
                        Registrieren <span class="required" title="Pflichtfeld">*</span>
                        <select id="APP_SIGNUP" name="APP_SIGNUP" required>
                            <option value="1" {{(App::get('APP_SIGNUP') == 1) ? "selected" : ""}}>Aktiviert</option>
                            <option value="0" {{(App::get('APP_SIGNUP') == 0) ? "selected" : ""}}>Deaktiviert</option>
                        </select>
                    </label>
                    <label for="APP_MAINTENANCE">
                        Wartungsmodus <span class="required" title="Pflichtfeld">*</span>
                        <select id="APP_MAINTENANCE" name="APP_MAINTENANCE" required>
                            <option value="1" {{(App::get('APP_MAINTENANCE') == 1) ? "selected" : ""}}>Aktiviert</option>
                            <option value="0" {{(App::get('APP_MAINTENANCE') == 0) ? "selected" : ""}}>Deaktiviert</option>
                        </select>
                    </label>
                    <label for="META_PUBLIC"> 
                        Öffentliche Accountfelder 
                        <textarea id="META_PUBLIC" name="META_PUBLIC" placeholder="Accountfelder eingeben">{{implode("\n", json_decode(App::get('META_PUBLIC')))}}</textarea>
                    </label>
                    <label for="FILES_CSS">
                        Zusätzliche CSS-Dateien laden
                        <textarea id="FILES_CSS" name="FILES_CSS" placeholder="CSS-Dateien eingeben">{{implode("\n", json_decode(App::get('FILES_CSS')))}}</textarea>
                    </label>
                    <label for="FILES_JS">
                        Zusätzliche JS-Dateien laden
                        <textarea id="FILES_JS" name="FILES_JS" placeholder="JS-Dateien eingeben">{{implode("\n", json_decode(App::get('FILES_JS')))}}</textarea>
                    </label>
                    <br><br>
                    <h2>E-Mail</h2>
                    <label for="MAIL_HOST">
                        Hostname <span class="required" title="Pflichtfeld">*</span>
                        <input type="text" id="MAIL_HOST" name="MAIL_HOST" value="{{App::get('MAIL_HOST')}}" placeholder="Hostname eingeben" required/>
                    </label>
                    <label for="MAIL_SENDER">
                        Absender <span class="required" title="Pflichtfeld">*</span>
                        <input type="email" id="MAIL_SENDER" name="MAIL_SENDER" value="{{App::get('MAIL_SENDER')}}" placeholder="Absender eingeben" required/>
                    </label>
                    <label for="MAIL_USERNAME">
                        Benutzername <span class="required" title="Pflichtfeld">*</span>
                        <input type="text" id="MAIL_USERNAME" name="MAIL_USERNAME" value="{{App::get('MAIL_USERNAME')}}" placeholder="Benutzername eingeben" required/>
                    </label>
                    <label for="MAIL_PASSWORD">
                        Passwort <span class="required" title="Pflichtfeld">*</span>
                        <input type="password" id="MAIL_PASSWORD" name="MAIL_PASSWORD" value="" placeholder="Neues Passwort eingeben"/>
                    </label>
                    <br><br>
                    <h2>Cron</h2>
                    <label for="APP_CRONJOB">
                        Cronjob <span class="required" title="Pflichtfeld">*</span>
                        <select id="APP_CRONJOB" name="APP_CRONJOB" required>
                            <option value="1" {{(App::get('APP_CRONJOB') == 1) ? "selected" : ""}}>Aktiviert</option>
                            <option value="0" {{(App::get('APP_CRONJOB') == 0) ? "selected" : ""}}>Deaktiviert</option>
                        </select>
                    </label>
                    <label> 
                        URL 
                        <input type="text" value="{{App::get('APP_URL')}}/cron?key={{str_replace('=', '', base64_encode(App::get('AUTH_CRON')))}}" disabled/>
                    </label>
                    <br><br>
                    <h2>Zusätzliche Einstellungen</h2>
                    {% $index = 0; %}
                    {% $ignore = "'APP_URL', 'APP_NAME', 'APP_TITLE', 'APP_AUTHOR', 'APP_DESCRIPTION', 'APP_LANGUAGE', 'META_PUBLIC', 'FILES_JS', 'FILES_CSS', 'APP_LOGIN', 'APP_MAINTENANCE', 'APP_SIGNUP', 'MAIL_HOST', 'MAIL_SENDER', 'MAIL_USERNAME', 'MAIL_PASSWORD', 'APP_CRONJOB'"; %}
                    {% foreach (Database::select("app_config", "name NOT IN (".$ignore.")") as $config): %}
                        <label for="config_value[{{$index}}]">{{$config['name']}}
                            <input type="hidden" name="config_name[{{$index}}]" value="{{$config['name']}}"/>
                            <input type="text" id="config_value[{{$index}}]" name="config_value[{{$index}}]" value="{{$config['value']}}" placeholder="Wert eingeben"/>
                        </label>
                        {% $index++; %}
                    {% endforeach; %}
                    <label for="config_value[{{$index}}]">
                        <input type="text" name="config_name[{{$index}}]" placeholder="Name eingeben"/>
                        <input type="text" id="config_value[{{$index}}]" name="config_value[{{$index}}]" placeholder="Wert eingeben"/>
                    </label>
                    <br><br>
                    <button class="btn is--primary" title="Änderungen speichern">Änderungen speichern</button> <a data-request="admin/cache/clear">Cache leeren</a>
                </form>
            </div>
        </div>
    </section>
    <div class="response"></div>
</main>

{% include footer.tpl %}