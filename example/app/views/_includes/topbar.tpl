<header>
    <nav class="container">
        <div class="mobile" data-trigger="menu" title="{{'Main Menu'}}"><i class="fas fa-bars"></i></div>
        <div class="brand">
            <a href="{{$app->url}}" title="{{'Homepage'}}"><i class="fa-solid fa-shield-cat"></i></a>
        </div>
        </div>
        <ul class="menu">
            <li><a href="{{$app->url}}">{{"Homepage"}}</a></li>
            <li><a href="#">Menu 1</a></li>
            <li><a href="#">Menu 2</a></li>
            <li><a href="#">Menu 3</a></li>
        </ul>
        <ul class="cta">
            <li>
                <a href="{{$app->url}}/contact" title="{{'Contact'}}"><i class="fas fa-envelope"></i></a>
            </li>
            <li>
                <div class="avatar" data-trigger="dropdown" title="{{$account->meta->displayname ?? $account->username}}">
                    {% if ($account->role >= $account->roles->user && !empty($account->meta->avatar)): %}
                        <img src="{{$app->url}}/media/avatars/{{$account->meta->avatar}}" alt="_avatar"/>
                    {% endif; %}
                </div>
                <div class="dropdown">
                    {% if ($account->role == $account->roles->administrator): %}
                        <div class="dropdown-header">
                            {{"Administration"}}
                        </div>
                        <ul>
                            <li><a href="{{$app->url}}/admin/accounts"><i class="fas fa-users"></i> {{"All Accounts"}}</a></li>
                            <li><a href="{{$app->url}}/admin/pages"><i class="fas fa-file"></i> {{"Custom Pages"}}</a></li>
                            <li><a href="{{$app->url}}/admin/filters"><i class="fas fa-filter"></i> {{"Filter Settings"}}</a></li>
                            <li><a href="{{$app->url}}/admin/newsletter"><i class="fas fa-newspaper"></i> {{"Newsletter"}}</a></li>
                        </ul>
                    {% endif; %}
                    <div class="dropdown-header">
                        {{"My Account"}}
                    </div>
                    <ul>
                        {% if ($account->role >= $account->roles->user): %}
                            <li><a href="{{$app->url}}/account/personal"><i class="fas fa-user"></i> {{"Personal Data"}}</a></li>
                            <li><a href="{{$app->url}}/account/security"><i class="fas fa-lock"></i> {{"Account & Security"}}</a></li>
                            <li><a href="{{$app->url}}/account/email"><i class="fas fa-envelope"></i> {{"Email Settings"}}</a></li>
                            <li><div class="dropdown-divider"></div></li>
                            <li><a href="{{$app->url}}/logout"><i class="fas fa-right-from-bracket"></i> {{"Log Out"}}</a></li>
                        {% else: %}
                            {% if ($app->login): %}
                                <li><a href="{{$app->url}}/login"><i class="fas fa-right-to-bracket"></i> {{"Log In"}}</a></li>
                            {% endif; %}
                            {% if ($app->signup): %}
                                <li><a href="{{$app->url}}/signup"><i class="fas fa-user-plus"></i> {{"Sign Up"}}</a></li>
                            {% endif; %}
                        {% endif; %}
                    </ul>
                </div>
            </li>
        </ul>
    </nav>
</header>