{% include header.tpl %}

<main class="account personal">
    <section class="section is--light">
        <div class="container">
            {% include account/sidebar.tpl %}
            <div class="main-content">
                <h1 class="title">Personal Information</h1>
                <p>Manage your name and contact information used for invoicing. This personal data is private and will only be used for communication with you.</p>
                <form data-request="user/edit">
                    <h2>Contact Information</h2>
                    <label for="email">
                        Email Address <span class="required" title="Required">*</span>
                        <input type="email" id="email" name="email" placeholder="Enter email address" value="{{$account->get('email')}}" required/>
                    </label>
                    <h2>Address</h2>
                    <label for="company">
                        Company
                        <input type="hidden" name="meta_name[0]" value="company"/>
                        <input type="text" id="company" name="meta_value[0]" placeholder="Enter company (optional)" value="{{$account->get('company')}}"/>
                    </label>
                    <label for="firstname">
                        First Name <span class="required" title="Required">*</span>
                        <input type="hidden" name="meta_name[1]" value="firstname"/>
                        <input type="text" id="firstname" name="meta_value[1]" placeholder="Enter first name" value="{{$account->get('firstname')}}" required/>
                    </label>
                    <label for="lastname">
                        Last Name <span class="required" title="Required">*</span>
                        <input type="hidden" name="meta_name[2]" value="lastname"/>
                        <input type="text" id="lastname" name="meta_value[2]" placeholder="Enter last name" value="{{$account->get('lastname')}}" required/>
                    </label>
                    <label for "street">
                        Street / House Number <span class="required" title="Required">*</span>
                        <input type="hidden" name="meta_name[3]" value="street"/>
                        <input type="text" id="street" name="meta_value[3]" placeholder="Enter street / house number" value="{{$account->get('street')}}" required/>
                    </label>
                    <label for="postal">
                        ZIP Code <span class="required" title="Required">*</span>
                        <input type="hidden" name="meta_name[4]" value="postal"/>
                        <input type="text" id="postal" name="meta_value[4]" placeholder="Enter ZIP code" value="{{$account->get('postal')}}" required/>
                    </label>
                    <label for="city">
                        City <span class="required" title="Required">*</span>
                        <input type="hidden" name="meta_name[5]" value="city"/>
                        <input type="text" id="city" name="meta_value[5]" placeholder="Enter city" value="{{$account->get('city')}}" required/>
                    </label>
                    <label for="country">
                        Country <span class="required" title="Required">*</span>
                        <input type="hidden" name="meta_name[6]" value="country"/>
                        <input type="text" id="country" name="meta_value[6]" placeholder="Enter country" required/>
                    </label>
                    <div class="response"></div>
                    <button>Save Changes</button>
                </form>                
            </div>
        </div>
    </section>
</main>

{% include footer.tpl %}