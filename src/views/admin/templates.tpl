{% include /admin/header.tpl %} 

    {% include /admin/topbar.tpl %}
    
    <main class="templates">
        <div class="container">
            {% include /admin/sidebar.tpl %}
            <form data-request="admin/templates/edit">

            <div class="section">
                <div class="title">
                    <h2>{{"Header"}}</h2>
                    <p>{{"Add or edit your app settings."}}</p>
                </div>
                <div class="content no-padding">
                        
                     
                            <textarea id="template-css" name="template" placeholder="{{'Enter template path'}}" required>
                                {% echo $templates->header %}
                            </textarea>
                            <script>
                            CodeMirror.fromTextArea(document.getElementById('template-css'), {
                                lineNumbers: true,
                                mode: "xml",
                                lineWrapping: true
                                });
                          </script> 
                      
                        
                </div>
            </div>
            <div class="section" style="height:600px;">
                <div class="title">
                    <h2>{{"Home"}}</h2>
                </div>
                <div class="content no-padding">
                        
                       
                            <textarea id="template-home"  name="template" placeholder="{{'Enter template path'}}" required>
                                {% echo $templates->home %}
                            </textarea>
                            <script>
                            CodeMirror.fromTextArea(document.getElementById('template-home'), {
                                lineNumbers: true,
                                mode: "xml",
                                lineWrapping: true
                                });
                          </script> 
                </div>
            </div>
            <div class="section">
                <div class="title">
                    <h2>{{"Footer"}}</h2>
                    <p>{{"Add or edit your app settings."}}</p>
                </div>
                <div class="content no-padding">
                        
                       
                            <textarea id="template-js" name="template" placeholder="{{'Enter template path'}}" required>
                                {% echo $templates->footer %}
                            </textarea>
                            <script>
                            CodeMirror.fromTextArea(document.getElementById('template-js'), {
                                lineNumbers: true,
                                mode: "xml",
                                lineWrapping: true
                                });
                          </script> 
                </div>
            </div>
            <div class="section is-center">
                <button class="btn is--primary">{{"Save Changes"}}</button> <a class="btn is--secondary" data-request="admin/cache/clear">{{"Empty Cache"}}</a>
            </div>
        </form>
        </div>
    </main>
    {% include /admin/footer.tpl %}