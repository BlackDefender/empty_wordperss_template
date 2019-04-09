let scrollEventListenerThirdArgument = false;
(function () {
    try {
        var options = Object.defineProperty({}, "passive", {
            get: function() {
                scrollEventListenerThirdArgument = {passive: true};
            }
        });

        window.addEventListener("test", null, options);
    } catch(err) {}
})();

var MOBILE_MAX_WIDTH  = 768;
var ANIMATION_STEP = 150;

var sliderArrows = {
    prev: '<div class="slider-arrow slider-arrow-prev"></div>',
    next: '<div class="slider-arrow slider-arrow-next"></div>'
};