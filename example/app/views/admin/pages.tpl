{% include /_includes/header.tpl %}
        {% include /_includes/topbar.tpl %}

        <main>
            <div class="container">
                {% include /admin/_includes/sidebar.tpl %}
                <div class="main-content is--fading">
                    <h1 class="title">{{"Custom Pages"}}</h1>
                    <p>{{"Manage all custom pages or create a new one. Using these search filters, <b>%s</b> custom pages were found.", $var->count}}</p>
                    <form class="filter" method="GET">
                        <input type="text" name="search" placeholder="Search.." value="{{$request->get->search}}"/>
                        <button class="btn is--primary">{{"Search"}}</button>
                    </form>
                    <table>
                        <tr>
                            <th>{{"Title"}}</th>
                            <th>{{"URL Slug"}}</th>
                            <th><i class="fas fa-square-check"></i></th>
                            <th><i class="fas fa-cog"></i></th>
                        </tr>
                        {% foreach($var->result as $item): %}
                            <tr data-id="{{$item->get('id')}}">
                                <td>
                                    {{$item->get("title")}}
                                </td>
                                <td>
                                    {{$item->get("slug")}}
                                </td>
                                <td>
                                    {% if ($item->get("active") == 1): %}
                                        <i class="fa-solid fa-square-check" style="color:#32cd32;"></i>
                                    {% else: %}
                                        <i class="fa-solid fa-square-xmark" style="color:#F00;"></i>
                                    {% endif; %}
                                </td>
                                <td>
                                    <a href="#" data-trigger="dropdown" title="{{'Settings'}}"><i class="fas fa-cog"></i></a>
                                    <div class="dropdown">
                                        <div class="dropdown-header">
                                            {{"Settings"}}
                                        </div>
                                        <ul>
                                            <li>
                                                <a href="#" data-trigger="modalbox"><i class="fas fa-pencil"></i>{{'Edit Page'}}</a>
                                                <div class="modalbox">
                                                    <div>
                                                        <form data-request="admin/page/edit">
                                                            <h3>{{"Page"}} #{{$item->get('id')}}</h3>
                                                            <input type="hidden" name="id" value="{{$item->get('id')}}"/>
                                                            <label for="title-{{$item->get('id')}}">
                                                                {{"Page Title"}} <span class="is--required" title="{{'Required'}}">*</span>
                                                                <input type="text" id="title-{{$item->get('id')}}" name="title" value="{{$item->get('title')}}" placeholder="{{'Enter page title'}}" autocomplete="off" required/>
                                                            </label>
                                                            <label for="slug-{{$item->get('id')}}">
                                                                {{"URL Slug"}} <span class="is--required" title="{{'Required'}}">*</span><div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your URL slug must start with a slash (/) and<br/>can be either simple or a regular expression."}}</span></div>
                                                                <input type="text" id="slug-{{$item->get('id')}}" name="slug" value="{{$item->get('slug')}}" placeholder="{{'Enter URL slug'}}" autocomplete="off" required/>
                                                            </label>
                                                            <label for="template-{{$item->get('id')}}">
                                                                {{"Template File"}} <span class="is--required" title="{{'Required'}}">*</span><div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"The path to your template file (.tpl) must be within<br/>your views directory and must start with a slash (/)."}}</span></div>
                                                                <input type="text" id="template-{{$item->get('id')}}" name="template" value="{{$item->get('template')}}" placeholder="{{'Enter template file'}}" autocomplete="off" required/>
                                                            </label>
                                                            <label for="description-{{$item->get('id')}}">
                                                                {{"Description"}}
                                                                <textarea id="description-{{$item->get('id')}}" name="description" placeholder="{{'Enter description'}}">{{$item->get('description')}}</textarea>
                                                            </label>
                                                            <label for="robots-{{$item->get('id')}}">
                                                                {{"Robots"}}
                                                                <input type="text" id="robots-{{$item->get('id')}}" name="robots" value="{{$item->get('robots')}}" placeholder="{{'Enter robots'}}" autocomplete="off"/>
                                                            </label>
                                                            <label for="classes-{{$item->get('id')}}">
                                                                {{"Classes"}} <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"All classes added to the body tag."}}</span></div>
                                                                <input type="text" id="classes-{{$item->get('id')}}" name="class" value="{{$item->get('class')}}" placeholder="{{'Enter classes'}}" autocomplete="off"/>
                                                            </label>
                                                            <h4>{{"Requirements"}}</h4>
                                                            <label for="requirement-{{$item->get('id')}}">
                                                                {{"Minimum Role"}} <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"The minimum role a visitor must have to access the page."}}</span></div>
                                                                <select id="requirement-{{$item->get('id')}}" name="requirement" autocomplete="off">
                                                                    <option value="0" {{(($item->get("requirement") == 0)?"selected":"")}}>{{"No Specific"}}</option>
                                                                    <option value="{{$account->roles->blocked}}" {{(($item->get("requirement") == $account->roles->blocked)?"selected":"")}}>{{"Blocked"}}</option>
                                                                    <option value="{{$account->roles->deactivated}}" {{(($item->get("requirement") == $account->roles->deactivated)?"selected":"")}}>{{"Deactivated"}}</option>
                                                                    <option value="{{$account->roles->guest}}" {{(($item->get("requirement") == $account->roles->guest)?"selected":"")}}>{{"Guest"}}</option>
                                                                    <option value="{{$account->roles->user}}" {{(($item->get("requirement") == $account->roles->user)?"selected":"")}}>{{"Not Verified"}}</option>
                                                                    <option value="{{$account->roles->verified}}" {{(($item->get("requirement") == $account->roles->verified)?"selected":"")}}>{{"User"}}</option>
                                                                    <option value="{{$account->roles->supporter}}" {{(($item->get("requirement") == $account->roles->supporter)?"selected":"")}}>{{"Supporter"}}</option>
                                                                    <option value="{{$account->roles->moderator}}" {{(($item->get("requirement") == $account->roles->moderator)?"selected":"")}}>{{"Moderator"}}</option>
                                                                    <option value="{{$account->roles->administrator}}" {{(($item->get("requirement") == $account->roles->administrator)?"selected":"")}}>{{"Administrator"}}</option>
                                                                </select>
                                                            </label>
                                                            <h4>{{"Custom Fields"}}</h4>
                                                            {% $i = 0; %}
                                                            {% foreach($item->get("meta")??[] as $name => $meta): %}
                                                                {% if (!in_array($name, $var->protected)): %}
                                                                    <div class="element">
                                                                        <label for="metaname-{{$i}}-{{$item->get('id')}}">
                                                                            {{"Name"}}
                                                                            <input id="metaname-{{$i}}-{{$item->get('id')}}" type="text" name="custom[name][{{$i}}]" value="{{$name}}" readonly/>
                                                                        </label>
                                                                        <label for="metavalue-{{$i}}-{{$item->get('id')}}">
                                                                            {{"Value"}}
                                                                            <input id="metavalue-{{$i}}-{{$item->get('id')}}" type="text" name="custom[value][{{$i}}]" placeholder="{{'Enter value'}}" value="{{$meta}}"/>
                                                                        </label>
                                                                    </div>
                                                                    {% $i++; %}
                                                                {% endif; %}
                                                            {% endforeach; %}
                                                            <div class="element">
                                                                <label for="metaname-{{$i}}-{{$item->get('id')}}">
                                                                    {{"Name"}}
                                                                    <input id="metaname-{{$i}}-{{$item->get('id')}}" type="text" name="custom[name][{{$i}}]" value="" placeholder="{{'Enter name'}}" maxlength="64"/>
                                                                </label>
                                                                <label for="metavalue-{{$i}}-{{$item->get('id')}}">
                                                                    {{"Value"}}
                                                                    <input id="metavalue-{{$i}}-{{$item->get('id')}}" type="text" name="custom[value][{{$i}}]" value="" placeholder="{{'Enter value'}}"/>
                                                                </label>
                                                            </div>
                                                            <button class="btn is--secondary" data-trigger="addElement">
                                                                {{"Add Field"}}
                                                            </button>
                                                            <h4>{{"Additional Settings"}}</h4>
                                                            <label for="maintenance-{{$item->get('id')}}">
                                                                <input type="hidden" name="maintenance" value="0">
                                                                <input type="checkbox" id="maintenance-{{$item->get('id')}}" name="maintenance" value="1" {{(($item->get("maintenance") == 1)?"checked":"")}}/> {{"Enable maintenance mode if enabled globally."}}
                                                            </label>
                                                            <br/><br/>
                                                            <button class="btn is--primary is--submit">{{"Save Changes"}}</button>
                                                        </form>
                                                        <button class="btn is--secondary" data-trigger="close">{{"Abort"}}</button>
                                                    </div>
                                                </div>
                                            </li>
                                            {% if ($item->get("active") == 1): %}
                                                <li>
                                                    <a href="#" data-request="admin/page/deactivate" data-id="{{$item->get('id')}}" style="color:#F00;"><i class="fas fa-square-xmark"></i> {{'Deactivate Page'}}</a>
                                                </li>
                                            {% else: %}
                                                <li>
                                                    <a href="#" data-request="admin/page/activate" data-id="{{$item->get('id')}}"><i class="fas fa-square-check"></i> {{'Activate Page'}}</a>
                                                </li>
                                            {% endif; %}
                                            <li>
                                                <a href="#" data-trigger="modalbox" style="color:#F00;"><i class="fas fa-trash"></i>{{'Delete Page'}}</a>
                                                <div class="modalbox">
                                                    <div>
                                                        <h3>{{"Are you sure?"}}</h3>
                                                        <p>{{"This page will be deleted immediately and permanently. The template file will remain unchanged in its folder."}}</p>
                                                        <button data-request="admin/page/delete" data-id="{{$item->get('id')}}" class="btn is--warning is--submit">{{"Delete Page"}}</button>
                                                        <button class="btn is--secondary" data-trigger="close">{{"Abort"}}</button>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        {% endforeach; %}
                    </table>
                    <ul class="pagination">
                        {% if ($var->pagination->page > 2): %}
                            <li class="first">
                                <a href="{{$app->url}}{{$request->uri}}?page=1{{$var->query}}"><i class="fas fa-angles-left"></i></a>
                            </li>
                        {% endif; %}
                        {% if ($var->pagination->page > 1): %}
                            <li class="previous">
                                <a href="{{$app->url}}{{$request->uri}}?page={{$var->pagination->page-1}}{{$var->query}}"><i class="fas fa-angle-left"></i></a>
                            </li>
                        {% endif; %}
                        {% if ($var->pagination->pages > 1): %}
                            <li class="current">
                                {{$var->pagination->page}} {{"of"}} {{$var->pagination->pages}}
                            </li>
                        {% endif; %}
                        {% if ($var->pagination->page < $var->pagination->pages): %}
                            <li class="next">
                                <a href="{{$app->url}}{{$request->uri}}?page={{$var->pagination->page+1}}{{$var->query}}"><i class="fas fa-angle-right"></i></a>
                            </li>
                        {% endif; %}
                        {% if ($var->pagination->page < $var->pagination->pages-1): %}
                            <li class="last">
                                <a href="{{$app->url}}{{$request->uri}}?page={{$var->pagination->pages}}{{$var->query}}"><i class="fas fa-angles-right"></i></a>
                            </li>
                        {% endif; %}
                    </ul>
                    <br/><br/>
                    <button data-trigger="modalbox" class="btn is--primary">{{"Create New Page"}}</button>
                    <div class="modalbox">
                        <div>
                            <h3>{{"Create New Page"}}</h3>
                            <form data-request="admin/page/create">
                                <label for="title">
                                    {{"Page Title"}} <span class="is--required" title="{{'Required'}}">*</span>
                                    <input type="text" id="title" name="title" placeholder="{{'Enter page title'}}" autocomplete="off" required/>
                                </label>
                                <label for="slug">
                                    {{"URL Slug"}} <span class="is--required" title="{{'Required'}}">*</span><div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your URL slug must start with a slash (/) and<br/>can be either simple or a regular expression."}}</span></div>
                                    <input type="text" id="slug" name="slug" placeholder="{{'Enter URL slug'}}" autocomplete="off" required/>
                                </label>
                                <label for="template">
                                    {{"Template File"}} <span class="is--required" title="{{'Required'}}">*</span><div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"The path to your template file (.tpl) must be within<br/>your views directory and must start with a slash (/)."}}</span></div>
                                    <input type="text" id="template" name="template" placeholder="{{'Enter template file'}}" autocomplete="off" required/>
                                </label>
                                <button class="btn is--primary is--submit">{{"Create Page"}}</button>
                            </form>
                            <button class="btn is--secondary" data-trigger="close">{{"Abort"}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

{% include /_includes/footer.tpl %}