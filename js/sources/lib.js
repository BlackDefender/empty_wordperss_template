// https://developer.mozilla.org/en-US/docs/Web/API/Element/closest
if (!Element.prototype.matches)
    Element.prototype.matches = Element.prototype.msMatchesSelector ||
        Element.prototype.webkitMatchesSelector;

if (!Element.prototype.closest) {
    Element.prototype.closest = function (s) {
        var el = this;
        if (!document.documentElement.contains(el)) return null;
        do {
            if (el.matches(s)) return el;
            el = el.parentElement || el.parentNode;
        } while (el !== null && el.nodeType === 1);
        return null;
    };
}

var pageScrollState = (function () {
    var pageScrollPosition = 0;
    function fix() {
        if(!document.body.parentElement.classList.contains('fixed')){
            pageScrollPosition = window.scrollY;
            document.body.parentNode.classList.add('fixed');
            document.body.scrollTop = pageScrollPosition;
        }
    }
    function unfix() {
        if(document.body.parentElement.classList.contains('fixed')) {
            document.body.parentNode.classList.remove('fixed');
            window.scrollTo(0, pageScrollPosition);
        }
    }
    function toggle() {
        document.body.parentNode.classList.contains('fixed') ? unfix() : fix();
    }
    return {
        fix: fix,
        unfix: unfix,
        toggle: toggle
    };
})();

var Convert = {
    nodeListToArray: function (nodeList){
        return Array.prototype.slice.call(nodeList)
    },
    toIntOrZero: function (val) {
        val = parseInt(val);
        return isNaN(val) ? 0 : val;
    },
    toFloatOrZero: function (val) {
        val = parseFloat(val);
        return isNaN(val) ? 0 : val;
    }
};

function querySelectorAsArray(selector) {
    return Array.prototype.slice.call(document.querySelectorAll(selector));
}

function on(elements, event, callback) {
    if(typeof elements === 'string'){
        elements = querySelectorAsArray(elements);
    }else if(elements instanceof NodeList){
        elements = Array.prototype.slice.call(elements);
    }
    elements.forEach(function (item) {
        item.addEventListener(event, callback);
    });
}

function emailRegExp() {
    return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
}

function emailValid(email) {
    return emailRegExp().test(email)
}


function phoneInputFilterRegExp() {
    return /[\d+() -]/;
}

function isInputFilled(input) {
    return input.value !== '';
}

function isInputValid(input) {
    if(input.type.toLowerCase() === "email"){
        return input.value !== '' && emailRegExp().test(input.value);
    }else{
        return input.value !== '' && input.validity.valid;
    }
}

function scrollToElement(element, duration) {
    duration = duration || 1000;

    if(typeof element === 'string'){
        element = document.querySelector(element);
    }
    var endScrollPosition = element.offsetTop;
    while(element.tagName.toLowerCase() !== 'body'){
        element = element.parentElement;
        endScrollPosition += element.offsetTop;
    }
    var startScrollPosition = window.scrollY;
    var start = performance.now();

    var scrollStep = (endScrollPosition - startScrollPosition)/duration;
    var currentScrollPosition;

    requestAnimationFrame(function doScrollStep(time) {
        var timePassed = time - start;
        if (timePassed > duration) timePassed = duration;

        if(timePassed === duration){
            currentScrollPosition = endScrollPosition;
        }else{
            currentScrollPosition = startScrollPosition + scrollStep * timePassed;
        }
        window.scrollTo(0, currentScrollPosition);

        if (timePassed < duration) {
            requestAnimationFrame(doScrollStep);
        }
    });
}

function isClickFromKeyboard(mouseClickEvent) {
    if(!mouseClickEvent.type || mouseClickEvent.type.toLowerCase() !== 'click'){
        throw new Error('Wrong event type!');
    }
    return mouseClickEvent.clientX === 0 && mouseClickEvent.clientY === 0;
}
