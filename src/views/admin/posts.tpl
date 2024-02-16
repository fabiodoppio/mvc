{% include /admin/header.tpl %} 

    {% include /admin/topbar.tpl %} 

    <main class="admin posts">
        <div class="container">
            {% include /admin/sidebar.tpl %}
            <div class="section">
                <div class="title">
                    <h2>{{"All Posts"}}</h2>
                    <p>{{"Neuigkeiten und Blogeinträge"}}</p>
                    <button class="btn is--primary" style="position: absolute;top: 50px;right: 50px;margin: 0;padding: 10px;"><i class="fas fa-plus"></i></button>
                </div>
                <div class="content">
                    <table>
                        <tr>
                            <td><i class="fas fa-image"></i></td><td>Title</td><td>Published</td><td></td>
                        </tr>
                        {% foreach ($posts as $item): %}
                            <tr>
                                <td>
                                    <div class="post-preview">

                                    </div>
                                </td><td>{{$item->title}}<br>tag, tag, tag</td><td>Veröffentlicht am<br>{{date("d.m.Y", strtotime($item->published))}}</td><td><i class="fas fa-ellipsis-vertical" data-trigger="dropdown"></i>
                                    <div class="dropdown">
                                        <ul>
                                            <li>Deaktivieren</li>
                                            <li><a data-trigger="modalbox-edit"><i class="fas fa-pen"></i> Bearbeiten</a></li>
                                            <li><i class="fas fa-trash"></i>Löschen</li>
                                        </ul>
                                    </div>
                                    <div class="modalbox edit">
                                        <div class="modal-content">
                                            <form data-request="admin/page/edit">
                                            <div class="modal-header">
                                                <span class="close">&times;</span>
                                                <h3>{{"Edit Page"}}</h3>
                                            </div>
                                            <div class="modal-body" style="display:flex;">
                                                <div style="width:70%;">
                                                    <label for="title-{{$item->id}}">
                                                        {{"Title"}} <span class="required" title="{{'Required'}}">*</span>
                                                        <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Title of the page"}}</span></div>
                                                        <input type="text" id="title-{{$item->id}}" name="title" value="{{$item->title}}" maxlength="64" placeholder="{{'Enter title'}}" required/>
                                                    </label>
                                                    <label for="template-{{$item->id}}">
                                                        {{"Template Path"}} <span class="required" title="{{'Required'}}">*</span>
                                                        <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Path to the template file in the views directory<br>e.g. imprint.tpl"}}</span></div>
                                                        <textarea id="template-{{$item->id}}" name="template" placeholder="{{'Enter template path'}}" required>
                                                            {{$item->content}}
                                                        </textarea>
                                                    </label>
                                                </div>
                                                <div style="width:30%; background:#EEE;">
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
                                                </div>
                                                    
                                                
                                            </div>
                                            <div class="modal-footer">
                                                <input type="hidden" name="id" value="{{$item->id}}"/>
                                                <button class="btn is--primary">{{"Save Changes"}}</button><a data-request="admin/page/delete" data-value="{{$item->id}}">{{"Delete Page"}}</a>
                                            </div>
                                        </form> 
                                        </div>
                                    </div>
                                    <div class="modalbox">
                                        <div class="modal-content">
                                            <span class="close">&times;</span>
                                            <p>Some text in the Modal..</p>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        {% endforeach; %}
                    </table>
                    <ul class="pagination">
                        {% if ($page->pagination->page > 1): %}
                            <li class="previous"><a href="{{$app->url}}/admin/posts?p={{$page->pagination->page-1}}">{{$page->pagination->page-1}}</a></li>
                        {% endif; %}
                        <li class="current">{{$page->pagination->page}}</li>
                        {% if ($page->pagination->page < $page->pagination->pages): %}
                            <li class="next"><a href="{{$app->url}}/admin/posts?p={{$page->pagination->page+1}}">{{$page->pagination->page+1}}</a></li>
                        {% endif; %}
                    </ul>
                </div>
            </div>        
        </div>
    </main>

    {% include /admin/footer.tpl %} 