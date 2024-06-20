{% include /_includes/header.tpl %} 
        {% include /_includes/topbar.tpl %} 

        <main>
            <div class="container">
                {% include /_includes/sidebar.tpl %}
                <div class="main-content is--fading">
                    <h1 class="title">{{"Email Settings"}}</h1>
                    <p>{{"Manage your email address and newsletter preference. Your email address will <b>not</b> be shown to other users on this platform and will only be used to contact you."}}</p>
                    <h2>{{"Contact"}}</h2>
                    <form data-request="account/email/edit">
                        <label for="email">
                            {{"Email Address"}} <span class="is--required" title="{{'Required'}}">*</span>
                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"You will have to verify your email address again."}}</span></div>
                            <input type="email" id="email" name="email" maxlength="64" placeholder="{{'Enter email address'}}" value="{{$account->email}}" autocomplete="email" required/>
                        </label>
                        <label for="newsletter">
                            <input type="hidden" name="newsletter" value="0"/>
                            <input type="checkbox" id="newsletter" name="newsletter" value="1" {{(($account->meta->newsletter??"1")?"checked":"")}}/>
                            {{"I would like to be informed about news by email."}}
                        </label>
                        <div class="response"></div>
                        <button class="btn is--primary is--submit">{{"Save Changes"}}</button>
                    </form>
                    <h2>{{"Verification"}}</h2>
                    <p>{{"Some functions of this app require a verified email address. For example, if you want to use the 2-Factor authentication for a secure login."}}</p>
                    <form data-request="account/email/verify">
                        {% if ($account->role >= $account->roles->verified): %}
                            <div class="alert is--success"><i class="fas fa-circle-check"></i> {{"Your email adress is verified."}}</div>
                        {% else: %}  
                            <div class="alert is--error"><i class="fas fa-circle-xmark"></i> {{"Your email adress is <b>not</b> verified."}}</div>
                            <div class="response"></div>
                            <button class="btn is--primary is--submit">{{"Request"}}</button>
                        {% endif; %}
                    </form>
                </div>
            </div>
        </main>

{% include /_includes/footer.tpl %} 