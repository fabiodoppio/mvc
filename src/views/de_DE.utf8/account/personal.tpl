{% include header.tpl %}

        <main class="account personal">
            <section class="section is--light">
                <div class="container">
                    {% include account/sidebar.tpl %}
                    <div class="main-content">
                        <h1 class="title">Persönliche Daten</h1>
                        <h2>Profilbild</h2>
                        <div class="avatar">
                            {% if ($account->get("avatar")): %}
                                <img src="{{App::get('APP_URL')}}{{App::get('DIR_UPLOADS')}}/{{$account->get('avatar')}}" alt="profilbild"/>
                            {% endif; %}
                        </div>
                        <form data-request="user/edit/avatar/upload">
                            <label for="avatar"> <span class="btn is--primary">Profilbild hochladen</span>
                                <input id="avatar" type="file" name="avatar" accept="image/*" hidden/>
                            </label>
                            <a class="btn is--secondary" data-request="user/edit/avatar/delete" data-value="">Profilbild löschen</a>
                        </form>
                        <br><br>
                        <h2>Kontakt</h2>
                        <form data-request="user/edit">
                            <label for="displayname">
                                Anzeigename
                                <input type="hidden" name="meta_name[]" value="displayname"/>
                                <input type="text" id="displayname" name="meta_value[]" placeholder="Anzeigename eingeben (optional)" value="{{$account->get('displayname')}}"/>
                            </label>
                            <label for="email">
                                E-Mail Adresse <span class="required" title="Pflichtfeld">*</span>
                                <input type="email" id="email" name="email" placeholder="E-Mail Adresse eingeben" value="{{$account->get('email')}}" required/>
                            </label>
                            <h2>Anschrift</h2>
                            <label for="company">
                                Unternehmen
                                <input type="hidden" name="meta_name[]" value="company"/>
                                <input type="text" id="company" name="meta_value[]" placeholder="Unternehmen eingeben (optional)" value="{{$account->get('company')}}"/>
                            </label>
                            <label for="firstname">
                                Vorname <span class="required" title="Pflichtfeld">*</span>
                                <input type="hidden" name="meta_name[]" value="firstname"/>
                                <input type="text" id="firstname" name="meta_value[]" placeholder="Vorname eingeben" value="{{$account->get('firstname')}}" required/>
                            </label>
                            <label for="lastname">
                                Nachname <span class="required" title="Pflichtfeld">*</span>
                                <input type="hidden" name="meta_name[]" value="lastname"/>
                                <input type="text" id="lastname" name="meta_value[]" placeholder="Nachname eingeben" value="{{$account->get('lastname')}}" required/>
                            </label>
                            <label for="street">
                                Straße / Hausnummer <span class="required" title="Pflichtfeld">*</span>
                                <input type="hidden" name="meta_name[]" value="street"/>
                                <input type="text" id="street" name="meta_value[]" placeholder="Straße / Hausnummer eingeben" value="{{$account->get('street')}}" required/>
                            </label>
                            <label for="postal">
                                PLZ <span class="required" title="Pflichtfeld">*</span>
                                <input type="hidden" name="meta_name[]" value="postal"/>
                                <input type="text" id="postal" name="meta_value[]" placeholder="PLZ eingeben" value="{{$account->get('postal')}}" required/>
                            </label>
                            <label for="city">
                                Stadt <span class="required" title="Pflichtfeld">*</span>
                                <input type="hidden" name="meta_name[]" value="city"/>
                                <input type="text" id="city" name="meta_value[]" placeholder="Stadt eingeben" value="{{$account->get('city')}}" required/>
                            </label>
                            <label for="country">
                                Land <span class="required" title="Pflichtfeld">*</span>
                                <input type="hidden" name="meta_name[]" value="country"/>
                                <input type="text" id="country" name="meta_value[]" placeholder="Land eingeben" value="{{$account->get('country')}}" required/>
                            </label>
                            <button class="btn is--primary">Änderungen speichern</button>
                        </form>
                    </div>
                </div>
            </section>
            <div class="response"></div>
        </main>

{% include footer.tpl %}