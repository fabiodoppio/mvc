<li class="list-item" data-id="{{$item->get('id')}}">
    <div class="list-item-header">
        <span class="title">{{$item->get("title")}}</span><span class="slug">slug: {{$item->get("slug")}}</span>
    </div>
    <div class="list-item-content">
        <form data-request="admin/page/edit">
            <h3>Edit Page</h3>
            <label for="title-{{$item->get('id')}}">
                Title <span class="required" title="Mandatory">*</span>
                <input type="text" id="title-{{$item->get('id')}}" name="title" value="{{$item->get('title')}}" placeholder="Enter title" required/>
            </label>
            <label for="slug-{{$item->get('id')}}">
                URL Slug <span class="required" title="Mandatory">*</span>
                <input type="text" id="slug-{{$item->get('id')}}" name="slug" value="{{$item->get('slug')}}" placeholder="Enter URL slug" required/>
            </label>
            <label for="description-{{$item->get('id')}}">
                Description
                <input type="text" id="description-{{$item->get('id')}}" name="description" value="{{$item->get('description')}}" placeholder="Enter description"/>
            </label>
            <label for="robots-{{$item->get('id')}}">
                Robots
                <input type="text" id="robots-{{$item->get('id')}}" name="robots" value="{{$item->get('robots')}}" placeholder="Enter robots"/>
            </label>
            <label for="template-{{$item->get('id')}}">
                Template Path <span class="required" title="Mandatory">*</span>
                <input type="text" id="template-{{$item->get('id')}}" name="template" value="{{$item->get('template')}}" placeholder="Enter template path" required/>
            </label>
            <label for="role-{{$item->get('id')}}">
                Minimum Requirement <span class="required" title="Mandatory">*</span>
                <select id="role-{{$item->get('id')}}" name="role" required>
                    <option value="1" {{($item->get('role') == 1) ? "selected" : ""}}>Blocked</option>
                    <option value="2" {{($item->get('role') == 2) ? "selected" : ""}}>Disabled</option>
                    <option value="3" {{($item->get('role') == 3) ? "selected" : ""}}>Guest</option>
                    <option value="4" {{($item->get('role') == 4) ? "selected" : ""}}>User</option>
                    <option value="5" {{($item->get('role') == 5) ? "selected" : ""}}>Verified</option>
                    <option value="6" {{($item->get('role') == 6) ? "selected" : ""}}>Moderator</option>
                    <option value="7" {{($item->get('role') == 7) ? "selected" : ""}}>Administrator</option>
                </select>
            </label>
            <br><br>
            <input type="hidden" name="id" value="{{$item->get('id')}}"/>
            <button class="btn is--primary">Save Changes</button><a data-request="admin/page/delete" data-value="{{$item->get('id')}}">Delete Page</a>
        </form> 
    </div>
</li>