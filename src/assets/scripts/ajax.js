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

function ajax_send(url,data){if(!ajax_send_before(data))return;$.ajax({type:"POST",dataType:"JSON",async:true,cache:false,contentType:false,processData:false,url:url,data:data,headers:{Authorization:"Bearer "+$("body").attr("data-token")??0}}).fail((function(res){if(!ajax_send_fail(res))return;console.log(res.responseText)})).done((function(res){if(!ajax_send_done(res))return;if(res.reload)window.location.reload(true);else if(res.redirect)window.location.replace(res.redirect);const process=(action,data)=>{$.each(data,(function(target,value){$(target)[action](value)}))};$.each({html:"html",replace:"replaceWith",prepend:"prepend",append:"append",remove:"remove"},(function(key,method){if(res[key]){process(method,res[key])}}))})).always((function(){if(!ajax_send_always())return;window.setTimeout((function(){$(".is--submit").removeClass("is--loading").prop("disabled",false)}),2e3)}));if(!ajax_send_after())return}$(document).on("click","[data-request]:not(form)",(function(e){e.preventDefault();data=new FormData;$.each(this.dataset,(function(key,value){data.append(key,value)}));$(".is--submit",this).addClass("is--loading").prop("disabled",true);ajax_send($(this).attr("data-request"),data)}));$(document).on("submit","form[data-request]",(function(e){e.preventDefault();data=new FormData(this);$('input[type="password"], input[name="message"]',this).val("");$(".is--submit",this).addClass("is--loading").prop("disabled",true);ajax_send($(this).attr("data-request"),data)}));