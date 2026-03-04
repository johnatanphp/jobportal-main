(function($) {
$(document).on('keydown', '*[data-timepicker]', function(e){

    $(this).attr('autocomplete','off');
    
    // Input Value var
    var inputValue = $(this).val();
    
    // Make sure keypress value is a Number
    if ($.isNumeric(e.key) || e.keyCode == 8) {
      // Make sure first value is not greater than 2
      if (inputValue.length == 0) {
        if (e.keyCode > 48) {
          e.preventDefault();
          $(this).val(1);
        }
      }

      // Make sure second value is not greater than 4
      else if(inputValue.length == 1 && e.keyCode != 8){
        e.preventDefault();
        
        if(inputValue == '1' && e.keyCode > 49 ){
          $(this).val(inputValue + '2:');
        } else if (inputValue == '0' && String.fromCharCode(e.keyCode) == '0') {
          $(this).val(inputValue + '1:');
        } else{
          $(this).val(inputValue + String.fromCharCode(e.keyCode) + ':');
        }
      }

      else if(inputValue.length == 2 && e.keyCode != 8){
        e.preventDefault();
        if( e.keyCode > 52 ){
          $(this).val(inputValue + ':5');
        }
        else{
          $(this).val(inputValue + ':' + String.fromCharCode(e.keyCode));
        }
      }

      // Make sure that third value is not greater than 5
      else if(inputValue.length == 3 && e.keyCode != 8){
        if( e.keyCode > 52 ){
          e.preventDefault();
          $(this).val( inputValue + '5' );
        }
      }

      // Make sure only 5 Characters can be input
      else if(inputValue.length > 4 && e.keyCode != 8){
        e.preventDefault();
        return false;
      }
    }

    // Prevent Alpha and Special Character inputs
    else{
      e.preventDefault();
      return false;
    }
  }) // End Timepicker KeyUp function

  .keyup(function(e) {

    $(this).val($(this).val().replace(/[^0-9:]/g,'')); 
  
  }).blur(function() {
  
    var inputValue = $(this).val();
  
    if (inputValue.length < 5) {
      $(this).val('');
    }
  
  });
})(jQuery);
