<li class="list-item" data-id="{{$item->get('id')}}">
    <div class="list-item-header">
        <span class="title">{{$item->get("title")}}</span><span class="slug">slug: {{$item->get("slug")}}</span>
    </div>
    <div class="list-item-content">
        <form data-request="admin/page/edit">
            <h3>Seite bearbeiten</h3>
            <label for="title-{{$item->get('id')}}">
                Titel <span class="required" title="Pflichtfeld">*</span>
                <input type="text" id="title-{{$item->get('id')}}" name="title" value="{{$item->get('title')}}" placeholder="Titel eingeben" required/>
            </label>
            <label for="slug-{{$item->get('id')}}">
                URL-Slug <span class="required" title="Pflichtfeld">*</span>
                <input type="text" id="slug-{{$item->get('id')}}" name="slug" value="{{$item->get('slug')}}" placeholder="URL-Slug eingeben" required/>
            </label>
            <label for="description-{{$item->get('id')}}">
                Beschreibung
                <input type="text" id="description-{{$item->get('id')}}" name="description" value="{{$item->get('description')}}" placeholder="Beschreibung eingeben"/>
            </label>
            <label for="robots-{{$item->get('id')}}">
                Robots
                <input type="text" id="robots-{{$item->get('id')}}" name="robots" value="{{$item->get('robots')}}" placeholder="Robots eingeben"/>
            </label>
            <label for="template-{{$item->get('id')}}">
                Template-Pfad <span class="required" title="Pflichtfeld">*</span>
                <input type="text" id="template-{{$item->get('id')}}" name="template" value="{{$item->get('template')}}" placeholder="Template-Pfad eingeben" required/>
            </label>
            <label for="role-{{$item->get('id')}}">
                Mindestanforderung <span class="required" title="Pflichtfeld">*</span>
                <select id="role-{{$item->get('id')}}" name="role" required>
                    <option value="1" {{($item->get('role') == 1) ? "selected" : ""}}>Gesperrt</option>
                    <option value="2" {{($item->get('role') == 2) ? "selected" : ""}}>Deaktiviert</option>
                    <option value="3" {{($item->get('role') == 3) ? "selected" : ""}}>Besucher:in</option>
                    <option value="4" {{($item->get('role') == 4) ? "selected" : ""}}>Benutzer:in</option>
                    <option value="5" {{($item->get('role') == 5) ? "selected" : ""}}>Verifiziert</option>
                    <option value="6" {{($item->get('role') == 6) ? "selected" : ""}}>Moderator:in</option>
                    <option value="7" {{($item->get('role') == 7) ? "selected" : ""}}>Administrator:in</option>
                </select>
            </label>
            <br><br>
            <input type="hidden" name="id" value="{{$item->get('id')}}"/>
            <button class="btn is--primary">Änderungen speichern</button><a data-request="admin/page/delete" data-value="{{$item->get('id')}}">Seite löschen</a>
        </form> 
    </div>
</li>