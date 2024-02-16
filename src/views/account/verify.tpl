{% include /header.tpl %} 
        {% include /topbar.tpl %} 

        <main class="account verify">
            <section class="section is--light">
                <div class="container">
                    <div class="main-content">
                        <h2 class="title">{{"Verify Email Address"}}</h1>
                        {% if ($request->email != ""): %}
                            <form data-request="user/verify/submit">
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
                            <form data-request="user/verify/request">
                                <div class="warning">{{"We will send you a <b>confirmation code</b> to verify to your registered <b>email address</b>."}}</div><br>
                                <br><br>
                                {% if (isset($request->redirect)): %}
                                    <input type="hidden" name="redirect" value="{{$request->redirect}}"/>
                                {% endif; %}
                                <div class="response"></div>
                                <button class="btn is--primary">{{"Request Confirmation Code"}}</button>
                            </form>
                        {% endif; %}  
                    </div>
                </div>
            </section>
        </main>

        {% include /footer.tpl %} 