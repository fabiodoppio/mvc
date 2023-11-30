{% include head.tpl %} 
        {% include header.tpl %} 

        <main class="admin pages">
            <section class="section is--light">
                <div class="container">
                    {% include admin/sidebar.tpl %}
                    <div class="main-content">
                        <h1 class="title">{{"Custom Pages"}}</h1>
                        <p>{{"Create, edit or delete custom pages."}}</p>
                        <h2 class="title">{{"All Pages"}}</h2>
                        {% include admin/elements/PageList.tpl %}
                        <br>
                        <h2 class="title">{{"Add New Page"}}</h2>
                        <form data-request="admin/page/add">
                            <label for="title">
                                {{"Title"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Title of the page"}}</span></div>
                                <input type="text" id="title" name="title" maxlength="64" placeholder="{{'Enter title'}}" required/>
                            </label>
                            <label for="slug">
                                {{"URL Slug"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Url to the page, starting with a slash<br>e.g. /imprint"}}</span></div>
                                <input type="text" id="slug" name="slug" maxlength="64" placeholder="{{'Enter url slug'}}" required/>
                            </label>
                            <label for="description">
                                {{"Description"}}
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Description of the page"}}</span></div>
                                <input type="text" id="description" name="description" maxlength="160" placeholder="{{'Enter description'}}"/>
                            </label>
                            <label for="robots">
                                {{"Robots"}}
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Robots of the page<br>e.g. index, nofollow"}}</span></div>
                                <input type="text" id="robots" name="robots" maxlength="64" placeholder="{{'Enter robots'}}"/>
                            </label>
                            <label for="template">
                                {{"Template Path"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Path to the template file in the views directory<br>e.g. imprint.tpl"}}</span></div>
                                <input type="text" id="template" name="template" maxlength="128" placeholder="{{'Enter template path'}}" required/>
                            </label>
                            <label for="role">
                                {{"Minimal Requirements"}} <span class="required" title="{{'Required'}}">*</span>
                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Minimum allowed user role to access the page"}}</span></div>
                                <select name="role" required>
                                    <option value="1">{{"Blocked"}}</option>
                                    <option value="2">{{"Deactivated"}}</option>
                                    <option value="3" selected>{{"Guest"}}</option>
                                    <option value="4">{{"User"}}</option>
                                    <option value="5">{{"Verified"}}</option>
                                    <option value="6">{{"Moderator"}}</option>
                                    <option value="7">{{"Administrator"}}</option>
                                </select>
                            </label>
                            <br><br>
                            <button class="btn is--primary">{{"Add Page"}}</button>
                        </form>
                    </div>
                </div>
            </section>
            <div class="response"></div>
        </main>

        {% include footer.tpl %} 
{% include foot.tpl %}