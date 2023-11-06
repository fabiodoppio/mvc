{% include header.tpl %}

<main class="page recovery">
    <section class="section is--light">
        <div class="container">
            <div class="main-content">
                <h2 class="title">Recover Account</h2>
                {% if ($credential != ""): %}
                    <form data-request="account/recovery/submit">
                        <input type="hidden" name="credential" value="{{$credential}}" required/>
                        {% if ($code == ""): %}
                            <div class="success">We've sent you a <b>confirmation code</b> to reset your password to the registered <b>Email Address</b>.</div>
                        {% endif; %}
                        <label for="code">Confirmation Code <span class="required" title="Mandatory">*</span><br>
                        <input type="text" id="code" name="code" value="{{$code}}" placeholder="Enter confirmation code" required/></label><br><br>
                        <label for="pw1">New Password <span class="required" title="Mandatory">*</span><br>
                        <input type="password" id="pw1" name="pw1" placeholder="Enter new password"/></label><br>
                        <label for="pw2">Confirm New Password <span class="required" title="Mandatory">*</span><br>
                        <input type="password" id="pw2" name="pw2" placeholder="Confirm new password"/></label><br><br>
                        <div class="response"></div>
                        <button>Recover Account</button>
                    </form>
                {% else: %}
                    <form data-request="account/recovery/request">
                        <div class="warning">We will send you a <b>confirmation code</b> to reset your password to the registered <b>Email Address</b>.</div><br>
                        <label for="credential">Username or Email Address <span class="required" title="Mandatory">*</span><br>
                        <input type="text" id="credential" name="credential" placeholder="Enter username or email address" required/></label><br><br>
                        <div class="response"></div>
                        <button>Request Confirmation Code</button>
                    </form>
                {% endif; }
            </div>
        </div>
    </section>
</main>

{% include footer.tpl %}