{% include header.tpl %}

<main class="admin accounts">
    <section class="section is--light">
        <div class="container">
            {% include admin/sidebar.tpl %}
            <div class="main-content">
                <h1 class="title">Accounts</h1>
                <p>Here, you have the ability to create, edit, or delete accounts.</p>
                <h2>Registered Accounts</h2>
                {% include admin/elements/AccountList.tpl %}
                <br>
                <h2>Add a New Account</h2>
                <form data-request="admin/account/add">
                    <label for="username">
                        Username <span class="required" title="Mandatory">*</span>
                        <input type="text" id="username" name="username" placeholder="Enter username" required/>
                    </label>
                    <label for="email">
                        Email Address <span class="required" title="Mandatory">*</span>
                        <input type="email" id="email" name="email" placeholder="Enter email address" required/>
                    </label>
                    <label for="role"> 
                        Role <span class="required" title="Mandatory">*</span>
                        <select name="role" id="role" required> 
                            <option value="1">Blocked</option>
                            <option value="2">Disabled</option>
                            <option value="3">Guest</option>
                            <option value="4" selected>User</option>
                            <option value="5">Verified</option>
                            <option value="6">Moderator</option>
                            <option value="7">Administrator</option>
                        </select>
                    </label>
                    <label for="pw1">
                        Password <span class="required" title="Mandatory">*</span>
                        <input type="password" name="pw1" placeholder="Enter password" required/>
                    </label>
                    <label for="pw2">
                        Confirm Password <span class="required" title="Mandatory">*</span>
                        <input type="password" name="pw2" placeholder="Confirm password" required/>
                    </label>
                    <br><br>
                    <button class="btn is--primary">Add Account</button>
                </form>
            </div>
        </div>
    </section>
    <div class="response"></div>
</main>

{% include footer.tpl %}
