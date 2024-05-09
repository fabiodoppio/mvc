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

var cooldown=false;function ajax_send(data){data.append("token",$("body").attr("data-token")??0);if(!ajax_send_before(data))return;$.ajax({type:"POST",dataType:"JSON",async:true,cache:false,contentType:false,processData:false,url:window.location,data:data}).fail((function(res){if(!ajax_send_fail(res))return;$('form[data-request="'+data.get("request")+'"] .response').html('<div class="error">'+res.responseText+"</div>")})).done((function(res){if(!ajax_send_done(res))return;if(res.reload)window.location.reload(true);if(res.redirect)window.location.replace(res.redirect);if(res.html)$.each(res.html,(function(target,value){$(target).html(value)}));if(res.prepend)$.each(res.prepend,(function(target,value){$(target).prepend(value)}));if(res.append)$.each(res.append,(function(target,value){$(target).append(value)}));if(res.remove)$.each(res.remove,(function(target,value){$(target).remove()}))})).always((function(){if(!ajax_send_always())return;window.setTimeout((function(){cooldown=false;$("button").removeClass("loading")}),2e3)}));if(!ajax_send_after())return}$(document).on("click","[data-request]:not(form)",(function(e){e.preventDefault();var data=new FormData;data.append("request",$(this).attr("data-request"));data.append("value",$(this).attr("data-value"));ajax_send(data)}));$(document).on("submit","form[data-request]",(function(e){e.preventDefault();if(!cooldown){cooldown=true;var data=new FormData($(this)[0]);data.append("request",$(this).attr("data-request"));ajax_send(data);$('input[type="password"], input[name="message"]',this).val("");$("button",this).addClass("loading")}}));