{% include /_includes/header.tpl %} 
        {% include /_includes/topbar.tpl %} 

        <main class="account email">
            <section class="section is--light">
                <div class="container">
                    {% include /_includes/sidebar.tpl %}
                    <div class="main-content">
                        <h1 class="title">{{"Email Settings"}}</h1>
                        <p>{{"Manage your newsletter preference."}}</p>
                        <h2 class="title">{{"Contact"}}</h2>
                        <form data-request="account/email/edit">
                            <label for="email">
                                {{"Email Address"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"You will have to verify your email address again"}}</span></div>
                                <input type="email" id="email" name="email" maxlength="64" placeholder="{{'Enter email address'}}" value="{{$account->email}}" required/>
                            </label>
                            <label for="newsletter">
                                <input type="hidden" name="newsletter" value="0"/>
                                <input type="checkbox" id="newsletter" name="newsletter" value="1" {{(($account->meta->newsletter??"1")?"checked":"")}}/>
                                {{"I would like to be informed about news by email."}}
                            </label>
                            <br><br>                         
                            <div class="response"></div>
                            <button class="btn is--primary">{{"Save Changes"}}</button>
                        </form>
                        {% if ($account->role < $account->roles->verified): %}
                            <h2 class="title">{{"Email Address Verification"}}</h2>
                            {% include /_includes/verify.tpl %}
                        {% endif; %} 
                    </div>
                </div>
            </section>
        </main>

{% include /_includes/footer.tpl %} 
