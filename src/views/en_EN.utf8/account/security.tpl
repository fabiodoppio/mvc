{% include header.tpl %}

<main class="account security">
    <section class="section is--light">
        <div class="container">
            {% include account/sidebar.tpl %}
            <div class="main-content">
                <h1 class="title">Password & Security</h1>
                <form data-request="user/edit">
                    <h2>Login Details</h2>
                    <label for="username">
                        Username <span class="required" title="Required">*</span>
                        <input type="text" id="username" name="username" value="{{$account->get('username')}}" placeholder="Enter username" required/>
                    </label>
                    <label for="pw">
                        Current Password
                        <input type="password" id="pw" name="pw" placeholder="Enter current password"/>
                    </label>
                    <label for="pw1">
                        New Password
                        <input type="password" id="pw1" name="pw1" placeholder="Enter new password"/>
                    </label>
                    <label for="pw2">
                        Repeat New Password
                        <input type="password" id="pw2" name="pw2" placeholder="Repeat new password"/>
                    </label>
                    <div class="response"></div>
                    <button class="btn is--primary">Save Changes</button>
                </form>      
                <br><br> 
                <form data-request="account/glogout">
                    <h2>Sign Out Everywhere</h2>
                    <p>Sign out from all other sessions where your account is used, including all other browsers, phones, and devices.</p>
                    <button class="btn is--primary">Sign Out Other Sessions</button>
                </form>
            </div>
        </div>
    </section>
</main>

{% include footer.tpl %}