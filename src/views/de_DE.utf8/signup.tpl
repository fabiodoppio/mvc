{% include header.tpl %}

    <main class="page signup">
        <section class="section is--light">
            <div class="container">
                <div class="main-content">
                <h2 class="title">Registrieren</h1>
                    <form data-request="account/signup">
                        <label for="username">Benutzername <span class="required" title="Pflichtfeld">*</span><br>
                        <input type="text" id="username" name="username" placeholder="Benutzername eingeben" autocomplete="off" required/></label><br>
                        <label for="email">E-Mail Adresse <span class="required" title="Pflichtfeld">*</span><br>
                        <input type="email" id="email" name="email" placeholder="E-Mail Adresse eingeben" autocomplete="off" required/></label><br>
                        <label for="pw1">Passwort <span class="required" title="Pflichtfeld">*</span><br>
                        <input type="password" id="pw1" name="pw1" placeholder="Passwort eingeben" autocomplete="off" required/></label><br>
                        <label for="pw2">Passwort wiederholen <span class="required" title="Pflichtfeld">*</span><br>
                        <input type="password" id="pw2" name="pw2" placeholder="Passwort wiederholen" autocomplete="off" required/></label><br>
                        <div class="response"></div>
                        <button>Registrieren</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

{% include footer.tpl %}