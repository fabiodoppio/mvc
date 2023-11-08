{% include header.tpl %}

        <main class="account personal">
            <section class="section is--light">
                <div class="container">
                    {% include account/sidebar.tpl %}
                    <div class="main-content">
                        <h1 class="title">Persönliche Daten</h1>
                        <p>Verwalte deinen Namen und deine Kontaktinformationen, die zur Ausstellung von Rechnungen verwendet werden. Diese persönlichen Daten sind privat und werden ausschließlich zur Kontaktaufnahme mit dir verwendet.</p>
                        {% if ($account->get("avatar")): %}
                            <img src="{{App::get('APP_URL')}}{{App::get('DIR_UPLOADS')}}/{{$account->get('avatar')}}" width="200" height="200">
                        {% endif; %}
                        <form data-request="user/edit/avatar">
                            <input type="file" name="avatar"/>
                            <div class="response"></div>
                            <button>Bild hochladen</button>
                            <a href="#" data-request="user/edit/avatar">Bild löschen</a>
                        </form>
                        <form data-request="user/edit">
                            <h2>Kontakt</h2>
                            <label for="email">
                                E-Mail Adresse <span class="required" title="Pflichtfeld">*</span>
                                <input type="email" id="email" name="email" placeholder="E-Mail Adresse eingeben" value="{{$account->get('email')}}" required/>
                            </label>
                            <h2>Anschrift</h2>
                            <label for="company">
                                Unternehmen
                                <input type="hidden" name="meta_name[0]" value="company"/>
                                <input type="text" id="company" name="meta_value[0]" placeholder="Unternehmen eingeben (optional)" value="{{$account->get('company')}}"/>
                            </label>
                            <label for="firstname">
                                Vorname <span class="required" title="Pflichtfeld">*</span>
                                <input type="hidden" name="meta_name[1]" value="firstname"/>
                                <input type="text" id="firstname" name="meta_value[1]" placeholder="Vorname eingeben" value="{{$account->get('firstname')}}" required/>
                            </label>
                            <label for="lastname">
                                Nachname <span class="required" title="Pflichtfeld">*</span>
                                <input type="hidden" name="meta_name[2]" value="lastname"/>
                                <input type="text" id="lastname" name="meta_value[2]" placeholder="Nachname eingeben" value="{{$account->get('lastname')}}" required/>
                            </label>
                            <label for="street">
                                Straße / Hausnummer <span class="required" title="Pflichtfeld">*</span>
                                <input type="hidden" name="meta_name[3]" value="street"/>
                                <input type="text" id="street" name="meta_value[3]" placeholder="Straße / Hausnummer eingeben" value="{{$account->get('street')}}" required/>
                            </label>
                            <label for="postal">
                                PLZ <span class="required" title="Pflichtfeld">*</span>
                                <input type="hidden" name="meta_name[4]" value="postal"/>
                                <input type="text" id="postal" name="meta_value[4]" placeholder="PLZ eingeben" value="{{$account->get('postal')}}" required/>
                            </label>
                            <label for="city">
                                Stadt <span class="required" title="Pflichtfeld">*</span>
                                <input type="hidden" name="meta_name[5]" value="city"/>
                                <input type="text" id="city" name="meta_value[5]" placeholder="Stadt eingeben" value="{{$account->get('city')}}" required/>
                            </label>
                            <label for="country">
                                Land <span class="required" title="Pflichtfeld">*</span>
                                <input type="hidden" name="meta_name[6]" value="country"/>
                                <input type="text" id="country" name="meta_value[6]" placeholder="Land eingeben" value="{{$account->get('country')}}" required/>
                            </label>
                            <div class="response"></div>
                            <button>Änderungen speichern</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>

{% include footer.tpl %}