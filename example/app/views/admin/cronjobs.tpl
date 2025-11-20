{% include /_includes/header.tpl %}
        {% include /_includes/topbar.tpl %}

        <main>
            <div class="container">
                {% include /admin/_includes/sidebar.tpl %}
                <div class="main-content is--fading">
                    <h1 class="title">{{"Scheduled Tasks"}}</h1>
                    <p>{{"Scheduled tasks ensure that a specific function is executed at a specific time. To use this feature, please set up a cron job to call the URL <b>%s/cron</b> regularly.", $app->url}}</p>
                    {% if (!$app->cron): %}
                        <div class="alert is--error">{{"Scheduled Tasks are <b>not</b> activated. Please set APP_CRON in your .env file."}}</div>
                    {% else: %}
                        {% foreach ($var->result as $item): %}
                            {% if ($item->is_delayed()): %}
                                <div class="alert is--warning">{{"One or more scheduled tasks are delayed. Please check your cron job setup."}}</div>
                                {% break; %}
                            {% endif; %}
                        {% endforeach; %}
                    {% endif; %}
                    <br/>
                    <table>
                        <tr>
                            <th>{{"Task"}}</th>
                            <th>{{"Next Run"}}</th>
                            <th>{{"Last Run"}}</th>
                            <th><i class="fas fa-square-check"></i></th>
                            <th><i class="fas fa-cog"></i></th>
                        </tr>
                        {% foreach($var->result as $item): %}
                            <tr data-id="{{$item->get('id')}}">
                                <td>
                                    {{$item->get("name")}}
                                </td>
                                <td>
                                    {{(new \DateTime($item->get("next")?:""))->format("d.m.Y - H:i:s")}}
                                </td>
                                <td>
                                    {% if ($item->is_delayed()): %}
                                        <i class="fas fa-triangle-exclamation" title="{{'Delayed'}}" style="color:#F00;"></i>
                                    {% endif; %}
                                    {% if ($item->get("last")): %}
                                        {{(new \DateTime($item->get("last")))->format("d.m.Y - H:i:s")}}
                                    {% else: %}
                                        {{"Never"}}
                                    {% endif; %}
                                </td>
                                <td>
                                    {% if ($item->get("active") == 1): %}
                                        <i class="fas fa-square-check" style="color:#32cd32;"></i>
                                    {% else: %}
                                        <i class="fas fa-square-xmark" style="color:#F00;"></i>
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
                                                <a href="#" data-trigger="modalbox"><i class="fas fa-pencil"></i>{{'Edit Task'}}</a>
                                                <div class="modalbox">
                                                    <div>
                                                        <form data-request="admin/cronjob/edit">
                                                            <h3>{{"Task"}} #{{$item->get('id')}}</h3>
                                                            <input type="hidden" name="id" value="{{$item->get('id')}}"/>
                                                            <label for="name-{{$item->get('id')}}">
                                                                {{"Name"}} <span class="is--required" title="{{'Required'}}">*</span>
                                                                <input type="text" id="name-{{$item->get('id')}}" name="name" value="{{$item->get('name')}}" placeholder="{{'Enter task name'}}" autocomplete="off" required/>
                                                            </label>
                                                            <label for="action-{{$item->get('id')}}">
                                                                {{"Action"}} <span class="is--required" title="{{'Required'}}">*</span><div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"The scheme for your action has to be:<br/>Name of the controller slash (/) Name of the function<br/>within the controller, e.g. <i>my/example</i>"}}</span></div>
                                                                <input type="text" id="action-{{$item->get('id')}}" name="action" value="{{$item->get('action')}}" placeholder="{{'Enter task action'}}" autocomplete="off" required/>
                                                            </label>
                                                            <label for="period-{{$item->get('id')}}">
                                                                {{"Interval"}} <span class="is--required" title="{{'Required'}}">*</span>
                                                                <input type="number" id="period-{{$item->get('id')}}" name="period" value="{{$item->get('period')}}" min="1" step="1" placeholder="{{'Enter interval in minutes'}}" autocomplete="off" required/>
                                                            </label>
                                                            <label for="next-{{$item->get('id')}}">
                                                                {{"Next Run"}} <span class="is--required" title="{{'Required'}}">*</span>
                                                                <input type="datetime-local" id="next-{{$item->get('id')}}" name="next" value="{{$item->get('next')}}"  autocomplete="off"/>
                                                            </label>
                                                            <br/><br/>
                                                            <button class="btn is--primary is--submit">{{"Save Changes"}}</button>
                                                        </form>
                                                        <button class="btn is--secondary" data-trigger="close">{{"Abort"}}</button>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <a href="#" data-request="admin/cronjob/execute" data-id="{{$item->get('id')}}"><i class="fas fa-play"></i> {{'Execute Task'}}</a>
                                            </li>
                                            {% if ($item->get("active") == 1): %}
                                                <li>
                                                    <a href="#" data-request="admin/cronjob/deactivate" data-id="{{$item->get('id')}}" style="color:#F00;"><i class="fas fa-square-xmark"></i> {{'Deactivate Task'}}</a>
                                                </li>
                                            {% else: %}
                                                <li>
                                                    <a href="#" data-request="admin/cronjob/activate" data-id="{{$item->get('id')}}"><i class="fas fa-square-check"></i> {{'Activate Task'}}</a>
                                                </li>
                                            {% endif; %}
                                            <li>
                                                <a href="#" data-trigger="modalbox" style="color:#F00;"><i class="fas fa-trash"></i>{{'Delete Task'}}</a>
                                                <div class="modalbox">
                                                    <div>
                                                        <h3>{{"Are you sure?"}}</h3>
                                                        <p>{{"This task will be deleted immediately and permanently."}}</p>
                                                        <button data-request="admin/cronjob/delete" data-id="{{$item->get('id')}}" class="btn is--warning is--submit">{{"Delete Task"}}</button>
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
                                <a href="{{$app->url}}{{$request->uri}}?page=1"><i class="fas fa-angles-left"></i></a>
                            </li>
                        {% endif; %}
                        {% if ($var->pagination->page > 1): %}
                            <li class="previous">
                                <a href="{{$app->url}}{{$request->uri}}?page={{$var->pagination->page-1}}"><i class="fas fa-angle-left"></i></a>
                            </li>
                        {% endif; %}
                        {% if ($var->pagination->pages > 1): %}
                            <li class="current">
                                {{$var->pagination->page}} {{"of"}} {{$var->pagination->pages}}
                            </li>
                        {% endif; %}
                        {% if ($var->pagination->page < $var->pagination->pages): %}
                            <li class="next">
                                <a href="{{$app->url}}{{$request->uri}}?page={{$var->pagination->page+1}}"><i class="fas fa-angle-right"></i></a>
                            </li>
                        {% endif; %}
                        {% if ($var->pagination->page < $var->pagination->pages-1): %}
                            <li class="last">
                                <a href="{{$app->url}}{{$request->uri}}?page={{$var->pagination->pages}}"><i class="fas fa-angles-right"></i></a>
                            </li>
                        {% endif; %}
                    </ul>
                    <br/><br/>
                    <button data-trigger="modalbox" class="btn is--primary">{{"Create New Task"}}</button>
                    <div class="modalbox">
                        <div>
                            <h3>{{"Create New Task"}}</h3>
                            <form data-request="admin/cronjob/create">
                                <label for="name">
                                    {{"Name"}} <span class="is--required" title="{{'Required'}}">*</span>
                                    <input type="text" id="name" name="name" placeholder="{{'Enter task name'}}" autocomplete="off" required/>
                                </label>
                                <label for="action">
                                    {{"Action"}} <span class="is--required" title="{{'Required'}}">*</span><div class="tooltip"><i class="fas fa-circle-info"></i><span>{{"The scheme for your action has to be:<br/>Name of the controller slash (/) Name of the function<br/>within the controller, e.g. <i>my/example</i>"}}</span></div>
                                    <input type="text" id="action" name="action" placeholder="{{'Enter task action'}}" autocomplete="off" required/>
                                </label>
                                <label for="period">
                                    {{"Interval"}} <span class="is--required" title="{{'Required'}}">*</span>
                                    <input type="number" id="period" name="period" min="1" step="1" placeholder="{{'Enter interval in minutes'}}" autocomplete="off" required/>
                                </label>
                                <button class="btn is--primary is--submit">{{"Create Task"}}</button>
                            </form>
                            <button class="btn is--secondary" data-trigger="close">{{"Abort"}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </main>

{% include /_includes/footer.tpl %}