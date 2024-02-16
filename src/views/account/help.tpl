{% include /header.tpl %} 
        {% include /topbar.tpl %} 

        <main class="account email">
            <section class="section is--light">
                <div class="container">
                    {% include /account/sidebar.tpl %}
                    <div class="main-content">
                        <h1 class="title">{{"Help"}}</h1>
                        <p>{{"Manage your newsletter preference."}}</p>
                        
                    </div>
                </div>
            </section>
            <div class="response"></div>
        </main>

        {% include /footer.tpl %} 
