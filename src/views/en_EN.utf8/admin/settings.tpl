{% include header.tpl %}

<main class="admin settings">
    <section class="section is--light">
        <div class="container">
            {% include admin/sidebar.tpl %}
            <div class="main-content">
                <h1 class="title">Settings</h1>
                <p>Here, you have the ability to create, edit, or delete settings for your app.</p>
                <h2>App</h2>
                <form data-request="admin/settings/edit">
                    <label for="APP_DEBUG">
                        Environment <span class="required" title="Pflichtfeld">*</span>
                        <select id="APP_DEBUG" name="APP_DEBUG" required>
                            <option value="1" {{(App::get('APP_DEBUG') == 1) ? "selected" : ""}}>Development</option>
                            <option value="0" {{(App::get('APP_DEBUG') == 0) ? "selected" : ""}}>Production</option>
                        </select>
                    </label>
                    <label for="APP_URL">
                        App URL <span class="required" title="Mandatory">*</span>
                        <input type="text" id="APP_URL" name="APP_URL" value="{{App::get('APP_URL')}}" placeholder="Enter app URL" required/>
                    </label>
                    <label for="APP_NAME">
                        App Name <span class="required" title="Mandatory">*</span>
                        <input type="text" id="APP_NAME" name="APP_NAME" value="{{App::get('APP_NAME')}}" placeholder="App Name" required/>
                    </label>
                    <label for="APP_TITLE">
                        Homepage Title
                        <input type="text" id="APP_TITLE" name="APP_TITLE" value="{{App::get('APP_TITLE')}}" placeholder="Enter homepage title"/>
                    </label>
                    <label for="APP_AUTHOR"> 
                        App Author
                        <input type="text" id="APP_AUTHOR" name="APP_AUTHOR" value="{{App::get('APP_AUTHOR')}}" placeholder="Enter app author"/>
                    </label>
                    <label for="APP_DESCRIPTION">
                        App Description
                        <input type="text" id="APP_DESCRIPTION" name="APP_DESCRIPTION" value="{{App::get('APP_DESCRIPTION')}}" placeholder="Enter app description"/>
                    </label>
                    <label for="APP_LANGUAGE"> 
                        App Language <span class="required" title="Mandatory">*</span>
                        <input type="text" id="APP_LANGUAGE" name="APP_LANGUAGE" value="{{App::get('APP_LANGUAGE')}}" placeholder="Enter app language" required/>
                    </label>
                    <label for="APP_LOGIN">
                        Log In <span class="required" title="Mandatory">*</span>
                        <select id="APP_LOGIN" name="APP_LOGIN" required>
                            <option value="1" {{(App::get('APP_LOGIN') == 1) ? "selected" : ""}}>Enabled</option>
                            <option value="0" {{(App::get('APP_LOGIN') == 0) ? "selected" : ""}}>Disabled</option>
                        </select>
                    </label>
                    <label for="APP_SIGNUP">
                        Sign Up <span class="required" title="Mandatory">*</span>
                        <select id="APP_SIGNUP" name="APP_SIGNUP" required>
                            <option value="1" {{(App::get('APP_SIGNUP') == 1) ? "selected" : ""}}>Enabled</option>
                            <option value="0" {{(App::get('APP_SIGNUP') == 0) ? "selected" : ""}}>Disabled</option>
                        </select>
                    </label>
                    <label for="APP_MAINTENANCE">
                        Maintenance Mode <span class="required" title="Mandatory">*</span>
                        <select id="APP_MAINTENANCE" name="APP_MAINTENANCE" required>
                            <option value="1" {{(App::get('APP_MAINTENANCE') == 1) ? "selected" : ""}}>Enabled</option>
                            <option value="0" {{(App::get('APP_MAINTENANCE') == 0) ? "selected" : ""}}>Disabled</option>
                        </select>
                    </label>
                    <label for="META_PUBLIC"> 
                        Public Account Fields 
                        <textarea id="META_PUBLIC" name="META_PUBLIC" placeholder="Enter account fields">{{implode("\n", json_decode(App::get('META_PUBLIC')))}}</textarea>
                    </label>
                    <label for="FILES_CSS">
                        Load Additional CSS Files
                        <textarea id="FILES_CSS" name="FILES_CSS" placeholder="Enter CSS files">{{implode("\n", json_decode(App::get('FILES_CSS')))}}</textarea>
                    </label>
                    <label for="FILES_JS">
                        Load Additional JS Files
                        <textarea id="FILES_JS" name="FILES_JS" placeholder="Enter JS files">{{implode("\n", json_decode(App::get('FILES_JS')))}}</textarea>
                    </label>
                    <br><br>
                    <h2>Email</h2>
                    <label for="MAIL_HOST">
                        Hostname <span class="required" title="Mandatory">*</span>
                        <input type="text" id="MAIL_HOST" name="MAIL_HOST" value="{{App::get('MAIL_HOST')}}" placeholder="Enter hostname" required/>
                    </label>
                    <label for="MAIL_SENDER">
                        Sender <span class="required" title="Mandatory">*</span>
                        <input type="email" id="MAIL_SENDER" name="MAIL_SENDER" value="{{App::get('MAIL_SENDER')}}" placeholder="Enter sender" required/>
                    </label>
                    <label for="MAIL_USERNAME">
                        Username <span class="required" title="Mandatory">*</span>
                        <input type="text" id="MAIL_USERNAME" name="MAIL_USERNAME" value="{{App::get('MAIL_USERNAME')}}" placeholder="Enter username" required/>
                    </label>
                    <label for="MAIL_PASSWORD">
                        Password <span class="required" title="Mandatory">*</span>
                        <input type="password" id="MAIL_PASSWORD" name="MAIL_PASSWORD" value="" placeholder="Enter a new password"/>
                    </label>
                    <br><br>
                    <h2>Cron</h2>
                    <label for="APP_CRONJOB">
                        Cron Job <span class="required" title="Mandatory">*</span>
                        <select id="APP_CRONJOB" name="APP_CRONJOB" required>
                            <option value="1" {{(App::get('APP_CRONJOB') == 1) ? "selected" : ""}}>Enabled</option>
                            <option value="0" {{(App::get('APP_CRONJOB') == 0) ? "selected" : ""}}>Disabled</option>
                        </select>
                    </label>
                    <label> 
                        URL 
                        <input type="text" value="{{App::get('APP_URL')}}/cron?key={{App::get('AUTH_CRON')}}" disabled/>
                    </label>
                    <br><br>
                    <h2>Additional Settings</h2>
                    {% $ignore = "'APP_DEBUG', 'APP_URL', 'APP_NAME', 'APP_TITLE', 'APP_AUTHOR', 'APP_DESCRIPTION', 'APP_LANGUAGE', 'META_PUBLIC', 'FILES_JS', 'FILES_CSS', 'APP_LOGIN', 'APP_MAINTENANCE', 'APP_SIGNUP', 'MAIL_HOST', 'MAIL_SENDER', 'MAIL_USERNAME', 'MAIL_PASSWORD', 'APP_CRONJOB'"; %}
                    {% foreach (Database::query("SELECT * FROM app_config WHERE name NOT IN (".$ignore.")") as $key => $config): %}
                        <label for="config_value[{{$key}}]">{{$config['name']}}
                            <input type="hidden" name="config_name[]" value="{{$config['name']}}"/>
                            <input type="text" id="config_value[{{$key}}]" name="config_value[]" value="{{$config['value']}}" placeholder="Enter value"/>
                        </label>
                    {% endforeach; %}
                    <label for="config_value[]">
                        <input type="text" name="config_name[]" placeholder="Enter name"/>
                        <input type="text" id="config_value[]" name="config_value[]" placeholder="Enter value"/>
                    </label>
                    <br><br>
                    <button class="btn is--primary" title="Save Changes">Save Changes</button> <a class="btn is--secondary" data-request="admin/cache/clear">Clear Cache</a>
                </form>
            </div>
        </div>
    </section>
    <div class="response"></div>
</main>

{% include footer.tpl %}