{* 

    Welcome to your home.tpl! 

    This template overrides the original home.tpl from the source package. If you want, 
    you can override other templates as well by simply placing them into your 'views' directory.

    In your templates, you can use simple Smarty code. For example to include a file: 
    {% include /_includes/mytemplate.tpl %}
    
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

{% include /_includes/header.tpl %} 
        {% include /_includes/topbar.tpl %}

        <main class="page home">
            <section>
                <div class="container">
                    <div class="main-content">
                        <h1 class="title">{{"Homepage"}}</h1>
                        Welcome to your app! Please read the first instructions in the home.tpl in your 'views' directory!

                        {* 

                            You can execute any method from any controller by submitting a form with the data-request attribute:
                            <form data-request="my/example">
                                <input type="text" name="value" value="xyz"/>
                                <input type="submit" value="Call my/example"/>
                            </form>

                            ..or by clicking any link with the appropriate attributes:
                            <a href="#" data-request="my/example" data-value="xyz">Call my/example</a>

                            The scheme for your data-request attribute is always the same: Name of your Controller / Name of your Action, e.g. my/example
                            You can find the controller and action of this specific example in your 'classes/Controllers' directory! 
                        
                        *}
                    </div>
                </div>
            </section>
        </main>

{% include /_includes/footer.tpl %} 