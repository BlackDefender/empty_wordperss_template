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