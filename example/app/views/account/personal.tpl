{% include /_includes/header.tpl %}
        {% include /_includes/topbar.tpl %}

        <main>
            <div class="container">
                {% include /account/_includes/sidebar.tpl %}
                <div class="main-content is--fading">
                    <h1 class="title">{{"Hey %s!", $account->meta->displayname ?? $account->username}}</h1>
                    <p>{{"Manage your appearance and the information you want to share with us. Keep in mind that some of these data may be visible to other users on this platform."}}</p>
                    <h2>{{"Avatar"}}</h2>
                    {% if ($account->role < $account->roles->verified): %}
                        <br/>
                        <div class="alert is--info">{{"You have to verify your email address before you can upload an avatar."}} {{"<a href=\"%s/account/email\">Verify now</a> to gain full access to all features of this app.", $app->url}}</div>
                    {% else: %}
                        <form data-request="account/personal/avatar/upload">
                            <label>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your image must be squared and not exceed the maximum allowed file size of %s MB.", 3}}</span></div>
                            </label>
                            <div class="avatar">
                                {% if (isset($account->meta->avatar)): %}
                                    <img src="{{$app->url}}/media/avatars/{{$account->meta->avatar}}" alt="_avatar"/>
                                {% endif; %}
                            </div>
                            <input type="file" name="avatar" accept="image/*" hidden/>
                            <button class="btn is--primary is--submit" data-trigger="avatar">{{"Upload Avatar"}}</button>
                            <button class="btn is--secondary is--submit" data-request="account/personal/avatar/delete">{{"Delete Avatar"}}</button>
                        </form>
                    {% endif; %}
                    <h2>{{"Personal Data"}}</h2>
                    <form data-request="account/personal/edit">
                        <label for="displayname">
                            {{"Display Name"}} <span class="is--required" title="{{'Required'}}">*</span>
                            <select id="displayname" name="displayname" autocomplete="username name organization" required>
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
                        <label for="firstname">
                            {{"First Name"}}
                            <input type="text" id="firstname" name="firstname" maxlength="64" placeholder="{{'Enter first name'}}" value="{{$account->meta->firstname}}" autocomplete="given-name additional-name"/>
                        </label>
                        <label for="lastname">
                            {{"Last Name"}}
                            <input type="text" id="lastname" name="lastname" maxlength="64" placeholder="{{'Enter last name'}}" value="{{$account->meta->lastname}}" autocomplete="family-name"/>
                        </label>
                        <label for="street">
                            {{"Street / House Number"}}
                            <input type="text" id="street" name="street" maxlength="64" placeholder="{{'Enter street / house number'}}" value="{{$account->meta->street}}" autocomplete="street-address"/>
                        </label>
                        <label for="postal">
                            {{"ZIP Code"}}
                            <input type="text" id="postal" name="postal" maxlength="64" placeholder="{{'Enter zip code'}}" value="{{$account->meta->postal}}" autocomplete="postal-code"/>
                            </label>
                        <label for="city">
                            {{"City"}}
                            <input type="text" id="city" name="city" maxlength="64" placeholder="{{'Enter city'}}" value="{{$account->meta->city}}" autocomplete="address-level2"/>
                        </label>
                        <label for="country">
                            {{"Country"}}
                            <input type="text" id="country" name="country" maxlength="64" placeholder="{{'Enter country'}}" value="{{$account->meta->country}}" autocomplete="country-name"/>
                        </label>
                        <button class="btn is--primary is--submit">{{"Save Changes"}}</button>
                    </form>
                    <h2>{{"Business Data"}}</h2>
                    <form data-request="account/personal/edit">
                        <label for="company">
                            {{"Company"}}
                            <input type="text" id="company" name="company" maxlength="64" placeholder="{{'Enter company'}}" value="{{$account->meta->company}}" autocomplete="organization"/>
                        </label>
                        <label for="vat">
                            {{"VAT Number"}}
                            <input type="text" id="vat" name="vat" maxlength="64" placeholder="{{'Enter VAT number'}}" value="{{$account->meta->vat}}" autocomplete="off"/>
                        </label>
                        <button class="btn is--primary is--submit">{{"Save Changes"}}</button>
                    </form>
                    {% if (count($app->languages) > 1): %}
                        <h2>{{"Language"}}</h2>
                        <form data-request="account/locale">
                            <label for="language">
                                {{"Preferred Language"}} <span class="is--required" title="{{'Required'}}">*</span>
                                <select id="language" name="value" autocomplete="language" required>
                                    {% $language = $account->meta->language ?? $app->language; %}
                                    {% foreach($app->languages as $value): %}
                                        <option value="{{$value}}" {{($language == $value) ? "selected" : ""}}>{% echo _($value); %}</option>
                                    {% endforeach; %}
                                </select>
                            </label>
                            <button class="btn is--primary is--submit">{{"Change Language"}}</button>
                        </form>
                    {% endif; %}
                </div>
            </div>
        </main>

{% include /_includes/footer.tpl %}