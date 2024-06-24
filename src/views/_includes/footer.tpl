        <footer>
            <nav class="container">
                <ul class="legal">
                    <li><a href="{{$app->url}}/terms" title="{{'Terms'}}">{{"Terms"}}</a></li>
                    <li><a href="{{$app->url}}/imprint" title="{{'Imprint'}}">{{"Imprint"}}</a></li>
                    <li><a href="{{$app->url}}/privacy" title="{{'Privacy'}}">{{"Privacy"}}</a></li>
                </ul>
                <div class="copyright">&copy; {{date('Y')}} {{$app->name}} - All Rights Reserved.<br/>Powered by <a href="{{$framework->url}}" target="_blank">{{$framework->package}}</a></div>
                {% if (count($app->languages) > 1): %}
                    <div class="locale">
                        <a href="#" data-trigger="dropdown" title="{{'Select Language'}}"><i class="fas fa-globe"></i> {{array_search($account->meta->language ?? $app->language, $app->languages)}} <i class="fas fa-angle-down"></i></a>
                        <div class="dropdown">
                            <div class="dropdown-header">
                                {{"Language"}}
                            </div>
                            <ul>
                                {% foreach($app->languages as $key => $value): %}
                                    <li><a href="#" data-request="account/locale" data-value="{{$value}}">{{$key}}</a></li>
                                {% endforeach; %}
                            </ul>
                        </div>
                    </div>
                {% endif; %}
                <div class="brand">
                    <a href="{{$app->url}}" title="{{'Homepage'}}"><i class="fa-solid fa-shield-cat"></i></a>
                </div>
            </nav>
        </footer>
    </body>
</html>