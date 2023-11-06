{% include header.tpl %}

<main class="account verify">
    <section class="section is--light">
        <div class="container">
            <div class="main-content">
                <h2 class="title">Verify Email Address</h1>
                {% if ($email != ""): %}
                    <form data-request="user/verify/submit">
                        {% if ($code == ""): %}
                            <div class="success">We've sent you a <b>verification code</b> to reset your password to the registered <b>email address</b>.</div>
                        {% endif; %}
                        <label for="code">Verification Code <span class="required" title="Required">*</span><br>
                        <input type="text" id="code" name="code" value="{{$code}}" placeholder="Enter verification code" required/></label><br><br>
                        {% if (Request::isset("redirect")): %}
                            <input type="hidden" name="redirect" value="{{$redirect}}"/>
                        {% endif; %}
                        <div class="response"></div>
                        <button>Verify Email Address</button>
                    </form>
                {% else: %}
                    <form data-request="user/verify/request">
                        <div class="warning">We will send you a <b>verification code</b> to your registered <b>email address</b> for verification.</div><br>
                        <br><br>
                        {% if (Request::isset("redirect")): %}
                            <input type="hidden" name="redirect" value="{{$redirect}}"/>
                        {% endif; %}
                        <div class="response"></div>
                        <button>Request Verification Code</button>
                    </form>
                {% endif; %}  
            </div>
        </div>
    </section>
</main>

{% include footer.tpl %}