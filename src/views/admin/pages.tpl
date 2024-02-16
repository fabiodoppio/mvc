{% include /admin/header.tpl %} 

    {% include /admin/topbar.tpl %} 

    <main class="admin pages">
        <div class="container">
            {% include /admin/sidebar.tpl %}
            <div class="section">
                <div class="title">
                    <h2>{{"All Pages"}}</h2>
                    <button class="btn is--primary" style="position: absolute;top: 50px;right: 50px;margin: 0;padding: 10px;"><i class="fas fa-plus"></i></button>
                </div>
                <div class="content">
                   
                    <ul class="list">
                        {% foreach ($pages as $item): %}
                            <li class="list-item" data-id="{{$item->id}}">
                                <div class="list-item-header">
                                    <span class="title">{{$item->title}}</span><span class="slug">slug: {{$item->slug}}</span>
                                </div>
                                <div class="list-item-content">
                                    <form data-request="admin/page/edit">
                                        <h3 class="title">{{"Edit Page"}}</h3>
                                        <label for="title-{{$item->id}}">
                                            {{"Title"}} <span class="required" title="{{'Required'}}">*</span>
                                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Title of the page"}}</span></div>
                                            <input type="text" id="title-{{$item->id}}" name="title" value="{{$item->title}}" maxlength="64" placeholder="{{'Enter title'}}" required/>
                                        </label>
                                        <label for="slug-{{$item->id}}">
                                            {{"URL Slug"}} <span class="required" title="{{'Required'}}">*</span>
                                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Url to the page, starting with a slash<br>e.g. /imprint"}}</span></div>
                                            <input type="text" id="slug-{{$item->id}}" name="slug" value="{{$item->slug}}" maxlength="64" placeholder="{{'Enter url slug'}}" required/>
                                        </label>
                                        <label for="description-{{$item->id}}">
                                            {{"Description"}}
                                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Description of the page"}}</span></div>
                                            <input type="text" id="description-{{$item->id}}" name="description" value="{{$item->description}}" maxlength="160" placeholder="{{'Enter description'}}"/>
                                        </label>
                                        <label for="robots-{{$item->id}}">
                                            {{"Robots"}}
                                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Robots of the page<br>e.g. index, nofollow"}}</span></div>
                                            <input type="text" id="robots-{{$item->id}}" name="robots" value="{{$item->robots}}" maxlength="64" placeholder="{{'Enter robots'}}"/>
                                        </label>
                                        <label for="template-{{$item->id}}">
                                            {{"Template Path"}} <span class="required" title="{{'Required'}}">*</span>
                                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Path to the template file in the views directory<br>e.g. imprint.tpl"}}</span></div>
                                            <textarea id="template-{{$item->id}}" name="template" placeholder="{{'Enter template path'}}" required>
                                                {{$item->template}}
                                            </textarea>
                                            <script>
                                                CodeMirror.fromTextArea(document.getElementById('template-{{$item->id}}'), {
                                    lineNumbers: true,
                                    mode: "xml"
                                    });
                                              </script> 
                                        </label>
                                        <label for="role-{{$item->id}}">
                                            {{"Minimal Requirements"}} <span class="required" title="{{'Required'}}">*</span>
                                            <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Minimum allowed user role to access the page"}}</span></div>
                                            <select id="role-{{$item->id}}" name="role" required>
                                                <option value="1" {{($item->role == 1) ? "selected" : ""}}>{{"Blocked"}}</option>
                                                <option value="2" {{($item->role == 2) ? "selected" : ""}}>{{"Deactivated"}}</option>
                                                <option value="3" {{($item->role == 3) ? "selected" : ""}}>{{"Guest"}}</option>
                                                <option value="4" {{($item->role == 4) ? "selected" : ""}}>{{"User"}}</option>
                                                <option value="5" {{($item->role == 5) ? "selected" : ""}}>{{"Verified"}}</option>
                                                <option value="6" {{($item->role == 6) ? "selected" : ""}}>{{"Moderator"}}</option>
                                                <option value="7" {{($item->role == 7) ? "selected" : ""}}>{{"Administrator"}}</option>
                                            </select>
                                        </label>
                                        <br>
                                        <h3 class="title">{{"Additional Settings"}}</h3>
                                        {% foreach ($item->meta as $name => $value): %}
                                            <label for="{{$name}}-{{$item->id}}">{{$name}}
                                                <input type="hidden" name="name[]" value="{{$name}}"/>
                                                <input type="text" id="{{$name}}-{{$item->id}}" name="value[]" value="{{$value}}" placeholder="{{'Enter value'}}"/>
                                            </label>
                                        {% endforeach; %}
                                        <label for="value[]-{{$item->id}}">
                                            <input type="text" name="name[]" placeholder="{{'Enter name'}}"/>
                                            <input type="text" id="value[]-{{$item->id}}" name="value[]" maxlength="64" placeholder="{{'Enter name'}}"/>
                                        </label>
                                        <br><br>
                                        <input type="hidden" name="id" value="{{$item->id}}"/>
                                        <button class="btn is--primary">{{"Save Changes"}}</button><a data-request="admin/page/delete" data-value="{{$item->id}}">{{"Delete Page"}}</a>
                                    </form> 
                                </div>
                            </li>
                        {% endforeach; %}
                    </ul>
                    <ul class="pagination">
                        {% if ($page->pagination->page > 1): %}
                            <li class="previous"><a data-request="admin/page/scroll" data-value="{{$page->pagination->page-1}}">{{$page->pagination->page-1}}</a></li>
                        {% endif; %}
                        <li class="current">{{$page->pagination->page}}</li>
                        {% if ($page->pagination->page < $page->pagination->pages): %}
                            <li class="next"><a data-request="admin/page/scroll" data-value="{{$page->pagination->page+1}}">{{$page->pagination->page+1}}</a></li>
                        {% endif; %}
                    </ul>

                </div>
            </div>
        </div>
    </main>





            <section class="section is--light">
                <div class="container">
                    {% include /admin/sidebar.tpl %}
                    <div class="main-content">
                        <h1 class="title">{{"Custom Pages"}}</h1>
                        <p>{{"Create, edit or delete custom pages."}}</p>

                        <h2 class="title">{{"Static Pages"}}</h2>
                        

                        <h2 class="title">{{"Custom Pages"}}</h2>
                        
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
                               
                                
                                <textarea id="template" name="template" placeholder="{{'Enter template path'}}" required>
                                    {{$page->template}}
                                </textarea>
                                <script>
                                    CodeMirror.fromTextArea(document.getElementById('template'), {
                        lineNumbers: true,
                        mode: "xml"
                        });
                                  </script> 
                    
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

        {% include /admin/footer.tpl %} 