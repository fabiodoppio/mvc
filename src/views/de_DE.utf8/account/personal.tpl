{% include header.tpl %}

        <main class="account personal">
            <section class="section is--light">
                <div class="container">
                    {% include account/sidebar.tpl %}
                    <div class="main-content">
                        <h1 class="title">Persönliche Daten</h1>
                        <p>Verwalte deinen Namen und deine Kontaktinformationen, die zur Ausstellung von Rechnungen verwendet werden. Diese persönlichen Daten sind privat und werden ausschließlich zur Kontaktaufnahme mit dir verwendet.</p>
                        <form data-request="user/personal">
                            {% if (in_array("email", json_decode(App::get("META_PUBLIC")))): %}
                                <h2>Kontakt</h2>
                                <label for="email">E-Mail Adresse <span class="required" title="Pflichtfeld">*</span><br>
                                <input type="email" id="email" name="email" placeholder="E-Mail Adresse eingeben" value="{{$account->get('email')}}" required/></label>
                            {% endif; %}
                            <h2>Anschrift</h2>
                            {% if (in_array("company", json_decode(App::get("META_PUBLIC")))): %}
                                <label for="company">Unternehmen<br>
                                <input type="text" id="company" name="company" placeholder="Unternehmen eingeben (optional)" value="{{$account->get('company')}}"/></label><br>
                            {% endif; %}
                            {% if (in_array("firstname", json_decode(App::get("META_PUBLIC")))): %}
                                <label for="firstname">Vorname <span class="required" title="Pflichtfeld">*</span><br>
                                <input type="text" id="firstname" name="firstname" placeholder="Vorname eingeben" value="{{$account->get('firstname')}}" required/></label>
                            {% endif; %}    
                            {% if (in_array("lastname", json_decode(App::get("META_PUBLIC")))): %}
                                <label for="lastname">Nachname <span class="required" title="Pflichtfeld">*</span><br>
                                <input type="text" id="lastname" name="lastname" placeholder="Nachname eingeben" value="{{$account->get('lastname')}}" required/></label>
                            {% endif; %}
                            {% if (in_array("street", json_decode(App::get("META_PUBLIC")))): %}
                                <label for="street">Straße / Hausnummer <span class="required" title="Pflichtfeld">*</span><br>
                                <input type="text" id="street" name="street" placeholder="Straße / Hausnummer eingeben" value="{{$account->get('street')}}" required/></label><br>
                            {% endif; %}
                            {% if (in_array("postal", json_decode(App::get("META_PUBLIC")))): %}
                                <label for="postal">PLZ <span class="required" title="Pflichtfeld">*</span><br>
                                <input type="text" id="postal" name="postal" placeholder="PLZ eingeben" value="{{$account->get('postal')}}" required/></label>
                            {% endif; %}
                            {% if (in_array("city", json_decode(App::get("META_PUBLIC")))): %}
                                <label for="city">Stadt <span class="required" title="Pflichtfeld">*</span><br>
                                <input type="text" id="city" name="city" placeholder="Stadt eingeben" value="{{$account->get('city')}}" required/></label>
                            {% endif; %}
                            {% if (in_array("country", json_decode(App::get("META_PUBLIC")))): %}
                                <label for="country">Land <span class="required" title="Pflichtfeld">*</span><br>
                                <input type="text" id="country" name="country" placeholder="Land eingeben" required/></label>
                            {% endif; %}
                            <div class="response"></div>
                            <button>Änderungen speichern</button>
                        </form>
                    </div>
                </div>
            </section>
        </main>

{% include footer.tpl %}