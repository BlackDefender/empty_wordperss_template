// https://developer.mozilla.org/en-US/docs/Web/API/Element/closest
if (!Element.prototype.matches)
    Element.prototype.matches = Element.prototype.msMatchesSelector ||
        Element.prototype.webkitMatchesSelector;

if (!Element.prototype.closest) {
    Element.prototype.closest = function (s) {
        let el = this;
        if (!document.documentElement.contains(el)) return null;
        do {
            if (el.matches(s)) return el;
            el = el.parentElement || el.parentNode;
        } while (el !== null && el.nodeType === 1);
        return null;
    };
}

const pageScrollState = (() => {
    let pageScrollPosition = 0;
    const fix = () => {
        if(!document.body.parentElement.classList.contains('fixed')){
            pageScrollPosition = window.scrollY;
            document.body.parentNode.classList.add('fixed');
            document.body.scrollTop = pageScrollPosition;
        }
    };
    const unfix = () => {
        if(document.body.parentElement.classList.contains('fixed')) {
            document.body.parentNode.classList.remove('fixed');
            window.scrollTo(0, pageScrollPosition);
        }
    };
    const toggle = () => {
        document.body.parentNode.classList.contains('fixed') ? unfix() : fix();
    };
    const set = (position) => {
        window.scrollTo(0, position);
    };
    return {
        fix,
        unfix,
        toggle,
        set,
    };
})();

const Convert = {
    nodeListToArray: nodeList => Array.prototype.slice.call(nodeList),
    toIntOrZero: (val) => {
        val = parseInt(val);
        return isNaN(val) ? 0 : val;
    },
    toFloatOrZero: (val) => {
        val = parseFloat(val);
        return isNaN(val) ? 0 : val;
    }
};

const querySelectorAsArray = selector => Array.prototype.slice.call(document.querySelectorAll(selector));

const isDesktop = () => window.innerWidth > MOBILE_MAX_WIDTH;
const isMobile = () => !isDesktop();

const on = (elements, event, callback) => {
    if(typeof elements === 'string'){
        elements = querySelectorAsArray(elements);
    }else if(elements instanceof NodeList){
        elements = Array.prototype.slice.call(elements);
    }
    elements.forEach(function (item) {
        item.addEventListener(event, callback);
    });
};

const emailRegExp = () => /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

const emailValid = email => emailRegExp().test(email);

const phoneInputFilterRegExp = () => /[\d+() -]/;

const isInputFilled = input => input.value !== '';

const isInputValid = input => {
    if(input.type.toLowerCase() === "email"){
        return input.value !== '' && emailRegExp().test(input.value);
    }else{
        return input.value !== '' && input.validity.valid;
    }
};

const scrollToElement = (element, duration = 1000) => {
    if(typeof element === 'string'){
        element = document.querySelector(element);
    }
    let endScrollPosition = element.offsetTop;
    while(element.tagName.toLowerCase() !== 'body'){
        element = element.parentElement;
        endScrollPosition += element.offsetTop;
    }
    const startScrollPosition = window.scrollY;
    const start = performance.now();

    const scrollStep = (endScrollPosition - startScrollPosition)/duration;
    let currentScrollPosition;

    requestAnimationFrame(function doScrollStep(time) {
        let timePassed = time - start;
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
};

/**
 * @param elements
 *   массив или массивоподобный объект с элементами для анимации
 *
 * @param animationStep
 *   задержка между стартами анимации элементов
 *   
 * Элемент с классом animate-with-prev-element будет анимироваться вместе с предидущим
**/
const animateElements = (elements, animationStep = ANIMATION_STEP) => {
    let index = 0;
    Array.prototype.forEach.call(elements, item => {
        if(!item.classList.contains('animate-with-prev-element')){
            ++index;
        }
        setTimeout(() => {
            item.classList.remove('unanimated');
            item.classList.add('animated');
        }, animationStep * index);
    });
};

const isClickFromKeyboard = mouseClickEvent => {
    if(!mouseClickEvent.type || mouseClickEvent.type.toLowerCase() !== 'click'){
        throw new Error('Wrong event type!');
    }
    return mouseClickEvent.clientX === 0 && mouseClickEvent.clientY === 0;
};
