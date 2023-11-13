{% include header.tpl %}

<main class="account personal">
    <section class="section is--light">
        <div class="container">
            {% include account/sidebar.tpl %}
            <div class="main-content">
                <h1 class="title">Personal Information</h1>
                <h2>Avatar</h2>
                <div class="avatar">
                    {% if ($account->get("avatar")): %}
                        <img src="{{App::get('APP_URL')}}{{App::get('DIR_UPLOADS')}}/{{$account->get('avatar')}}" alt="avatar"/>
                    {% endif; %}
                </div>
                <form data-request="user/edit/avatar/upload">
                    <label for="avatar"> <span class="btn is--primary">Upload Avatar</span>
                        <input id="avatar" type="file" name="avatar" accept="image/*" hidden/>
                    </label>
                    <a class="btn is--secondary" data-request="user/edit/avatar/delete" data-value="">Delete Avatar</a>
                </form>
                <br><br>
                <h2>Contact Information</h2>
                <form data-request="user/edit">
                    <label for="displayname">
                        Display name 
                        <input type="hidden" name="meta_name[]" value="displayname"/>
                        <input type="text" id="displayname" name="meta_value[]" placeholder="Enter display name (optional)" value="{{$account->get('displayname')}}"/>
                    </label>
                    <label for="email">
                        Email Address <span class="required" title="Required">*</span>
                        <input type="email" id="email" name="email" placeholder="Enter email address" value="{{$account->get('email')}}" required/>
                    </label>
                    <h2>Address</h2>
                    <label for="company">
                        Company
                        <input type="hidden" name="meta_name[]" value="company"/>
                        <input type="text" id="company" name="meta_value[]" placeholder="Enter company (optional)" value="{{$account->get('company')}}"/>
                    </label>
                    <label for="firstname">
                        First Name <span class="required" title="Required">*</span>
                        <input type="hidden" name="meta_name[]" value="firstname"/>
                        <input type="text" id="firstname" name="meta_value[]" placeholder="Enter first name" value="{{$account->get('firstname')}}" required/>
                    </label>
                    <label for="lastname">
                        Last Name <span class="required" title="Required">*</span>
                        <input type="hidden" name="meta_name[]" value="lastname"/>
                        <input type="text" id="lastname" name="meta_value[]" placeholder="Enter last name" value="{{$account->get('lastname')}}" required/>
                    </label>
                    <label for="street">
                        Street / House Number <span class="required" title="Required">*</span>
                        <input type="hidden" name="meta_name[]" value="street"/>
                        <input type="text" id="street" name="meta_value[]" placeholder="Enter street / house number" value="{{$account->get('street')}}" required/>
                    </label>
                    <label for="postal">
                        ZIP Code <span class="required" title="Required">*</span>
                        <input type="hidden" name="meta_name[]" value="postal"/>
                        <input type="text" id="postal" name="meta_value[]" placeholder="Enter ZIP code" value="{{$account->get('postal')}}" required/>
                    </label>
                    <label for="city">
                        City <span class="required" title="Required">*</span>
                        <input type="hidden" name="meta_name[]" value="city"/>
                        <input type="text" id="city" name="meta_value[]" placeholder="Enter city" value="{{$account->get('city')}}" required/>
                    </label>
                    <label for="country">
                        Country <span class="required" title="Required">*</span>
                        <input type="hidden" name="meta_name[]" value="country"/>
                        <input type="text" id="country" name="meta_value[]" placeholder="Enter country" value="{{$account->get('country')}}" required/>
                    </label>
                    <button class="btn is--primary">Save Changes</button>
                </form>                
            </div>
        </div>
        <div class="response"></div>
    </section>
</main>

{% include footer.tpl %}