{% include head.tpl %} 
        {% include header.tpl %} 

        <main class="account dashboard">
            <section class="section is--light">
                <div class="container">
                    {% include account/sidebar.tpl %}
                    <div class="main-content">
                        <h1 class="title">{{"Hey %s", $account->displayname}},</h1>
                        <p>{{"welcome to your dashboard! From here, you can track every section of your account. You can also update your personal information or change your login credentials."}}</p>
                        {% if ($account->role < 5): %}
                            <span class="info">
                                {{"Your email address is not verified. <a href=\"%s/account/verify\">Verify now</a> to gain full access to all features of this app.", $app->url}}                               
                            </span>
                        {% endif; %}
                    </div>
                </div>
            </section>
        </main>

        {% include footer.tpl %} 
{% include foot.tpl %}