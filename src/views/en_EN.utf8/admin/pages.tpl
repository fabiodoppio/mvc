{% include header.tpl %}

<main class="admin pages">
    <section class="section is--light">
        <div class="container">
            {% include admin/sidebar.tpl %}
            <div class="main-content">
                <h1 class="title">Pages</h1>
                <p>Here, you have the ability to create, edit, or delete pages.</p>
                <h2>Custom Pages</h2>
                {% include admin/elements/PageList.tpl %}
                <br>
                <h2>Add New Page</h2>
                <form data-request="admin/page/add">
                    <label for="title">
                        Title <span class="required" title="Mandatory">*</span>
                        <input type="text" id="title" name="title" placeholder="Enter title" required/>
                    </label>
                    <label for="slug">
                        URL Slug <span class="required" title="Mandatory">*</span>
                        <input type text" id="slug" name="slug" placeholder="Enter URL slug" required/>
                    </label>
                    <label for="description">
                        Description
                        <input type="text" id="description" name="description" placeholder="Enter description"/>
                    </label>
                    <label for="robots">
                        Robots
                        <input type="text" id="robots" name="robots" placeholder="Enter robots"/>
                    </label>
                    <label for="template">
                        Template Path <span class="required" title="Mandatory">*</span>
                        <input type="text" id="template" name="template" placeholder="Enter template path" required/>
                    </label>
                    <label for="role">
                        Minimum Requirement <span class="required" title="Mandatory">*</span>
                        <select name="role" required>
                            <option value="1">Blocked</option>
                            <option value="2">Disabled</option>
                            <option value="3" selected>Guest</option>
                            <option value="4">User</option>
                            <option value="5">Verified</option>
                            <option value="6">Moderator</option>
                            <option value="7">Administrator</option>
                        </select>
                    </label>
                    <br><br>
                    <button class="btn is--primary">Add Page</button>
                </form>
            </div>
        </div>
    </section>
    <div class="response"></div>
</main>

{% include footer.tpl %}