{% include /header.tpl %} 
        {% include /topbar.tpl %} 

        <main class="account email">
            <section class="section is--light">
                <div class="container">
                    {% include /account/sidebar.tpl %}
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
                                {{"I would like to be informed about news by email"}}
                            </label>
                            <button class="btn is--primary">{{"Save Changes"}}</button>
                        </form>
                        {% if ($account->role < 5): %}
                        <h2 class="title">{{"Email Address Verification"}}</h2>
                            {% if ($request->email != ""): %}
                                <form data-request="account/email/verify/submit">
                                    {% if ($request->code == ""): %}
                                        <div class="success">{{"We've sent you a <b>confirmation code</b> to verify to the registered <b>email address</b>."}}</div>
                                    {% endif; %}
                                    <label for="code">
                                        {{"Confirmation Code"}}<span class="required" title="{{'Required'}}">*</span>
                                        <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"You can find the 9-digit confirmation code in the email we sent you"}}</span></div>
                                        <input type="text" id="code" name="code" value="{{$request->code}}" maxlength="11" placeholder="{{'Enter confirmation code'}}" required/>
                                    </label><br><br>
                                    {% if (isset($request->redirect)): %}
                                        <input type="hidden" name="redirect" value="{{$request->redirect}}"/>
                                    {% endif; %}
                                    <div class="response"></div>
                                    <button class="btn is--primary">{{"Verify Email Address"}}</button>
                                </form>
                            {% else: %}
                                <form data-request="account/email/verify/request">
                                    <div class="warning">{{"We will send you a <b>confirmation code</b> to verify to your registered <b>email address</b>."}}</div><br>
                                    <br><br>
                                    {% if (isset($request->redirect)): %}
                                        <input type="hidden" name="redirect" value="{{$request->redirect}}"/>
                                    {% endif; %}
                                    <div class="response"></div>
                                    <button class="btn is--primary">{{"Request Confirmation Code"}}</button>
                                </form>
                            {% endif; %}  
                        {% endif; %} 
                    </div>
                </div>
            </section>
            <div class="response"></div>
        </main>

        {% include /footer.tpl %} 
