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

function ajax_send(url,data,context){if(!ajax_send_before(data,context))return;$.ajax({type:"POST",dataType:"JSON",async:true,cache:false,contentType:false,processData:false,url:url,data:data,headers:{Authorization:"Bearer "+$("body").attr("data-token")??0}}).fail((function(res){if(!ajax_send_fail(res,context))return;$(".response",context).html('<div class="alert is--error">'+res.responseText+"</div>")})).done((function(res){if(!ajax_send_done(res,context))return;if(res.reload)window.location.reload(true);if(res.redirect)window.location.replace(res.redirect);if(res.html)$.each(res.html,(function(target,value){$(target).html(value)}));if(res.replace)$.each(res.replace,(function(target,value){$(target).replaceWith(value)}));if(res.prepend)$.each(res.prepend,(function(target,value){$(target).prepend(value)}));if(res.append)$.each(res.append,(function(target,value){$(target).append(value)}));if(res.remove)$.each(res.remove,(function(target,value){$(target).remove()}))})).always((function(){if(!ajax_send_always(context))return;window.setTimeout((function(){$(".is--submit",context).removeClass("is--loading").prop("disabled",false)}),2e3)}));if(!ajax_send_after(context))return}$(document).on("click","[data-request]:not(form)",(function(e){e.preventDefault();var data=new FormData;data.append("value",$(this).attr("data-value"));ajax_send($(this).attr("data-request"),data,this)}));$(document).on("submit","form[data-request]",(function(e){e.preventDefault();var data=new FormData($(this)[0]);$('input[type="password"], input[name="message"]',this).val("");$(".is--submit",this).addClass("is--loading").prop("disabled",true);ajax_send($(this).attr("data-request"),data,this)}));