let scrollEventListenerThirdArgument = false;
(function () {
    try {
        let options = Object.defineProperty({}, "passive", {
            get: function() {
                scrollEventListenerThirdArgument = {passive: true};
            }
        });

        window.addEventListener("test", null, options);
    } catch(err) {}
})();

const MOBILE_MAX_WIDTH  = 768;
const ANIMATION_STEP = 150;

const sliderArrows = {
    prev: '<div class="slider-arrow slider-arrow-prev"></div>',
    next: '<div class="slider-arrow slider-arrow-next"></div>'
};
