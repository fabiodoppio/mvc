{% include /_includes/header.tpl %}
        {% include /_includes/topbar.tpl %}

        <main>
            <div class="container">
                {% include /admin/_includes/sidebar.tpl %}
                <div class="main-content is--fading">
                    <h1 class="title">{{"Filter Settings"}}</h1>
                    <p>{{"On this page you can set specific filters for user input fields like usernames, email addresses or short messages."}}</p>
                    <h2>{{"Add Filter"}}</h2>
                    <form data-request="admin/filters/add">
                        <label for="addBadwords">
                            {{"One or more forbidden words"}}
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"You can enter multiple forbidden words separated by commas."}}</span></div>
                            <input type="text" id="addBadwords" name="badwords" placeholder="{{'Enter forbidden words'}}" autocomplete="off"/>
                        </label>
                        <label for="addProviders">
                            {{"One or more forbidden email providers"}}
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"You can enter multiple forbidden email providers separated by commas."}}</span></div>
                            <input type="text" id="addProviders" name="providers" placeholder="{{'Enter forbidden email providers'}}" autocomplete="off"/>
                        </label>
                        <button class="btn is--primary is--submit">{{"Add Filter"}}</button>
                    </form>
                    <h2>{{"Remove Filter"}}</h2>
                    <form data-request="admin/filters/remove">
                        <label for="removeBadwords">
                            {{"One or more forbidden words"}}
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"You can enter multiple forbidden words separated by commas."}}</span></div>
                            <input type="text" id="removeBadwords" name="badwords" placeholder="{{'Enter forbidden words'}}" autocomplete="off" required/>
                        </label>
                        <label for="removeProviders">
                            {{"One or more forbidden email providers"}}
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"You can enter multiple forbidden email providers separated by commas."}}</span></div>
                            <input type="text" id="removeProviders" name="providers" placeholder="{{'Enter forbidden email providers'}}" autocomplete="off"/>
                        </label>
                        <button class="btn is--primary is--submit">{{"Remove Filter"}}</button>
                    </form>
                    <h2>{{"Check Filters"}}</h2>
                    <p>{{"Validate your previously defined filters with a randomly selected text or a comma separated list of email adresses to avoid false positives."}}</p>
                    <form data-request="admin/filters/check">
                        <label for="message">
                            {{"Text to check"}} <span class="is--required" title="{{'Required'}}">*</span>
                            <textarea id="message" name="message" placeholder="{{'Enter text'}}" required></textarea>
                        </label>
                        <button class="btn is--primary is--submit">{{"Check Filters"}}</button>
                    </form>
                </div>
            </div>
        </main>

{% include /_includes/footer.tpl %}