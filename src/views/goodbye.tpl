{% include /_includes/header.tpl %} 
        {% include /_includes/topbar.tpl %}

        <main class="page goodbye">
            <section class="section">
                <div class="container">
                    <div class="main-content">
                        <h1 class="title">{{"It's a pity you're leaving.. :("}}</h1>
                        {{"..but we understand. We wish you all the best and hope to see you again very soon!"}}
                        <div class="alert success">
                            <div class="alert-content">
                                {{"Your account has been successfully deactivated."}} 
                                {{"If this was a mistake, you have 90 days to recover your account before it will be permanently deleted from our servers. You can click on this link to start the recovery:"}} <a href="{{$app->url}}/recovery">{{"Recover Account"}}</a>
                            </div>                           
                        </div>
                    </div>
                </div>
            </section>
        </main>

{% include /_includes/footer.tpl %} 