{% include head.tpl %} 
        {% include header.tpl %} 

        <main class="admin settings">
            <section class="section is--light">
                <div class="container">
                    {% include admin/sidebar.tpl %}
                    <div class="main-content">
                        <h1 class="title">{{"App Settings"}}</h1>
                        <p>{{"Add or edit your app settings."}}</p>
                        <h2 class="title">{{"General"}}</h2>
                        <form data-request="admin/settings/edit">
                            <label for="APP_DEBUG">
                                {{"Environment"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Development mode disables caching and enables php error reporting<br>Production mode enables caching and disables php error reporting"}}</span></div>
                                <select id="APP_DEBUG" name="APP_DEBUG" required>
                                    <option value="1" {{($app->debug == 1) ? "selected" : ""}}>{{"Development"}}</option>
                                    <option value="0" {{($app->debug == 0) ? "selected" : ""}}>{{"Production"}}</option>
                                </select>
                            </label>
                            <label for="APP_URL">
                                {{"App URL"}} <span class="required" title="{{'Required'}}">*</span> 
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Url to your app, no trailing slash<br>e.g. https://mydomain.com"}}</span></div>
                                <input type="text" id="APP_URL" name="APP_URL" value="{{$app->url}}" maxlength="64" placeholder="App URL eingeben" required/>
                            </label>
                            <label for="APP_NAME">
                                {{"App Name"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Name of your app"}}</span></div>
                                <input type="text" id="APP_NAME" name="APP_NAME" value="{{$app->name}}" maxlength="64" placeholder="{{'Enter app name'}}" required/>
                            </label>
                            <label for="APP_TITLE">
                                {{"Homepage Title"}}
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Title of your start page"}}</span></div>
                                <input type="text" id="APP_TITLE" name="APP_TITLE" value="{{$app->title}}" maxlength="64" placeholder="{{'Enter homepage title'}}"/>
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
                            <label for="APP_LANGUAGE"> 
                                {{"App Language"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your preferred language"}}</span></div>
                                <select id="APP_LANGUAGE" name="APP_LANGUAGE" required>
                                    <option value="de_DE.utf8" {{($app->language == "de_DE.utf8") ? "selected" : ""}}>Deutsch</option>
                                    <option value="en_EN.utf8" {{($app->language == "en_EN.utf8") ? "selected" : ""}}>English</option>
                                </select>
                            </label>
                            <label for="APP_THEME">
                                {{"App Color Scheme"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your preferred color scheme"}}</span></div>
                                <select id="APP_THEME" name="APP_THEME" required>
                                    <option value="default" {{($app->theme == "default") ? "selected" : ""}}>{{"Default"}}</option>
                                    <option value="gray-purple" {{($app->theme == "gray-purple") ? "selected" : ""}}>{{"Gray-Purple"}}</option>
                                </select>
                            </label>
                            <label for="APP_LOGIN">
                                {{"Login"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"De/activates the login page, except for admins"}}</span></div>
                                <select id="APP_LOGIN" name="APP_LOGIN" required>
                                    <option value="1" {{($app->login == 1) ? "selected" : ""}}>{{"Enable"}}</option>
                                    <option value="0" {{($app->login == 0) ? "selected" : ""}}>{{"Disable"}}</option>
                                </select>
                            </label>
                            <label for="APP_SIGNUP">
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"De/activates the signup page"}}</span></div>
                                {{"Signup"}} <span class="required" title="{{'Required'}}">*</span>
                                <select id="APP_SIGNUP" name="APP_SIGNUP" required>
                                    <option value="1" {{($app->signup == 1) ? "selected" : ""}}>{{"Enable"}}</option>
                                    <option value="0" {{($app->signup == 0) ? "selected" : ""}}>{{"Disable"}}</option>
                                </select>
                            </label>
                            <label for="APP_MAINTENANCE">
                                {{"Maintenance Mode"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"De/activates maintenance mode, except for admins"}}</span></div>
                                <select id="APP_MAINTENANCE" name="APP_MAINTENANCE" required>
                                    <option value="1" {{($app->maintenance == 1) ? "selected" : ""}}>{{"Enable"}}</option>
                                    <option value="0" {{($app->maintenance == 0) ? "selected" : ""}}>{{"Disable"}}</option>
                                </select>
                            </label>
                            <label for="APP_METAFIELDS"> 
                                {{"Public Metafields"}}
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Fields a user can edit in the account settings"}}</span></div>
                                <textarea id="APP_METAFIELDS" name="APP_METAFIELDS" placeholder="{{'Enter metafields'}}">{{implode("\n", $app->metafields)}}</textarea>
                            </label>
                            <label for="APP_BADWORDS"> 
                                {{"Badwords"}}
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Words not allowed for usernames and short messages"}}</span></div>
                                <textarea id="APP_BADWORDS" name="APP_BADWORDS" placeholder="{{'Enter badwords'}}">{{implode("\n", $app->badwords)}}</textarea>
                            </label>
                            <label for="CUSTOM_CSS">
                                {{"Additional CSS Files"}}
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Paths to additional CSS files in the app directory<br>e.g. /app/assets/styles/general.css"}}</span></div>
                                <textarea id="CUSTOM_CSS" name="CUSTOM_CSS" placeholder="{{'Enter additional css files'}}">{{implode("\n", $custom->css)}}</textarea>
                            </label>
                            <label for="CUSTOM_JS">
                                {{"Additional JS Files"}}
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Paths to additional JavaScript files in the app directory<br>e.g. /app/assets/scripts/main.js"}}</span></div>
                                <textarea id="CUSTOM_JS" name="CUSTOM_JS" placeholder="{{'Enter additional js files'}}">{{implode("\n", $custom->js)}}</textarea>
                            </label>
                            <br><br>
                            <h2 class="title">Media Upload</h2>
                            <label for="UPLOAD_FILE_SIZE">
                                {{"Maximum File Size"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Maximum allowed file size in bytes"}}</span></div>
                                <input type="number" step="1" min="0" id="UPLOAD_FILE_SIZE" name="UPLOAD_FILE_SIZE" value="{{$upload->filesize}}" placeholder="{{'Enter maximum file size'}}" required/>
                            </label>
                            <label for="UPLOAD_FILE_TYPES">
                                {{"Allowed File Types"}}
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Allowed file types for uploads"}}</span></div>
                                <textarea id="UPLOAD_FILE_TYPES" name="UPLOAD_FILE_TYPES" placeholder="{{'Enter allowed file types'}}">{{implode("\n", $upload->filetypes)}}</textarea>
                            </label>
                            <br><br>
                            <h2 class="title">Mail Server</h2>
                            <label for="MAIL_HOST">
                                {{"Hostname"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Hostname of your mail server"}}</span></div>
                                <input type="text" id="MAIL_HOST" name="MAIL_HOST" value="{{$mail->host}}" placeholder="{{'Enter hostname'}}" required/>
                            </label>
                            <label for="MAIL_SENDER">
                                {{"Sender"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Sender email address for outgoing emails"}}</span></div>
                                <input type="email" id="MAIL_SENDER" name="MAIL_SENDER" value="{{$mail->sender}}" placeholder="{{'Enter sender'}}" required/>
                            </label>
                            <label for="MAIL_USERNAME">
                                {{"Username"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Username of your mail server"}}</span></div>
                                <input type="text" id="MAIL_USERNAME" name="MAIL_USERNAME" value="{{$mail->username}}" placeholder="{{'Enter username'}}" required/>
                            </label>
                            <label for="MAIL_PASSWORD">
                                {{"Password"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Password of your mail server"}}</span></div>
                                <input type="password" id="MAIL_PASSWORD" name="MAIL_PASSWORD" value="" placeholder="{{'Enter new password'}}"/>
                            </label>
                            <br><br>
                            <h2 class="title">{{"Cron"}}</h2>
                            <label for="CRON_ACTIVE">
                                {{"Cronjob"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"De/activates the cronjob page"}}</span></div>
                                <select id="CRON_ACTIVE" name="CRON_ACTIVE" required>
                                    <option value="1" {{($cron->active == 1) ? "selected" : ""}}>{{"Enable"}}</option>
                                    <option value="0" {{($cron->active == 0) ? "selected" : ""}}>{{"Disable"}}</option>
                                </select>
                            </label>
                            <label> 
                                {{"URL"}}
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Secured url to the cronjob page"}}</span></div>
                                <input type="text" value="{{$cron->url}}" disabled/>
                            </label>
                            <br><br>
                            <h2 class="title">{{"Additional Settings"}}</h2>
                            {% foreach ($app->properties as $name => $value): %}
                                    <label for="{{$name}}">{{$name}}
                                        <input type="hidden" name="name[]" value="{{$name}}"/>
                                        <input type="text" id="{{$name}}" name="value[]" value="{{$value}}" placeholder="{{'Enter values'}}"/>
                                    </label>
                            {% endforeach; %}
                            <label for="value[]">
                                <input type="text" name="name[]" maxlength="64" placeholder="{{'Enter name'}}"/>
                                <input type="text" id="value[]" name="value[]" placeholder="{{'Enter value'}}"/>
                            </label>
                            <br><br>
                            <button class="btn is--primary">{{"Save Changes"}}</button> <a class="btn is--secondary" data-request="admin/cache/clear">{{"Empty Cache"}}</a>
                        </form>
                    </div>
                </div>
            </section>
            <div class="response"></div>
        </main>

        {% include footer.tpl %} 
{% include foot.tpl %}