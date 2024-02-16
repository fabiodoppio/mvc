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

$(function() {


    $('[data-trigger="dropdown"]').click(function() { $('.dropdown').not($(this).next()).hide(); $(this).next('.dropdown').toggle(); });




   $(document).click(function(e) { 
        var target = e.target; 
        if (!$(target).is('[data-trigger="dropdown"]') && !$(target).parents().is('[data-trigger="dropdown"]')) 
        { $('.dropdown').hide(); }
    }); 

});