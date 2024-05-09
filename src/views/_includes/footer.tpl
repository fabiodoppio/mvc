        <footer>
            <nav class="container">
                <ul class="menu-locale">
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
                </ul>
                <ul class="menu-legal">
                    <li><a href="{{$app->url}}/terms" title="{{'Terms'}}">{{"Terms"}}</a></li>
                    <li><a href="{{$app->url}}/imprint" title="{{'Imprint'}}">{{"Imprint"}}</a></li>
                    <li><a href="{{$app->url}}/privacy" title="{{'Privacy'}}">{{"Privacy"}}</a></li>
                </ul>
            </nav>
            <div class="copyright">&copy; {{date('Y')}} {{$app->name}} - All Rights Reserved. Powered by {{$framework->package}}</div>
        </footer>
    </body>
</html>