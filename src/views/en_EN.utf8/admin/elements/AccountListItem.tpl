<li class="list-item" data-id="{{$item->get('id')}}">
    <div class="list-item-header">
        <span class="username">{{$item->get("username")}}</span><span class="id">ID: {{$item->get("id")}}</span>
    </div>
    <div class="list-item-content">
        <h3>Edit Account</h3>
        <div class="avatar">
            {% if ($item->get("avatar")): %}
                <img src="{{App::get('APP_URL')}}{{App::get('DIR_UPLOADS')}}/{{$item->get('avatar')}}"/>
            {% endif; %}
        </div>
        <form data-request="admin/account/avatar/upload">
            <label for="avatar-{{$item->get('id')}}"> <span class="btn is--primary">Upload Avatar</span>
                <input id="avatar-{{$item->get('id')}}" type="file" name="avatar" accept="image/*" hidden/>
                <input name="id" type="hidden" value="{{$item->get('id')}}"/>
            </label>
            <a class="btn is--secondary" data-request="admin/account/avatar/delete" data-value="{{$item->get('id')}}">Delete Avatar</a>
        </form>
        <form data-request="admin/account/edit">
            <label for="username-{{$item->get('id')}}">
                Username <span class="required" title="Mandatory">*</span>
                <input type="text" id="username-{{$item->get('id')}}" name="username" value="{{$item->get('username')}}" placeholder="Enter username" required/>
            </label>
            <label for="email-{{$item->get('id')}}">
                Email Address <span class="required" title="Mandatory">*</span>
                <input type="email" id="email-{{$item->get('id')}}" name="email" value="{{$item->get('email')}}" placeholder="Enter email address" required/>
            </label>
            <label for="displayname-{{$item->get('id')}}">
                Display name
                <input type="hidden" name="meta_name[]" value="displayname"/>
                <input type="text" id="displayname-{{$item->get('id')}}" name="meta_value[]" value="{{$item->get('displayname')}}" placeholder="Enter display name"/>
            </label>
            <label for="role-{{$item->get('id')}}"> 
                Role <span class="required" title="Mandatory">*</span>
                <select name="role" id="-{{$item->get('id')}}" required> 
                    <option value="1" {{($item->get('role') == 1) ? "selected" : ""}}>Blocked</option>
                    <option value="2" {{($item->get('role') == 2) ? "selected" : ""}}>Disabled</option>
                    <option value="3" {{($item->get('role') == 3) ? "selected" : ""}}>Guest</option>
                    <option value="4" {{($item->get('role') == 4) ? "selected" : ""}}>User</option>
                    <option value="5" {{($item->get('role') == 5) ? "selected" : ""}}>Verified</option>
                    <option value="6" {{($item->get('role') == 6) ? "selected" : ""}}>Moderator</option>
                    <option value="7" {{($item->get('role') == 7) ? "selected" : ""}}>Administrator</option>
                </select>
            </label>
            <label for="pw1-{{$item->get('id')}}">
                Change Password
                <input type="password" name="pw1" id="pw1-{{$item->get('id')}}" placeholder="Enter new password"/>
            </label>
            <label for="pw2-{{$item->get('id')}}">
                Repeat Password
                <input type="password" name="pw2" id="pw2-{{$item->get('id')}}" placeholder="Repeat new password"/>
            </label>
            <label>
                Registered Since
                <input type="text" value="{{date('d.m.Y H:i',strtotime($item->get('registered')))}}" disabled/>
            </label>
            <label>
                Last Active
                <input type="text" value="{{date('d.m.Y H:i',strtotime($item->get('lastaction')))}}" disabled/>
            </label>
            <br>
            <h3>Additional Settings</h3>
            {% $ignore = "'displayname', 'avatar'"; %}
            {% foreach (\MVC\Database::query("SELECT * FROM app_accounts_meta WHERE name NOT IN (".$ignore.") AND id = ?", [$item->get("id")]) as $key => $meta): %}
                <label for="meta_value[{{$key}}]-{{$item->get('id')}}">{{$meta['name']}}
                    <input type="hidden" name="meta_name[]" value="{{$meta['name']}}"/>
                    <input type="text" id="meta_value[{{$key}}]-{{$item->get('id')}}" name="meta_value[]" value="{{$meta['value']}}" placeholder="Enter value"/>
                </label>
            {% endforeach; %}
            <label for="meta_value[]-{{$item->get('id')}}">
                <input type="text" name="meta_name[]" placeholder="Enter name"/>
                <input type="text" id="meta_value[]-{{$item->get('id')}}" name="meta_value[]" placeholder="Enter value"/>
            </label>
            <br><br>
            <input type="hidden" name="id" value="{{$item->get('id')}}"/>
            <button class="btn is--primary">Save Changes</button><a class="btn is--secondary" data-request="admin/account/logout" data-value="{{$item->get('id')}}">Logout Account</a><a data-request="admin/account/delete" data-value="{{$item->get('id')}}">Delete Account</a>
        </form>
    </div>
</li>