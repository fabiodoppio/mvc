<li class="list-item" data-id="{{$item->get('id')}}">
    <div class="list-item-header">
        <span class="username">{{$item->get("username")}}</span><span class="id">id: {{$item->get("id")}}</span>
    </div>
    <div class="list-item-content">
        <form data-request="admin/account/edit">
            <h3>Account bearbeiten</h3>
            <label for="username-{{$item->get('id')}}">
                Benutzername <span class="required" title="Pflichtfeld">*</span>
                <input type="text" id="username-{{$item->get('id')}}" name="username" value="{{$item->get('username')}}" placeholder="Benutzername eingeben" required/>
            </label>
            <label for="email-{{$item->get('id')}}">
                E-Mail Adresse <span class="required" title="Pflichtfeld">*</span>
                <input type="email" id="email-{{$item->get('id')}}" name="email" value="{{$item->get('email')}}" placeholder="E-Mail Adresse eingeben" required/>
            </label>
            <label for="role-{{$item->get('id')}}"> 
                Rolle <span class="required" title="Pflichtfeld">*</span>
                <select name="role" id="-{{$item->get('id')}}" required> 
                    <option value="1" {{($item->get('role') == 1) ? "selected" : ""}}>Gesperrt</option>
                    <option value="2" {{($item->get('role') == 2) ? "selected" : ""}}>Deaktiviert</option>
                    <option value="3" {{($item->get('role') == 3) ? "selected" : ""}}>Besucher:in</option>
                    <option value="4" {{($item->get('role') == 4) ? "selected" : ""}}>Benutzer:in</option>
                    <option value="5" {{($item->get('role') == 5) ? "selected" : ""}}>Verifiziert</option>
                    <option value="6" {{($item->get('role') == 6) ? "selected" : ""}}>Moderator:in</option>
                    <option value="7" {{($item->get('role') == 7) ? "selected" : ""}}>Administrator:in</option>
                </select>
            </label>
            <label for="pw1-{{$item->get('id')}}">
                Passwort ändern
                <input type="password" name="pw1" id="pw1-{{$item->get('id')}}" placeholder="Neues Passwort eingeben"/>
            </label>
            <label for="pw2-{{$item->get('id')}}">
                Passwort wiederholen
                <input type="password" name="pw2" id="pw2-{{$item->get('id')}}" placeholder="Neues Passwort wiederholen"/>
            </label>
            <label>
                Registriert seit
                <input type="text" value="{{date('d.m.Y H:i',strtotime($item->get('registered')))}}" disabled/>
            </label>
            <label>
                Zuletzt aktiv
                <input type="text" value="{{date('d.m.Y H:i',strtotime($item->get('lastaction')))}}" disabled/>
            </label>
            <br>
            <h3>Zusätzliche Einstellungen</h3>
            {% $index = 0; %}
            {% foreach (\MVC\Database::select("app_accounts_meta", "name IS NOT NULL AND id = '".$item->get("id")."'") as $meta): %}
                <label for="meta_value[{{$index}}]-{{$item->get('id')}}">{{$meta['name']}}
                    <input type="hidden" name="meta_name[{{$index}}]" value="{{$meta['name']}}"/>
                    <input type="text" id="meta_value[{{$index}}]-{{$item->get('id')}}" name="meta_value[{{$index}}]" value="{{$meta['value']}}" placeholder="Wert eingeben"/>
                </label>
                {% $index++; %}
            {% endforeach; %}
            <label for="meta_value[{{$index}}]-{{$item->get('id')}}">
                <input type="text" name="meta_name[{{$index}}]" placeholder="Name eingeben"/>
                <input type="text" id="meta_value[{{$index}}]-{{$item->get('id')}}" name="meta_value[{{$index}}]" placeholder="Wert eingeben"/>
            </label>
            <br><br>
            <input type="hidden" name="id" value="{{$item->get('id')}}"/>
            <button class="btn is--primary">Änderungen speichern</button><a class="btn is--secondary" data-request="admin/account/logout" data-value="{{$item->get('id')}}">Account abmelden</a><a data-request="admin/account/delete" data-value="{{$item->get('id')}}">Account löschen</a>
        </form>
    </div>
</li>