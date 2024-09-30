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

$(function(){$(document).on("click","body",function(t){($(".dropdown.is--active, .modalbox.is--active").length&&!$(t.target).is('[data-trigger="modalbox"], [data-trigger="dropdown"]')&&!$(t.target).closest(".modalbox div").length&&!$(t.target).closest(".dropdown").find(".modalbox.is--active").length||$(t.target).is(".modalbox, .modalbox button"))&&($(".modalbox.is--active").length||$(".dropdown").removeClass("is--active"),$(".modalbox").addClass("is--vanishing"),setTimeout(function(){$(".modalbox").removeClass("is--active is--vanishing")},500))}),$(document).on("click",'[data-trigger="dropdown"]',function(t){t.preventDefault(),t.stopPropagation(),$(".dropdown").removeClass("is--active"),$(this).next(".dropdown").addClass("is--active")}),$(document).on("click",'[data-trigger="modalbox"]',function(t){t.preventDefault(),t.stopPropagation(),$(".modalbox").removeClass("is--active"),$(this).next(".modalbox").addClass("is--active")}),$(document).on("click",'[data-trigger="menu"]',function(t){t.preventDefault(),$(".menu").toggleClass("is--mobile")}),$(document).on("click",'[data-trigger="remove"]',function(t){t.preventDefault(),$('input[name="attachment"]').val(""),$('[data-trigger="attachment"]').removeClass("is--hidden"),$(".attachment-info").html("")}),$(document).on("change",'input[name="attachment"]',function(t){t.preventDefault(),$('[data-trigger="attachment"]').addClass("is--hidden"),$(".attachment-info").html(this.files.length?this.files[0].name+' <i class="fas fa-circle-xmark" data-trigger="remove"></i>':"")}),$(document).on("click",'[data-trigger="attachment"]',function(t){t.preventDefault(),$('input[name="attachment"]').click()}),$(document).on("click",'[data-trigger="avatar"]',function(t){t.preventDefault(),$('input[name="avatar"]').click()}),$(document).on("change",'input[name="avatar"]',function(t){$(this).closest("form").submit(),$(this).val("")})});