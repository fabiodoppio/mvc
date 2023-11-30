{% include head.tpl %} 
        {% include header.tpl %} 

        <main class="admin dashboard">
            <section class="section is--light">
                <div class="container">
                    {% include admin/sidebar.tpl %}
                    <div class="main-content">
                        <h1 class="title">{{"Administration"}}</h1>
                        <p>{{"This is the dashboard for admins. Please select the section in the left sidebar you want to configure."}}</p>
                    </div>
                </div>
            </section>
        </main>

        {% include footer.tpl %} 
{% include foot.tpl %} 