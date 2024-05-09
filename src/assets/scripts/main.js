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
            $('.dropdown').hide();
    }); 

    $(document).on("change",'input[name="avatar"]',(function(e){$(this).closest("form").submit()}));


    $(document).on("change", 'input[name="attachment"]', function(e) {
        e.preventDefault(); 
        $('label[for="attachment"] .btn.is--secondary').attr("style", "display: none !important");
        $(".attachment-info").html((this.files.length)?this.files[0].name+' <i class="fas fa-circle-xmark"></i>':"");
      });
      
      $(document).on("click", ".attachment-info i", function(e) {
          e.preventDefault();
          $('label[for="attachment"] input[name="attachment"]').val("");
          $('label[for="attachment"] .btn.is--secondary').attr("style", "display: inline-block !important");
          $(".attachment-info").html("");
      });
});