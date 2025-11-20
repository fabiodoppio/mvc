{% include /_includes/header.tpl %}
        {% include /_includes/topbar.tpl %}

        <main>
            <div class="container">
                {% include /admin/_includes/sidebar.tpl %}
                <div class="main-content is--fading">
                    <h1 class="title">{{"All Accounts"}}</h1>
                    <p>{{"Manage all registered accounts or create a new one. Using these search filters, <b>%s</b> accounts were found.", $var->count}}{% if (!isset($request->get->role)): %} {{"Blocked and deactivated accounts are hidden."}}{% endif; %}</p>
                    <form class="filter" method="GET">
                        <input type="text" name="search" placeholder="Search.." value="{{$request->get->search}}"/>
                        <select name="role">
                            <option value="">- {{"Select Role"}} -</option>
                            <option value="{{$account->roles->blocked}}" {{((($request->get->role??"") == $account->roles->blocked)?"selected":"")}}>{{"Blocked"}}</option>
                            <option value="{{$account->roles->deactivated}}" {{((($request->get->role??"") == $account->roles->deactivated)?"selected":"")}}>{{"Deactivated"}}</option>
                            <option value="{{$account->roles->user}}" {{((($request->get->role??"") == $account->roles->user)?"selected":"")}}>{{"Not Verified"}}</option>
                            <option value="{{$account->roles->verified}}" {{((($request->get->role??"") == $account->roles->verified)?"selected":"")}}>{{"User"}}</option>
                            <option value="{{$account->roles->supporter}}" {{((($request->get->role??"") == $account->roles->supporter)?"selected":"")}}>{{"Supporter"}}</option>
                            <option value="{{$account->roles->moderator}}" {{((($request->get->role??"") == $account->roles->moderator)?"selected":"")}}>{{"Moderator"}}</option>
                            <option value="{{$account->roles->administrator}}" {{((($request->get->role??"") == $account->roles->administrator)?"selected":"")}}>{{"Administrator"}}</option>
                        </select>
                        <button class="btn is--primary">{{"Search"}}</button>
                    </form>
                    <table>
                        <tr>
                            <th></th>
                            <th>{{"Username"}}</th>
                            <th>{{"Email Address"}}</th>
                            <th>{{"Role"}}</th>
                            <th>{{"Registered Since"}}</th>
                            <th><i class="fas fa-cog"></i></th>
                        </tr>
                        {% foreach($var->result as $item): %}
                            <tr data-id="{{$item->get('id')}}">
                                <td>
                                    <div class="avatar">
                                        {% if (!empty($item->get("avatar"))): %}
                                            <img src="{{$app->url}}/media/avatars/{{$item->get('avatar')}}" alt="_avatar"/>
                                        {% endif; %}
                                    </div>
                                </td>
                                <td>
                                    {{$item->get("username")}}
                                </td>
                                <td>
                                    <a href="mailto:{{$item->get('email')}}" title="{{'Send Email'}}">{{$item->get('email')}}</a>
                                </td>
                                <td>
                                    {{$item->get_role_name()}}
                                </td>
                                <td>
                                    {{(new \DateTime($item->get("registered")))->format('d.m.Y - H:i')}}
                                </td>
                                <td>
                                    <a href="#" data-trigger="dropdown" title="{{'Settings'}}"><i class="fas fa-cog"></i></a>
                                    <div class="dropdown">
                                        <div class="dropdown-header">
                                            {{"Settings"}}
                                        </div>
                                        <ul>
                                            <li>
                                                <a href="#" data-trigger="modalbox"><i class="fas fa-pencil"></i>{{'Edit Account'}}</a>
                                                <div class="modalbox">
                                                    <div>
                                                        <form data-request="admin/account/edit">
                                                            <h3>{{"Account"}} #{{$item->get('id')}}</h3>
                                                            <input type="hidden" name="id" value="{{$item->get('id')}}"/>
                                                            <h4>{{"Permissions"}}</h4>
                                                            <label for="role-{{$item->get('id')}}">
                                                                {{"Role"}} <span class="is--required" title="{{'Required'}}">*</span>
                                                                <select id="role-{{$item->get('id')}}" name="role" autocomplete="off">
                                                                    {% if ($item->get("role") == $account->roles->blocked): %}
                                                                        <option value="{{$account->roles->blocked}}" selected>{{"Blocked"}}</option>
                                                                    {% endif; %}
                                                                    {% if ($item->get("role") == $account->roles->deactivated): %}
                                                                        <option value="{{$account->roles->deactivated}}" selected>{{"Deactivated"}}</option>
                                                                    {% endif; %}
                                                                    <option value="{{$account->roles->user}}" {{(($item->get("role") == $account->roles->user)?"selected":"")}}>{{"Not Verified"}}</option>
                                                                    <option value="{{$account->roles->verified}}" {{(($item->get("role") == $account->roles->verified)?"selected":"")}}>{{"User"}}</option>
                                                                    <option value="{{$account->roles->supporter}}" {{(($item->get("role") == $account->roles->supporter)?"selected":"")}}>{{"Supporter"}}</option>
                                                                    <option value="{{$account->roles->moderator}}" {{(($item->get("role") == $account->roles->moderator)?"selected":"")}}>{{"Moderator"}}</option>
                                                                    <option value="{{$account->roles->administrator}}" {{(($item->get("role") == $account->roles->administrator)?"selected":"")}}>{{"Administrator"}}</option>
                                                                </select>
                                                            </label>
                                                            <h4>{{"Personal Data"}}</h4>
                                                            <label for="displayname-{{$item->get('id')}}">
                                                                {{"Display Name"}} <span class="is--required" title="{{'Required'}}">*</span>
                                                                <select id="displayname-{{$item->get('id')}}" name="displayname" autocomplete="off">
                                                                    {% $selected = (!empty($item->get('displayname')) && $item->get('displayname') == $item->get('username')) ? "selected" : ""; %}
                                                                    <option value="{{$item->get('username')}}" {{$selected}}>{{$item->get('username')}}</option>
                                                                    {% if (!empty($item->get('company'))): %}
                                                                        {% $selected = (!empty($item->get('displayname')) && $item->get('displayname') == $item->get('company')) ? "selected" : ""; %}
                                                                        <option value="{{$item->get('company')}}" {{$selected}}>{{$item->get('company')}}</option>
                                                                    {% endif; %}
                                                                    {% if (!empty($item->get('firstname'))): %}
                                                                        {% $selected = (!empty($item->get('displayname')) && $item->get('displayname') == $item->get('firstname')) ? "selected" : ""; %}
                                                                        <option value="{{$item->get('firstname')}}" {{$selected}}>{{$item->get('firstname')}}</option>
                                                                    {% endif; %}
                                                                    {% if (!empty($item->get('lastname'))): %}
                                                                        {% $selected = (!empty($item->get('displayname')) && $item->get('displayname') == $item->get('lastname')) ? "selected" : ""; %}
                                                                        <option value="{{$item->get('lastname')}}" {{$selected}}>{{$item->get('lastname')}}</option>
                                                                    {% endif; %}
                                                                    {% if (!empty($item->get('firstname')) && !empty($item->get('lastname'))): %}
                                                                        {% $selected = (!empty($item->get('displayname')) && $item->get('displayname') == $item->get('firstname')." ".$item->get('lastname')) ? "selected" : ""; %}
                                                                        <option value="{{$item->get('firstname')}} {{$item->get('lastname')}}" {{$selected}}>{{$item->get('firstname')}} {{$item->get('lastname')}}</option>
                                                                        {% $selected = (!empty($item->get('displayname')) && $item->get('displayname') == $item->get('lastname')." ".$item->get('firstname')) ? "selected" : ""; %}
                                                                        <option value="{{$item->get('lastname')}} {{$item->get('firstname')}}" {{$selected}}>{{$item->get('lastname')}} {{$item->get('firstname')}}</option>
                                                                    {% endif; %}
                                                                </select>
                                                            </label>
                                                            <label for="firstname-{{$item->get('id')}}">
                                                                {{"First Name"}}
                                                                <input type="text" id="firstname-{{$item->get('id')}}" name="firstname" maxlength="64" placeholder="{{'Enter first name'}}" value="{{$item->get('firstname')}}" autocomplete="off"/>
                                                            </label>
                                                            <label for="lastname-{{$item->get('id')}}">
                                                                {{"Last Name"}}
                                                                <input type="text" id="lastname-{{$item->get('id')}}" name="lastname" maxlength="64" placeholder="{{'Enter last name'}}" value="{{$item->get('lastname')}}" autocomplete="off"/>
                                                            </label>
                                                            <label for="street-{{$item->get('id')}}">
                                                                {{"Street / House Number"}}
                                                                <input type="text" id="street-{{$item->get('id')}}" name="street" maxlength="64" placeholder="{{'Enter street / house number'}}" value="{{$item->get('street')}}" autocomplete="off"/>
                                                            </label>
                                                            <label for="postal-{{$item->get('id')}}">
                                                                {{"ZIP Code"}}
                                                                <input type="text" id="postal-{{$item->get('id')}}" name="postal" maxlength="64" placeholder="{{'Enter zip code'}}" value="{{$item->get('postal')}}" autocomplete="off"/>
                                                                </label>
                                                            <label for="city-{{$item->get('id')}}">
                                                                {{"City"}}
                                                                <input type="text" id="city-{{$item->get('id')}}" name="city" maxlength="64" placeholder="{{'Enter city'}}" value="{{$item->get('city')}}" autocomplete="off"/>
                                                            </label>
                                                            <label for="country-{{$item->get('id')}}">
                                                                {{"Country"}}
                                                                <input type="text" id="country-{{$item->get('id')}}" name="country" maxlength="64" placeholder="{{'Enter country'}}" value="{{$item->get('country')}}" autocomplete="off"/>
                                                            </label>
                                                            <h4>{{"Business Data"}}</h4>
                                                            <label for="company-{{$item->get('id')}}">
                                                                {{"Company"}}
                                                                <input type="text" id="company-{{$item->get('id')}}" name="company" maxlength="64" placeholder="{{'Enter company'}}" value="{{$item->get('company')}}" autocomplete="off"/>
                                                            </label>
                                                            <label for="vat-{{$item->get('id')}}">
                                                                {{"VAT Number"}}
                                                                <input type="text" id="vat-{{$item->get('id')}}" name="vat" maxlength="64" placeholder="{{'Enter VAT number'}}" value="{{$item->get('vat')}}" autocomplete="off"/>
                                                            </label>
                                                            <h4>{{"Contact"}}</h4>
                                                            <label for="email-{{$item->get('id')}}">
                                                                {{"Email Address"}} <span class="is--required" title="{{'Required'}}">*</span>
                                                                <input type="email" id="email-{{$item->get('id')}}" name="email" maxlength="64" placeholder="{{'Enter email address'}}" value="{{$item->get('email')}}" autocomplete="off" required/>
                                                            </label>
                                                            <h4>{{"Login Credentials"}}</h4>
                                                            <label for="username-{{$item->get('id')}}">
                                                                {{"Username"}} <span class="is--required" title="{{'Required'}}">*</span>
                                                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your username must be between 3 and 18 characters long<br/>and cannot contain any special characters."}}</span></div>
                                                                <input type="text" id="username-{{$item->get('id')}}" minlength="3" maxlength="18" name="username" value="{{$item->get('username')}}" placeholder="{{'Enter username'}}"  autocomplete="off" required/>
                                                            </label>
                                                            <label for="pw1-{{$item->get('id')}}">
                                                                {{"New Password"}} <span class="is--required" title="{{'Required'}}">*</span>
                                                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your password must be at least 8 characters long."}}</span></div>
                                                                <input type="password" id="pw1-{{$item->get('id')}}" name="pw1" minlength="8" maxlength="64" placeholder="{{'Enter new password'}}" autocomplete="off"/>
                                                            </label>
                                                            <label for="pw2-{{$item->get('id')}}">
                                                                {{"Repeat New Password"}} <span class="is--required" title="{{'Required'}}">*</span>
                                                                <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your password must be at least 8 characters long."}}</span></div>
                                                                <input type="password" id="pw2-{{$item->get('id')}}" name="pw2" minlength="8" maxlength="64" placeholder="{{'Repeat new password'}}" autocomplete="off"/>
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
                                                                            <input id="metavalue-{{$i}}-{{$item->get('id')}}" type="text" name="custom[value][{{$i}}]" value="{{$meta}}" placeholder="{{'Enter value'}}" autocomplete="off"/>
                                                                        </label>
                                                                    </div>
                                                                    {% $i++; %}
                                                                {% endif; %}
                                                            {% endforeach; %}
                                                            <div class="element">
                                                                <label for="metaname-{{$i}}-{{$item->get('id')}}">
                                                                    {{"Name"}}
                                                                    <input id="metaname-{{$i}}-{{$item->get('id')}}" type="text" name="custom[name][{{$i}}]" placeholder="{{'Enter name'}}" maxlength="64" autocomplete="off"/>
                                                                </label>
                                                                <label for="metavalue-{{$i}}-{{$item->get('id')}}">
                                                                    {{"Value"}}
                                                                    <input id="metavalue-{{$i}}-{{$item->get('id')}}" type="text" name="custom[value][{{$i}}]" placeholder="{{'Enter value'}}" autocomplete="off"/>
                                                                </label>
                                                            </div>
                                                            <button class="btn is--secondary" data-trigger="addElement">
                                                                {{"Add Field"}}
                                                            </button>
                                                            <h4>{{"Statistics"}}</h4>
                                                            <label for="registered-{{$item->get('id')}}">
                                                                {{"Registered Since"}}
                                                                <input id="registered-{{$item->get('id')}}" type="text" value="{{(new \DateTime($item->get('registered')))->format('d.m.Y - H:i')}}" disabled/>
                                                            </label>
                                                            <label for="lastaction-{{$item->get('id')}}">
                                                                {{"Last Action"}}
                                                                <input id="lastaction-{{$item->get('id')}}" type="text" value="{{(new \DateTime($item->get('lastaction')))->format('d.m.Y - H:i')}}" disabled/>
                                                            </label>
                                                            <br/><br/>
                                                            <button class="btn is--primary is--submit">{{"Save Changes"}}</button>
                                                        </form>
                                                        <button class="btn is--secondary" data-trigger="close">{{"Abort"}}</button>
                                                    </div>
                                                </div>
                                            </li>
                                            {% if (!empty($item->get("avatar"))): %}
                                                <li>
                                                    <a href="#" data-trigger="modalbox"><i class="fas fa-circle-user"></i>{{'Delete Avatar'}}</a>
                                                    <div class="modalbox">
                                                        <div>
                                                            <h3>{{"Are you sure?"}}</h3>
                                                            <p></p>
                                                            <button data-request="admin/account/delete/avatar" data-id="{{$item->get('id')}}" class="btn is--warning is--submit">{{"Delete Avatar"}}</button>
                                                            <button class="btn is--secondary" data-trigger="close">{{"Abort"}}</button>
                                                        </div>
                                                    </div>
                                                </li>
                                            {% endif; %}
                                            {% if ($item->get("role") >= $account->roles->user): %}
                                                <li>
                                                    <a href="#" data-trigger="modalbox" style="color:#F00;"><i class="fas fa-ban"></i>{{'Block Account'}}</a>
                                                    <div class="modalbox">
                                                        <div>
                                                            <h3>{{"Are you sure?"}}</h3>
                                                            <p>{{"The account owner has no longer access to his account and cannot create a new account with these login credentials. The account owner can not restore his account."}}</p>
                                                            <button data-request="admin/account/block" data-id="{{$item->get('id')}}" class="btn is--warning is--submit">{{"Block Account"}}</button>
                                                            <button class="btn is--secondary" data-trigger="close">{{"Abort"}}</button>
                                                        </div>
                                                    </div>
                                                </li>
                                            {% else: %}
                                                <li>
                                                    <a href="#" data-request="admin/account/restore" data-id="{{$item->get('id')}}"><i class="fas fa-rotate-left"></i>{{'Restore Account'}}</a>
                                                </li>
                                            {% endif; %}
                                            {% if ($item->get("role") > $account->roles->deactivated): %}
                                                <li>
                                                    <a href="#" data-trigger="modalbox" style="color:#F00;"><i class="fas fa-trash"></i>{{'Deactivate Account'}}</a>
                                                    <div class="modalbox">
                                                        <div>
                                                            <h3>{{"Are you sure?"}}</h3>
                                                            <p>{{"The account owner has no longer access to his account and cannot create a new account with these login credentials. If cron jobs are activated, the account will be automatically and permanently deleted after 90 days. The account owner can restore his account during this time."}}</p>
                                                            <button data-request="admin/account/deactivate" data-id="{{$item->get('id')}}" class="btn is--warning is--submit">{{"Deactivate Account"}}</button>
                                                            <button class="btn is--secondary" data-trigger="close">{{"Abort"}}</button>
                                                        </div>
                                                    </div>
                                                </li>
                                            {% endif; %}
                                            {% if ($item->get("role") <= $account->roles->deactivated): %}
                                                <li>
                                                    <a href="#" data-trigger="modalbox" style="color:#F00;"><i class="fas fa-trash"></i>{{'Delete Account'}}</a>
                                                    <div class="modalbox">
                                                        <div>
                                                            <h3>{{"Are you sure?"}}</h3>
                                                            <p>{{"This account will be deleted immediately and permanently with all personal data."}}</p>
                                                            <button data-request="admin/account/delete" data-id="{{$item->get('id')}}" class="btn is--warning is--submit">{{"Delete Account"}}</button>
                                                            <button class="btn is--secondary" data-trigger="close">{{"Abort"}}</button>
                                                        </div>
                                                    </div>
                                                </li>
                                            {% endif; %}
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
                    <button data-trigger="modalbox" class="btn is--primary">{{"Create New Account"}}</button>
                    <div class="modalbox">
                        <div>
                            <h3>{{"Create New Account"}}</h3>
                            <form data-request="admin/account/create">
                                <label for="username">
                                    {{"Username"}} <span class="is--required" title="{{'Required'}}">*</span>
                                    <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your username must be between 3 and 18 characters long<br/>and cannot contain any special characters."}}</span></div>
                                    <input type="text" id="username" name="username" minlength="3" maxlength="18" placeholder="{{'Enter username'}}" autocomplete="off" required/>
                                </label>
                                <label for="email">
                                    {{"Email Address"}} <span class="is--required" title="{{'Required'}}">*</span>
                                    <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"You will have to verify your email address to<br/>gain full access to all features of this app."}}</span></div>
                                    <input type="email" id="email" name="email" maxlength="64" placeholder="{{'Enter email address'}}" autocomplete="off" required/>
                                </label>
                                <label for="pw1">
                                    {{"Password"}} <span class="is--required" title="{{'Required'}}">*</span>
                                    <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your password must be at least 8 characters long."}}</span></div>
                                    <input type="password" id="pw1" name="pw1" minlength="8" maxlength="64" placeholder="{{'Enter password'}}" autocomplete="off" required/>
                                    </label>
                                <label for="pw2">{{"Repeat Password"}} <span class="is--required" title="{{'Required'}}">*</span>
                                    <div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"Your password must be at least 8 characters long."}}</span></div>
                                    <input type="password" id="pw2" name="pw2" minlength="8" maxlength="64" placeholder="{{'Repeat password'}}" autocomplete="off" required/>
                                </label>
                                <button class="btn is--primary is--submit">{{"Create Account"}}</button>
                            </form>
                            <button class="btn is--secondary" data-trigger="close">{{"Abort"}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

{% include /_includes/footer.tpl %}