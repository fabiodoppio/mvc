{% include head.tpl %} 
        {% include header.tpl %} 

        <main class="account personal">
            <section class="section is--light">
                <div class="container">
                    {% include account/sidebar.tpl %}
                    <div class="main-content">
                        <h1 class="title">{{"Personal Information"}}</h1>
                        <p>{{"Manage your personal information."}}</p> 
                        {% if ($account->role < 5): %}
                            <span class="info">
                                {{"Your email address is not verified. <a href=\"%s/account/verify\">Verify now</a> to gain full access to all features of this app.", $app->url}}                               
                            </span>
                        {% endif; %}
                        {% if (in_array("avatar", $app->metafields)): %}
                            <h2 class="title">{{"Avatar"}}</h2>
                            <div class="avatar">
                                {% if (isset($account->avatar)): %}
                                    <img src="{{$app->url}}{{$directory->uploads}}/{{$account->avatar}}" alt="{{$account->avatar}}"/>
                                {% endif; %}
                            </div>
                            <form data-request="user/edit/avatar/upload">
                                <label for="avatar"> <span class="btn is--primary">{{"Upload Avatar"}}</span>
                                    <input id="avatar" type="file" name="avatar" accept="image/*" hidden/>
                                </label>
                                <a class="btn is--secondary" data-request="user/edit/avatar/delete" data-value="">{{"Delete Avatar"}}</a>
                            </form>
                            <br><br>
                        {% endif; %}
                        <h2 class="title">{{"Contact"}}</h2>
                        <form data-request="user/edit">
                            {% if (in_array("displayname", $app->metafields)): %}
                                <label for="displayname">
                                    {{"Display Name"}} <span class="required" title="{{'Required'}}">*</span>
                                    <select id="displayname" name="displayname" required>
                                        {% $selected = (isset($account->displayname) && $account->displayname == $account->username) ? "selected" : ""; %}
                                        <option value="{{$account->username}}" {{$selected}}>{{$account->username}}</option>
                                        {% if (isset($account->company)): %}
                                            {% $selected = (isset($account->displayname) && $account->displayname == $account->company) ? "selected" : ""; %}
                                            <option value="{{$account->company}}" {{$selected}}>{{$account->company}}</option>
                                        {% endif; %}
                                        {% if (isset($account->firstname)): %}
                                            {% $selected = (isset($account->displayname) && $account->displayname == $account->firstname) ? "selected" : ""; %}
                                            <option value="{{$account->firstname}}" {{$selected}}>{{$account->firstname}}</option>
                                        {% endif; %}
                                        {% if (isset($account->lastname)): %}
                                            {% $selected = (isset($account->displayname) && $account->displayname == $account->lastname) ? "selected" : ""; %}
                                            <option value="{{$account->lastname}}" {{$selected}}>{{$account->lastname}}</option>
                                        {% endif; %}
                                        {% if (isset($account->firstname) && isset($account->lastname)): %}
                                            {% $selected = (isset($account->displayname) && $account->displayname == $account->firstname." ".$account->lastname) ? "selected" : ""; %}
                                            <option value="{{$account->firstname}} {{$account->lastname}}" {{$selected}}>{{$account->firstname}} {{$account->lastname}}</option>
                                            {% $selected = (isset($account->displayname) && $account->displayname == $account->lastname." ".$account->firstname) ? "selected" : ""; %}
                                            <option value="{{$account->lastname}} {{$account->firstname}}" {{$selected}}>{{$account->lastname}} {{$account->firstname}}</option>
                                        {% endif; %}
                                    </select>
                                </label>
                            {% endif; %}
                            <label for="email">
                                {{"Email Address"}} <span class="required" title="{{'Required'}}">*</span>
                                {% $disabled = (!in_array("email", $app->metafields)) ? "disabled" : "" %}
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"You will have to verify your email address again"}}</span></div>
                                <input type="email" id="email" name="email" maxlength="64" placeholder="{{'Enter email address'}}" value="{{$account->email}}" {{$disabled}} required/>
                            </label>
                            {% if (in_array("company", $app->metafields)): %}
                                <label for="company">
                                    {{"Company"}}
                                    <input type="hidden" name="name[]" value="company"/>
                                    <input type="text" id="company" name="value[]" maxlength="64" placeholder="{{'Enter company'}}" value="{{$account->company}}"/>
                                </label>
                            {% endif; %}
                            {% if (in_array("firstname", $app->metafields)): %}
                                <label for="firstname">
                                    {{"First Name"}}
                                    <input type="hidden" name="name[]" value="firstname"/>
                                    <input type="text" id="firstname" name="value[]" maxlength="64" placeholder="{{'Enter first name'}}" value="{{$account->firstname}}"/>
                                </label>
                            {% endif; %}
                            {% if (in_array("lastname", $app->metafields)): %}
                                <label for="lastname">
                                    {{"Last Name"}}
                                    <input type="hidden" name="name[]" value="lastname"/>
                                    <input type="text" id="lastname" name="value[]" maxlength="64" placeholder="{{'Enter last name'}}" value="{{$account->lastname}}"/>
                                </label>
                            {% endif; %}
                            {% if (in_array("street", $app->metafields)): %}
                                <label for="street">
                                    {{"Street / House Number"}}
                                    <input type="hidden" name="name[]" value="street"/>
                                    <input type="text" id="street" name="value[]" maxlength="64" placeholder="{{'Enter street / house number'}}" value="{{$account->street}}"/>
                                </label>
                            {% endif; %}
                            {% if (in_array("postal", $app->metafields)): %}
                                <label for="postal">
                                    {{"ZIP Code"}}
                                    <input type="hidden" name="name[]" value="postal"/>
                                    <input type="text" id="postal" name="value[]" maxlength="64" placeholder="{{'Enter zip code'}}" value="{{$account->postal}}"/>
                                </label>
                            {% endif; %}
                            {% if (in_array("city", $app->metafields)): %}
                                <label for="city">
                                    {{"City"}}
                                    <input type="hidden" name="name[]" value="city"/>
                                    <input type="text" id="city" name="value[]" maxlength="64" placeholder="{{'Enter city'}}" value="{{$account->city}}"/>
                                </label>
                            {% endif; %}
                            {% if (in_array("country", $app->metafields)): %}
                                <label for="country">
                                    {{"Country"}}
                                    <input type="hidden" name="name[]" value="country"/>
                                    <input type="text" id="country" name="value[]" maxlength="64" placeholder="{{'Enter country'}}" value="{{$account->country}}"/>
                                </label>
                            {% endif; %}
                            <button class="btn is--primary">Save Changes</button>
                        </form>
                        {% if (in_array("language", $app->metafields)): %}
                            <br><br>
                            <h2 class="title">{{"Language"}}</h2>
                            <form data-request="account/locale">
                                <label for="language">
                                    {{"Preferred Language"}} <span class="required" title="{{'Required'}}">*</span>
                                    <select id="language" name="language" required>
                                        {% $language = $account->language ?? $app->language; %}
                                        <option value="de_DE.utf8" {{($language == "de_DE.utf8") ? "selected" : ""}}>Deutsch</option>
                                        <option value="en_EN.utf8" {{($language == "en_EN.utf8") ? "selected" : ""}}>English</option>
                                    </select>
                                </label>
                                <button class="btn is--primary">{{"Change Language"}}</button>
                            </form>
                        {% endif; %}
                    </div>
                </div>
            </section>
            <div class="response"></div>
        </main>

        {% include footer.tpl %} 
{% include foot.tpl %}