{% include /admin/header.tpl %} 

    {% include /admin/topbar.tpl %}
    
    <main class="admin settings">
        <div class="container">
            {% include /admin/sidebar.tpl %}
            <form data-request="admin/settings/edit">
                <div class="section">
                    <div class="title">
                        <h2>{{"General"}}</h2>
                        <p>{{"Lege hier die wesentlichen Eigenschaften deiner App fest."}}</p>
                    </div>
                    <div class="content">
                        <label for="APP_NAME">
                            {{"App Name"}} <span class="required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Name of your app"}}</span></div>
                            <input type="text" id="APP_NAME" name="APP_NAME" value="{{$app->name}}" maxlength="64" placeholder="{{'Enter app name'}}" required/>
                        </label>
                        <label for="APP_TITLE">
                            {{"App Title"}} <span class="required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Title of your app"}}</span></div>
                            <input type="text" id="APP_TITLE" name="APP_TITLE" value="{{$app->title}}" maxlength="64" placeholder="{{'Enter app title'}}" required/>
                        </label>
                        <label for="APP_URL">
                            {{"App URL"}} <span class="required" title="{{'Required'}}">*</span> 
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Url to your app, no trailing slash<br>e.g. https://mydomain.com"}}</span></div>
                            <input type="text" id="APP_URL" name="APP_URL" value="{{$app->url}}" maxlength="64" placeholder="App URL eingeben" required/>
                        </label>
                        <label for="APP_LANGUAGE"> 
                            {{"Default Language"}} <span class="required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your preferred default language"}}</span></div>
                            <select id="APP_LANGUAGE" name="APP_LANGUAGE" required>
                                <option value="de_DE.utf8" {{($app->language == "de_DE.utf8") ? "selected" : ""}}>Deutsch</option>
                                <option value="en_EN.utf8" {{($app->language == "en_EN.utf8") ? "selected" : ""}}>English</option>
                            </select>
                        </label>
                        <label for="APP_DEBUG">
                            {{"App Mode"}} <span class="required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Development mode disables caching and enables php error reporting<br>Production mode enables caching and disables php error reporting"}}</span></div>
                            <select id="APP_DEBUG" name="APP_DEBUG" required>
                                <option value="1" {{($app->debug == 1) ? "selected" : ""}}>{{"Development"}}</option>
                                <option value="0" {{($app->debug == 0) ? "selected" : ""}}>{{"Production"}}</option>
                            </select>
                        </label>
                        <label for="APP_AUTHOR"> 
                            {{"App Author"}}
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Author of your app"}}</span></div>
                            <input type="text" id="APP_AUTHOR" name="APP_AUTHOR" value="{{$app->author}}" maxlength="64" placeholder="{{'Enter app author'}}"/>
                        </label>
                        <label for="APP_DESCRIPTION">
                            {{"App Description"}}
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Description of your app"}}</span></div>
                            <input type="text" id="APP_DESCRIPTION" name="APP_DESCRIPTION" value="{{$app->description}}" maxlength="160" placeholder="{{'Enter app description'}}"/>
                        </label>
                    </div>
                </div>
                <div class="section">
                    <div class="title">
                        <h2>{{"Modules"}}</h2>
                        <p>{{"Schalte essentielle Funktionen deiner App an oder aus."}}</p>
                    </div>
                    <div class="content">
                        <label for="APP_LOGIN"> 
                            {{"Login"}} <span class="required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your preferred default language"}}</span></div>
                            <select id="APP_LANGUAGE" name="APP_LANGUAGE" required>
                                <option value="1" {{($app->login == 1) ? "selected" : ""}}>Aktiviert</option>
                                <option value="0" {{($app->login == 0) ? "selected" : ""}}>Deaktiviert</option>
                            </select>
                        </label>
                        <label for="APP_SIGNUP"> 
                            {{"Signup"}} <span class="required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your preferred default language"}}</span></div>
                            <select id="APP_LANGUAGE" name="APP_LANGUAGE" required>
                                <option value="de_DE.utf8" {{($app->language == "de_DE.utf8") ? "selected" : ""}}>Deutsch</option>
                                <option value="en_EN.utf8" {{($app->language == "en_EN.utf8") ? "selected" : ""}}>English</option>
                            </select>
                        </label>
                        <label for="APP_MAINTENANCE"> 
                            {{"Maintenance"}} <span class="required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your preferred default language"}}</span></div>
                            <select id="APP_LANGUAGE" name="APP_LANGUAGE" required>
                                <option value="de_DE.utf8" {{($app->language == "de_DE.utf8") ? "selected" : ""}}>Deutsch</option>
                                <option value="en_EN.utf8" {{($app->language == "en_EN.utf8") ? "selected" : ""}}>English</option>
                            </select>
                        </label>
                      
                    </div>
                </div>
                <div class="section">
                    <div class="title">
                        <h2>{{"Email"}}</h2>
                        <p>{{"Stelle Verbindung zu deinem E-Mail Server her, damit deine Nutzer:innen systemrelevante E-Mails erhalten können."}}</p>
                    </div>
                    <div class="content">
                        <label for="MAIL_HOST">
                            {{"Hostname"}} <span class="required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Hostname of your mail server"}}</span></div>
                            <input type="text" id="MAIL_HOST" name="MAIL_HOST" value="{{$app->mail->host}}" placeholder="{{'Enter hostname'}}" required/>
                        </label>
                        <label for="MAIL_SENDER">
                            {{"Sender"}} <span class="required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Sender email address for outgoing emails"}}</span></div>
                            <input type="email" id="MAIL_SENDER" name="MAIL_SENDER" value="{{$app->mail->sender}}" placeholder="{{'Enter sender'}}" required/>
                        </label>
                        <label for="MAIL_USERNAME">
                            {{"Username"}} <span class="required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Username of your mail server"}}</span></div>
                            <input type="text" id="MAIL_USERNAME" name="MAIL_USERNAME" value="{{$app->mail->username}}" placeholder="{{'Enter username'}}" required/>
                        </label>
                        <label for="MAIL_PASSWORD">
                            {{"Password"}}
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Password of your mail server"}}</span></div>
                            <input type="password" id="MAIL_PASSWORD" name="MAIL_PASSWORD" value="" placeholder="{{'Enter new password'}}"/>
                        </label>
                        <label for="MAIL_ENCRYPT">
                            {{"SMTP Encryption"}} <span class="required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"SMTP encryption of your mail server"}}</span></div>
                            <select id="MAIL_ENCRYPT" name="MAIL_ENCRYPT" required>
                                <option value="ssl" {{($app->mail->encrypt == "ssl") ? "selected" : ""}}>SSL</option>
                                <option value="tsl" {{($app->mail->encrypt == "tsl") ? "selected" : ""}}>TSL</option>
                            </select>
                        </label>
                        <label for="MAIL_PORT">
                            {{"SMTP Port"}} <span class="required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"SMTP Port of your mail server"}}</span></div>
                            <input type="number" id="MAIL_PORT" name="MAIL_PORT" value="{{$app->mail->port}}" placeholder="{{'Enter smtp port'}}" required/>
                        </label>
                    </div>
                </div>
                <div class="section">
                    <div class="title">
                        <h2>{{"Cronjob"}}</h2>
                        <p>{{"Schalte essentielle Funktionen deiner App an oder aus."}}</p>
                    </div>
                    <div class="content">
                        <label for="CRON_ACTIVE"> 
                            {{"Login"}} <span class="required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your preferred default language"}}</span></div>
                            <select id="APP_LANGUAGE" name="APP_LANGUAGE" required>
                                <option value="de_DE.utf8" {{($app->language == "de_DE.utf8") ? "selected" : ""}}>Deutsch</option>
                                <option value="en_EN.utf8" {{($app->language == "en_EN.utf8") ? "selected" : ""}}>English</option>
                            </select>
                        </label>
                        <label for="CRON_AUTH"> 
                            {{"URL"}}
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your preferred default language"}}</span></div>
                            <input type="text" id="CRON_AUTH" name="CRON_AUTH" value="{{$app->cron->url}}" disabled/>
                        </label>
                    </div>
                </div>
                <div class="section">
                    <div class="title">
                        <h2>{{"CSS"}}</h2>
                        <p>{{"Styles Anweisungen die in den head geladen werden sollen"}}</p>
                    </div>
                    <div class="content no-padding">
                        <textarea id="template-css" name="APP_CSS" placeholder="{{'Enter template path'}}">{% echo $assets->style %}</textarea>
                    </div>
                </div>
                <div class="section">
                    <div class="title">
                        <h2>{{"JavaScript"}}</h2>
                        <p>{{"Funktionen die in den head geladen werden sollen"}}</p>
                    </div>
                    <div class="content no-padding">
                        <textarea id="template-js" name="APP_JS" placeholder="{{'Enter template path'}}">{% echo $assets->script %}</textarea>
                    </div>
                </div>
                <div class="section is-center">
                    <button class="btn is--primary">{{"Save Changes"}}</button>
                </div>
            </form>
        </div>
    </main>
    
{% include /admin/footer.tpl %}