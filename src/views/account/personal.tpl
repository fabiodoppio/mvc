{% include /_includes/header.tpl %} 
        {% include /_includes/topbar.tpl %} 

        <main class="account personal">
            <section class="section is--light">
                <div class="container">
                    {% include /_includes/sidebar.tpl %}
                    <div class="main-content">
                        <h1 class="title">{{"Hey %s", $account->meta->displayname ?? $account->username}},</h1>
                        <p>{{"Manage your personal information."}}</p> 
                        {% if ($account->role < $account->roles->verified): %}
                            <span class="info">
                                {{"Your email address is not verified."}} {{"<a href=\"%s/account/email\">Verify now</a> to gain full access to all features of this app.", $app->url}}
                            </span>
                        {% endif; %}
                            <h2 class="title">{{"Avatar"}}</h2>
                            <div class="avatar">
                                {% if (isset($account->avatar)): %}
                                    <img src="{{$app->url}}{{$directory->uploads}}/{{$account->avatar}}" alt="{{$account->avatar}}"/>
                                {% endif; %}
                            </div>
                            <form data-request="account/personal/avatar/upload">
                                <label for="avatar"> 
                                    <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your image must be squared and not exceed the maximum allowed file size of %s KB", "3072"}}</span></div>
                                    <span class="btn is--primary">{{"Upload Avatar"}}</span>
                                    <input id="avatar" type="file" name="avatar" accept="image/*" hidden/>
                                </label>
                                <br><br>
                                <div class="response"></div>
                            </form>
                            <form data-request="account/personal/avatar/delete">
                                <br><br>
                                <div class="response"></div>
                                <button class="btn is--secondary" data-request="account/personal/avatar/delete">{{"Delete Avatar"}}</button>
                            </form>
                            <br><br>
                        <h2 class="title">{{"Contact"}}</h2>
                        <form data-request="account/personal/edit">
                                <label for="displayname">
                                    {{"Display Name"}} <span class="required" title="{{'Required'}}">*</span>
                                    <select id="displayname" name="displayname" required>
                                        {% $selected = (isset($account->meta->displayname) && $account->meta->displayname == $account->username) ? "selected" : ""; %}
                                        <option value="{{$account->username}}" {{$selected}}>{{$account->username}}</option>
                                        {% if (isset($account->meta->company)): %}
                                            {% $selected = (isset($account->meta->displayname) && $account->meta->displayname == $account->meta->company) ? "selected" : ""; %}
                                            <option value="{{$account->meta->company}}" {{$selected}}>{{$account->meta->company}}</option>
                                        {% endif; %}
                                        {% if (isset($account->meta->firstname)): %}
                                            {% $selected = (isset($account->meta->displayname) && $account->meta->displayname == $account->meta->firstname) ? "selected" : ""; %}
                                            <option value="{{$account->meta->firstname}}" {{$selected}}>{{$account->meta->firstname}}</option>
                                        {% endif; %}
                                        {% if (isset($account->meta->lastname)): %}
                                            {% $selected = (isset($account->meta->displayname) && $account->meta->displayname == $account->meta->lastname) ? "selected" : ""; %}
                                            <option value="{{$account->meta->lastname}}" {{$selected}}>{{$account->meta->lastname}}</option>
                                        {% endif; %}
                                        {% if (isset($account->meta->firstname) && isset($account->meta->lastname)): %}
                                            {% $selected = (isset($account->meta->displayname) && $account->meta->displayname == $account->meta->firstname." ".$account->meta->lastname) ? "selected" : ""; %}
                                            <option value="{{$account->meta->firstname}} {{$account->meta->lastname}}" {{$selected}}>{{$account->meta->firstname}} {{$account->meta->lastname}}</option>
                                            {% $selected = (isset($account->meta->displayname) && $account->meta->displayname == $account->meta->lastname." ".$account->meta->firstname) ? "selected" : ""; %}
                                            <option value="{{$account->meta->lastname}} {{$account->meta->firstname}}" {{$selected}}>{{$account->meta->lastname}} {{$account->meta->firstname}}</option>
                                        {% endif; %}
                                    </select>
                                </label>
                                <label for="company">
                                    {{"Company"}}
                                    <input type="text" id="company" name="company" maxlength="64" placeholder="{{'Enter company'}}" value="{{$account->meta->company}}"/>
                                </label>
                                <label for="firstname">
                                    {{"First Name"}}
                                    <input type="text" id="firstname" name="firstname" maxlength="64" placeholder="{{'Enter first name'}}" value="{{$account->meta->firstname}}"/>
                                </label>
                                <label for="lastname">
                                    {{"Last Name"}}
                                    <input type="text" id="lastname" name="lastname" maxlength="64" placeholder="{{'Enter last name'}}" value="{{$account->meta->lastname}}"/>
                                </label>
                                <label for="street">
                                    {{"Street / House Number"}}
                                    <input type="text" id="street" name="street" maxlength="64" placeholder="{{'Enter street / house number'}}" value="{{$account->meta->street}}"/>
                                </label>
                                <label for="postal">
                                    {{"ZIP Code"}}
                                    <input type="text" id="postal" name="postal" maxlength="64" placeholder="{{'Enter zip code'}}" value="{{$account->meta->postal}}"/>
                                </label>
                                <label for="city">
                                    {{"City"}}
                                    <input type="text" id="city" name="city" maxlength="64" placeholder="{{'Enter city'}}" value="{{$account->meta->city}}"/>
                                </label>
                                <label for="country">
                                    {{"Country"}}
                                    <input type="text" id="country" name="country" maxlength="64" placeholder="{{'Enter country'}}" value="{{$account->meta->country}}"/>
                                </label>
                                <br><br>
                                <div class="response"></div>
                            <button class="btn is--primary">{{"Save Changes"}}</button>
                        </form>
                        <br><br>
                        <h2 class="title">{{"Language"}}</h2>
                        <form data-request="account/locale">
                            <label for="language">
                                {{"Preferred Language"}} <span class="required" title="{{'Required'}}">*</span>
                                <select id="language" name="value" required>
                                    {% $language = $account->meta->language ?? $app->meta->language; %}
                                    <option value="de_DE.utf8" {{($language == "de_DE.utf8") ? "selected" : ""}}>Deutsch</option>
                                    <option value="en_EN.utf8" {{($language == "en_EN.utf8") ? "selected" : ""}}>English</option>
                                </select>
                            </label>
                            <button class="btn is--primary">{{"Change Language"}}</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>

{% include /_includes/footer.tpl %} 
