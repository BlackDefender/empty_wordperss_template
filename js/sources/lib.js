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

function _parseInt(val) {
    val = parseInt(val);
    return isNaN(val) ? 0 : val;
}

function _parseFloat(val) {
    val = parseFloat(val);
    return isNaN(val) ? 0 : val;
}

function emailRegExp() {
    return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
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
    jQuery('html, body').animate({
        scrollTop: jQuery(element).offset().top
    }, duration);
}

function isClickFromKeyboard(mouseClickEvent) {
    if(!mouseClickEvent.type || mouseClickEvent.type.toLowerCase() !== 'click'){
        throw new Error('Wrong event type!');
    }
    return mouseClickEvent.clientX === 0 && mouseClickEvent.clientY === 0;
}

var Convert = {
	nodeListToArray: function (nodeList){
		return Array.prototype.slice.call(nodeList)
	}
}