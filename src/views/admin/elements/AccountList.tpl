<ul class="list">
    {% foreach ($accounts as $item): %}
        <li class="list-item" data-id="{{$item->id}}">
            <div class="list-item-header">
                <span class="avatar">{% if ($item->avatar): %}<img src="{{$app->url}}{{$directory->uploads}}/{{$item->avatar}}"/>{% endif; %}</span><span class="username">{{$item->username}}</span><span class="id">id: {{$item->id}}</span>
            </div>
            <div class="list-item-content">
                <h3 class="title">{{"Edit Account"}}</h3>
                <div class="avatar">
                    {% if ($item->avatar): %}
                        <img src="{{$app->url}}{{$directory->uploads}}/{{$item->avatar}}"/>
                    {% endif; %}
                </div>
                <form data-request="admin/account/avatar/upload">
                    <label for="avatar-{{$item->id}}"> <span class="btn is--primary">{{"Upload Avatar"}}</span>
                        <input id="avatar-{{$item->id}}" type="file" name="avatar" accept="image/*" hidden/>
                        <input name="id" type="hidden" value="{{$item->id}}"/>
                    </label>
                    <a class="btn is--secondary" data-request="admin/account/avatar/delete" data-value="{{$item->id}}">{{"Delete Avatar"}}</a>
                </form>
                <br><br>
                <form data-request="admin/account/edit">
                    <label for="username-{{$item->id}}">
                        {{"Username"}} <span class="required" title="{{'Required'}}">*</span>
                        <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"The username must be between 3 and 18 characters long<br>and cannot contain any special characters"}}</span></div>
                        <input type="text" id="username-{{$item->id}}" name="username" value="{{$item->username}}" minlength="3" maxlength="18" placeholder="{{'Enter username'}}" required/>
                    </label>
                    <label for="email-{{$item->id}}">
                        {{"Email Address"}} <span class="required" title="{{'Required'}}">*</span>
                        <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"The email address will be verified the next time the user access a page for verified user"}}</span></div>
                        <input type="email" id="email-{{$item->id}}" name="email" value="{{$item->email}}" maxlength="64" placeholder="{{'Enter email address'}}" required/>
                    </label>
                    <label for="displayname-{{$item->id}}">
                        {{"Display Name"}} <span class="required" title="{{'Required'}}">*</span>
                        <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"The display name of the user"}}</span></div>
                        <select id="displayname-{{$item->id}}" name="displayname" required>
                            {% $selected = ($item->displayname == $item->username) ? "selected" : ""; %}
                            <option value="{{$item->username}}" {{$selected}}>{{$item->username}}</option>
                            {% if ($item->company): %}
                                {% $selected = ($item->displayname == $item->company) ? "selected" : ""; %}
                                <option value="{{$item->company}}" {{$selected}}>{{$item->company}}</option>
                            {% endif; %}
                            {% if ($item->firstname): %}
                                {% $selected = ($item->displayname == $item->firstname) ? "selected" : ""; %}
                                <option value="{{$item->firstname}}" {{$selected}}>{{$item->firstname}}</option>
                            {% endif; %}
                            {% if ($item->lastname): %}
                                {% $selected = ($item->displayname == $item->lastname) ? "selected" : ""; %}
                                <option value="{{$item->lastname}}" {{$selected}}>{{$item->lastname}}</option>
                            {% endif; %}
                            {% if ($item->firstname && $item->lastname): %}
                                {% $selected = ($item->displayname == $item->firstname." ".$item->lastname) ? "selected" : ""; %}
                                <option value="{{$item->firstname}} {{$item->lastname}}" {{$selected}}>{{$item->firstname}} {{$item->lastname}}</option>
                                {% $selected = ($item->displayname == $item->lastname." ".$item->firstname) ? "selected" : ""; %}
                                <option value="{{$item->lastname}} {{$item->firstname}}" {{$selected}}>{{$item->lastname}} {{$item->firstname}}</option>
                            {% endif; %}
                        </select>
                    </label>
                    <label for="role-{{$item->id}}"> 
                        {{"Role"}} <span class="required" title="{{'Required'}}">*</span>
                        <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"The permission role of the user"}}</span></div>
                        <select name="role" id="-{{$item->id}}" required> 
                            <option value="1" {{($item->role == 1) ? "selected" : ""}}>{{"Blocked"}}</option>
                            <option value="2" {{($item->role == 2) ? "selected" : ""}}>{{"Deactivated"}}</option>
                            <option value="3" {{($item->role == 3) ? "selected" : ""}}>{{"Guest"}}</option>
                            <option value="4" {{($item->role == 4) ? "selected" : ""}}>{{"User"}}</option>
                            <option value="5" {{($item->role == 5) ? "selected" : ""}}>{{"Verified"}}</option>
                            <option value="6" {{($item->role == 6) ? "selected" : ""}}>{{"Moderator"}}</option>
                            <option value="7" {{($item->role == 7) ? "selected" : ""}}>{{"Administrator"}}</option>
                        </select>
                    </label>
                    <label for="pw1-{{$item->id}}">
                        {{"New Password"}}
                        <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"The password must be at least 8 characters long"}}</span></div>
                        <input type="password" name="pw1" id="pw1-{{$item->id}}" minlength="8" maxlength="64" placeholder="{{'Enter new password'}}"/>
                    </label>
                    <label for="pw2-{{$item->id}}">
                        {{"Repeat New Password"}}
                        <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"The password must be at least 8 characters long"}}</span></div>
                        <input type="password" name="pw2" id="pw2-{{$item->id}}" minlength="8" maxlength="64" placeholder="{{'Repeat new password'}}"/>
                    </label>
                    <label>
                        {{"Registered Since"}}
                        <input type="text" value="{{date('d.m.Y H:i',strtotime($item->registered))}}" disabled/>
                    </label>
                    <label>
                        {{"Last Action"}}
                        <input type="text" value="{{date('d.m.Y H:i',strtotime($item->lastaction))}}" disabled/>
                    </label>
                    <br>
                    <h3 class="title">{{"Additional Settings"}}</h3>
                    {% foreach ($item->metafields as $name => $value): %}
                        {% if ($name != "displayname" && $name != "avatar" && $name != "language"): %}
                            <label for="{{$name}}-{{$item->id}}">{{$name}}
                                <input type="hidden" name="name[]" value="{{$name}}"/>
                                <input type="text" id="{{$name}}-{{$item->id}}" name="value[]" value="{{$value}}" placeholder="{{'Enter value'}}"/>
                            </label>
                        {% endif; %}
                    {% endforeach; %}
                    <label for="value[]-{{$item->id}}">
                        <input type="text" name="name[]" maxlength="64" placeholder="{{'Enter name'}}"/>
                        <input type="text" id="value[]-{{$item->id}}" name="value[]" placeholder="{{'Enter value'}}"/>
                    </label>
                    <br><br>
                    <input type="hidden" name="id" value="{{$item->id}}"/>
                    <button class="btn is--primary">{{"Save Changes"}}</button><a class="btn is--secondary" data-request="admin/account/logout" data-value="{{$item->id}}">{{"Log Out Account"}}</a><a data-request="admin/account/delete" data-value="{{$item->id}}">{{"Delete Account"}}</a>
                </form>
            </div>
        </li>
    {% endforeach; %}
</ul>
<ul class="pagination">
    {% if ($page->pagination->page > 1): %}
        <li class="previous"><a data-request="admin/account/scroll" data-value="{{$page->pagination->page-1}}">{{$page->pagination->page-1}}</a></li>
    {% endif; %}
    <li class="current">{{$page->pagination->page}}</li>
    {% if ($page->pagination->page < $page->pagination->pages): %}
        <li class="next"><a data-request="admin/account/scroll" data-value="{{$page->pagination->page+1}}">{{$page->pagination->page+1}}</a></li>
    {% endif; %}
</ul>