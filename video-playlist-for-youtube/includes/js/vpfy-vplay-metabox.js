jQuery(document).ready(function( $ ){
    $( '#add-row' ).on('click', function() {
        var row = $( '.empty-row.screen-reader-text' ).clone(true);
        row.removeClass( 'empty-row screen-reader-text' );
        row.insertBefore( '#repeatable-fieldset-one tbody>tr:last' );
        return false;
    });

    $( '.remove-row' ).on('click', function() {
        $(this).parents('tr').remove();
        return false;
    });

    /*Slider Width*/
    var sliderWidth = document.getElementById("vpfu_plyst_width");
    var outputWidth = document.getElementById("ytube_slide_wdth");
    outputWidth.innerHTML = sliderWidth.value;

    sliderWidth.oninput = function() {
      outputWidth.innerHTML = this.value;
    }
    /*Slider Height*/
    var sliderHeight = document.getElementById("vpfu_plyst_height");
    var outputHeight = document.getElementById("ytube_slide_height");
    outputHeight.innerHTML = sliderHeight.value;

    sliderHeight.oninput = function() {
      outputHeight.innerHTML = this.value;
    }

});

