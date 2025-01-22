{*

    Welcome to your home.tpl!

    You can add templates by simply placing them into your 'views' directory.
    In your template files, you can use simple Smarty code. For example to include a file:

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

    (But don't forget to update your language files in your _locale_ directory!)

*}

{% include /_includes/header.tpl %}
        {% include /_includes/topbar.tpl %}

        <main class="is--fading">
            <div class="container">
                <h1 class="title">{{"Homepage"}}</h1>
                Welcome to your App! Please read the first instructions in the <i>home.tpl</i> in your <i>views</i> directory!
                <br/><br/><br/>
                You can execute any method from any controller by submitting a form with the <i>data-request</i> attribute:
                <br/><br/>
                <form data-request="my/example">
                    <input type="text" name="value" value="xyz"/>
                    <br/><br/>
                    <input type="submit" value="Call my/example"/>
                </form>
                <br/>
                ..or by clicking any link with the appropriate attributes: <a href="#" data-request="my/example" data-value="xyz">Call my/example</a>
                <br/><br/>
                The scheme for your <i>data-request</i> attribute is always the same: Name of your Controller / Name of your Action, e.g. <i>my/example</i><br/>
                You can find the controller and action of this specific example in your <i>classes/Controllers</i> directory.
            </div>
        </main>

{% include /_includes/footer.tpl %}