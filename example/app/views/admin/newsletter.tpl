{% include /_includes/header.tpl %}
        {% include /_includes/topbar.tpl %}

        <main>
            <div class="container">
                {% include /admin/_includes/sidebar.tpl %}
                <div class="main-content is--fading">
                    <h1 class="title">{{"Newsletter"}}</h1>
                    <p>{{"Keep everyone up to date with this simple newsletter tool. Please note that the process cannot be cancelled once you have pressed the button, emails will be sent immediately."}}</p>
                    <h2>{{"New Email"}}</h2>
                    <form data-request="admin/newsletter/send">
                        <input type="hidden" name="counter" value="{{$var->counter}}"/>
                        <div class="queue is--hidden">
                            <input type="text" name="queue" value="{{$var->accounts}}"/>
                        </div>
                        <label for="subject">
                            {{"Subject"}} <span class="is--required" title="{{'Required'}}">*</span>
                            <input type="text" id="subject" name="subject" placeholder="{{'Enter subject'}}" autocomplete="off" required/>
                        </label>
                        <label for="message">
                            {{"Message"}} <span class="is--required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"You can use HTML to format your text."}}</span></div>
                            <textarea id="message" name="message" rows="10" placeholder="{{'Enter message'}}" required></textarea>
                        </label>
                        <label for="ignore">
                            <input type="hidden" name="ignore" value="0">
                            <input type="checkbox" id="ignore" name="ignore" value="1"> {{"Ignore accounts newsletter setting (not recommended)"}}
                        </label>
                        <br/><br/>
                        <button class="btn is--primary is--submit" data-trigger="submit">{{"Send Newsletter"}}</button>
                        <span class="progressbar"></span>
                    </form>
                </div>
            </div>
        </main>

{% include /_includes/footer.tpl %}