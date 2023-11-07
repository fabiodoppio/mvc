<li class="list-item" data-id="{{$item->get('id')}}">
    <div class="list-item-header">
        <span class="username">{{$item->get("username")}}</span><span class="id">ID: {{$item->get("id")}}</span>
    </div>
    <div class="list-item-content">
        <form data-request="admin/account/edit">
            <h3>Edit Account</h3>
            <label for="username-{{$item->get('id')}}">
                Username <span class="required" title="Mandatory">*</span>
                <input type="text" id="username-{{$item->get('id')}}" name="username" value="{{$item->get('username')}}" placeholder="Enter username" required/>
            </label>
            <label for="email-{{$item->get('id')}}">
                Email Address <span class="required" title="Mandatory">*</span>
                <input type="email" id="email-{{$item->get('id')}}" name="email" value="{{$item->get('email')}}" placeholder="Enter email address" required/>
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
            {% $index = 0; %}
            <h3>Additional Settings</h3>
            {% foreach (\MVC\Database::select("app_accounts_meta", "name IS NOT NULL AND id = '".$item->get("id")."'") as $meta): %}
                <label for="meta_value[{{$index}}]-{{$item->get('id')}}">{{$meta['name']}}
                    <input type="hidden" name="meta_name[{{$index}}]" value="{{$meta['name']}}"/>
                    <input type="text" id="meta_value[{{$index}}]-{{$item->get('id')}}" name="meta_value[{{$index}}]" value="{{$meta['value']}}" placeholder="Enter value"/>
                </label>
                {% $index++; %}
            {% endforeach; %}
            <label for="meta_value[{{$index}}]-{{$item->get('id')}}">
                <input type="text" name="meta_name[{{$index}}]" placeholder="Enter name"/>
                <input type="text" id="meta_value[{{$index}}]-{{$item->get('id')}}" name="meta_value[{{$index}}]" placeholder="Enter value"/>
            </label>
            <br><br>
            <input type="hidden" name="id" value="{{$item->get('id')}}"/>
            <button class="btn is--primary">Save Changes</button><a class="btn is--secondary" data-request="admin/account/logout" data-value="{{$item->get('id')}}">Logout Account</a><a data-request="admin/account/delete" data-value="{{$item->get('id')}}">Delete Account</a>
        </form>
    </div>
</li>