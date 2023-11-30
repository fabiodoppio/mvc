{% include head.tpl %} 
        {% include header.tpl %} 

        <main class="admin accounts">
            <section class="section is--light">
                <div class="container">
                    {% include admin/sidebar.tpl %}
                    <div class="main-content">
                        <h1 class="title">{{"Accounts"}}</h1>
                        <p>{{"Create, edit or delete accounts."}}</p>
                        <h2 class="title">{{"All Accounts"}}</h2>
                        {% include admin/elements/AccountList.tpl %}
                        <br>
                        <h2 class="title">{{"Add New Account"}}</h2>
                        <form data-request="admin/account/add">
                            <label for="username">
                                {{"Username"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"The username must be between 3 and 18 characters long<br>and cannot contain any special characters"}}</span></div>
                                <input type="text" id="username" name="username" minlength="3" maxlength="18" placeholder="{{'Enter username'}}" required/>
                            </label>
                            <label for="email">
                                {{"Email Address"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"The email address will be verified the next time the user access a page for verified user"}}</span></div>
                                <input type="email" id="email" name="email" maxlength="64" placeholder="{{'Enter email address'}}" required/>
                            </label>
                            <label for="role"> 
                                {{"Role"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"The permission role of the user"}}</span></div>
                                <select name="role" id="role" required> 
                                    <option value="1">{{"Blocked"}}</option>
                                    <option value="2">{{"Deactivated"}}</option>
                                    <option value="3">{{"Guest"}}</option>
                                    <option value="4" selected>{{"User"}}</option>
                                    <option value="5">{{"Verified"}}</option>
                                    <option value="6">{{"Moderator"}}</option>
                                    <option value="7">{{"Administrator"}}</option>
                                </select>
                            </label>
                            <label for="pw1">
                                {{"Password"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"The password must be at least 8 characters long"}}</span></div>
                                <input type="password" name="pw1" minlength="8" maxlength="64" placeholder="{{'Enter password'}}" required/>
                            </label>
                            <label for="pw2">
                                {{"Repeat Password"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"The password must be at least 8 characters long"}}</span></div>
                                <input type="password" name="pw2" minlength="8" maxlength="64" placeholder="{{'Repeat password'}}" required/>
                            </label>
                            <br><br>
                            <button class="btn is--primary">{{"Add Account"}}</button>
                        </form>
                    </div>
                </div>
            </section>
            <div class="response"></div>
        </main>

        {% include footer.tpl %} 
{% include foot.tpl %}