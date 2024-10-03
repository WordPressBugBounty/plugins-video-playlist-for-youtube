/*Slider Width*/
var sliderWidth = document.getElementById("vpfu_plyst_wid");
var outputWidth = document.getElementById("ytube_slidesetting_wdth");
if (sliderWidth != null) {
    outputWidth.innerHTML = sliderWidth.value;

    sliderWidth.oninput = function() {
	  outputWidth.innerHTML = this.value;
	}
}



/*Slider Height*/
var sliderHeight = document.getElementById("vpfu_plyst_hei");
var outputHeight = document.getElementById("ytube_slidesetting_height");
if (sliderHeight != null) {
	outputHeight.innerHTML = sliderHeight.value;

	sliderHeight.oninput = function() {
	  outputHeight.innerHTML = this.value;
	}

}