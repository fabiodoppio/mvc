{* 

    Welcome to your home.tpl! 

    This template overrides the original home.tpl from the source package. If you want, 
    you can override other templates as well by simply placing them into your 'views' directory.

    In your templates, you can use simple Smarty code. For exmaple to include a file: 
    {% @include mytemplate.tpl %}
    
    ..or to display a variable: 
    {{$myvar}}

    It's also allowed to use PHP code like this: 
    {% myfunction(); %}

    ..or this:
    {% if (Condition): %}
        My Text
    {% endif; %}

    If you want to output a translated text, you can write your texts like this: 
    {{"My text"}} 
    or
    {{"My %s text", $myvar}}

    (But don't forget to update your language files in your 'locale' directory!)

*}

{% include /header.tpl %} 
        {% include /topbar.tpl %}

        <main class="page home">
            <section class="section">
                <div class="container">
                    <div class="main-content">
                        <h1 class="title">{{"Homepage"}}</h1>
                    </div>
                </div>
            </section>
        </main>

        {% include /footer.tpl %} 