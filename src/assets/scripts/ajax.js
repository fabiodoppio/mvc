var cooldown = false;

function ajax_send(data) {
    data.append("client",$('body').attr("data-client")??0); 

	$.ajax({
		type:"POST",
		dataType:"JSON",
        async:true,
        cache:false,
        contentType:false,
        processData:false,
		url:window.location,
		data:data,
	}).fail(function(res) {
        $(".response").html('<div class="error">'+res.responseText+'</div>');
    }).done(function(res) {
        if (res.redirect) 
            window.location.replace(res.redirect);
        if (res.html) 
            $.each(res.html, function(target, value) {
                $(target).html(value);
            }); 
        if (res.prepend) 
            $.each(res.prepend, function(target, value) {
                $(target).prepend(value);
            }); 
        if (res.append) 
            $.each(res.append, function(target, value) {
                $(target).append(value);
            }); 
        if (res.remove) 
            $.each(res.remove, function(target, value) {
                $(target).remove();
            });
	}).always(function() {
        window.setTimeout(function() {
            cooldown = false;
            $('button').removeClass("loading");
         },2000);
    });
}

$(document).on("click", "[data-request]:not(form)", function(e) {
	e.preventDefault();
    var data = new FormData();
    data.append("request",$(this).attr("data-request"));
    data.append("value",$(this).attr("data-value"));
	ajax_send(data);
});

$(document).on("submit", "form[data-request]", function(e) {
	e.preventDefault();
    if (!cooldown) {
        cooldown = true;
        var data = new FormData($(this)[0]);
        for (const pair of data.entries())
            if (pair[1] == "")
                data.delete(pair[0]);
        data.append("request",$(this).attr("data-request"));
        ajax_send(data);
        $('input[type="password"]', this).val('');
        $('button', this).addClass("loading");
    }
});
