/**
 * 
 *  MVC
 *  Model View Controller (MVC) design pattern for simple web applications.
 *
 *  @see     https://github.com/fabiodoppio/mvc
 *
 *  @author  Fabio Doppio (Developer) <hallo@fabiodoppio.de>
 *  @license https://opensource.org/license/mit/ MIT License
 * 
 */

function ajax_send(e,a,t){if(ajax_send_before(a,t)&&($.ajax({type:"POST",dataType:"JSON",async:!0,cache:!1,contentType:!1,processData:!1,url:e,data:a,headers:{Authorization:("Bearer "+$("body").attr("data-token"))??0}}).fail(function(e){ajax_send_fail(e,t)&&$(".response",t).html('<div class="alert is--error">'+e.responseText+"</div>")}).done(function(e){ajax_send_done(e,t)&&(e.reload&&window.location.reload(!0),e.redirect&&window.location.replace(e.redirect),e.html&&$.each(e.html,function(e,a){$(e).html(a)}),e.replace&&$.each(e.replace,function(e,a){$(e).replaceWith(a)}),e.prepend&&$.each(e.prepend,function(e,a){$(e).prepend(a)}),e.append&&$.each(e.append,function(e,a){$(e).append(a)}),e.remove&&$.each(e.remove,function(e,a){$(e).remove()}))}).always(function(){ajax_send_always(t)&&window.setTimeout(function(){$(".is--submit",t).removeClass("is--loading").prop("disabled",!1)},2e3)}),!ajax_send_after(t)))return}$(document).on("click","[data-request]:not(form)",function(e){e.preventDefault();var a=new FormData;a.append("value",$(this).attr("data-value")),ajax_send($(this).attr("data-request"),a,this)}),$(document).on("submit","form[data-request]",function(e){e.preventDefault();var a=new FormData($(this)[0]);$('input[type="password"], input[name="message"]',this).val(""),$(".is--submit",this).addClass("is--loading").prop("disabled",!0),ajax_send($(this).attr("data-request"),a,this)});