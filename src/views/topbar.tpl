<header>
    <nav class="container">
        <div class="brand">
            <a href="{{$app->url}}" title="{{$app->name}}">
                {* Logo *}
            </a>
        </div>
        <ul class="menu">
            <li>Menu 1</li>
            <li>Menu 2</li>
            <li>Menu 3</li>
        </ul>
        <ul class="actions">
            <li>
                <a data-trigger="sidebar" title="{{'Toggle Sidebar'}}"><i class="fas fa-bars"></i></a>
            </li>
            <li>
                <a data-trigger="dropdown" title="{{'Select Language'}}"><i class="fas fa-globe"></i></a>
                <div class="dropdown locale">
                    <div class="dropdown-header">
                        {{"Language"}}
                    </div>
                    <ul>
                        <li><a data-request="account/locale" data-value="de_DE.utf8">Deutsch</a></li>
                        <li><a data-request="account/locale" data-value="en_EN.utf8">English</a></li>
                    </ul>
                </div>
            </li>
            <li>
                <a class="avatar" data-trigger="dropdown" title="{{$account->meta->displayname ?? $account->username}}">
                    {% if ($account->meta->avatar ?? null): %}
                        <img src="{{$account->meta->avatar}}" alt="_avatar" width="40" height="40"/>
                    {% endif; %}
                </a>
                <div class="dropdown account">
                    <div class="dropdown-header">
                        {{"My Account"}}
                    </div>
                    <ul>
                        {% if ($account->role >= 4): %}
                            <li><a href="{{$app->url}}/account/personal"><i class="fas fa-user"></i> {{"Personal Information"}}</a></li>
                            <li><a href="{{$app->url}}/account/security"><i class="fas fa-lock"></i> {{"Password & Security"}}</a></li>
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