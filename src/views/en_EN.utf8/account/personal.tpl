{% include header.tpl %}

<main class="account personal">
    <section class="section is--light">
        <div class="container">
            {% include account/sidebar.tpl %}
            <div class="main-content">
                <h1 class="title">Personal Information</h1>
                <p>Manage your name and contact information used for invoicing. This personal data is private and will only be used for communication with you.</p>
                <form data-request="user/edit">
                    {% if (in_array("email", json_decode(App::get("META_PUBLIC")))): %}
                        <h2>Contact</h2>
                        <label for="email">Email Address <span class="required" title="Mandatory">*</span><br>
                        <input type="email" id="email" name="email" placeholder="Enter email address" value="{{$account->get('email')}}" required/></label>
                    {% endif; %}
                    <h2>Address</h2>
                    {% if (in_array("company", json_decode(App::get("META_PUBLIC")))): %}
                        <label for="company">Company<br>
                        <input type="text" id="company" name="company" placeholder="Enter company (optional)" value="{{$account->get('company')}}"/></label><br>
                    {% endif; %}
                    {% if (in_array("firstname", json_decode(App::get("META_PUBLIC")))): %}
                        <label for="firstname">First Name <span class="required" title="Mandatory">*</span><br>
                        <input type="text" id="firstname" name="firstname" placeholder="Enter first name" value="{{$account->get('firstname')}}" required/></label>
                    {% endif; %}    
                    {% if (in_array("lastname", json_decode(App::get("META_PUBLIC")))): %}
                        <label for="lastname">Last Name <span class="required" title="Mandatory">*</span><br>
                        <input type="text" id="lastname" name="lastname" placeholder="Enter last name" value="{{$account->get('lastname')}}" required/></label>
                    {% endif; %}
                    {% if (in_array("street", json_decode(App::get("META_PUBLIC")))): %}
                        <label for="street">Street / House Number <span class="required" title="Mandatory">*</span><br>
                        <input type="text" id="street" name="street" placeholder="Enter street / house number" value="{{$account->get('street')}}" required/></label><br>
                    {% endif; %}
                    {% if (in_array("postal", json_decode(App::get("META_PUBLIC")))): %}
                        <label for="postal">Postal Code <span class="required" title="Mandatory">*</span><br>
                        <input type="text" id="postal" name="postal" placeholder="Enter postal code" value="{{$account->get('postal')}}" required/></label>
                    {% endif; %}
                    {% if (in_array("city", json_decode(App::get("META_PUBLIC")))): %}
                        <label for="city">City <span class="required" title="Mandatory">*</span><br>
                        <input type="text" id="city" name="city" placeholder="Enter city" value="{{$account->get('city')}}" required/></label>
                    {% endif; %}
                    {% if (in_array("country", json_decode(App::get("META_PUBLIC")))): %}
                        <label for="country">Country <span class="required" title="Mandatory">*</span><br>
                        <input type="text" id="country" name="country" placeholder="Enter country" required/></label>
                    {% endif; %}
                    <div class="response"></div>
                    <button>Save Changes</button>
                </form>
            </div>
        </div>
    </section>
</main>

{% include footer.tpl %}