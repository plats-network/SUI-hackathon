
/**
 *
 * ScrollbarByCount
 * A class to help to provide scrollbars for lists by receiving a desired count to show.
 *
 * @param 'data-count' - Item count to show. Default value is 4.
 * @param 'data-autoHide' - Auto hide property of the scrollbar. Default value is 'leave'.
 * @param 'data-childSelector' - Items to calculate height of the container. Default value is '.card'.
 * @param 'data-subtractBottomMargin' - When provided 'true', adds item margin bottom value as negative to the element. Default value is 'true'.
 *
 * @method update Updates all the instances
 * @method destroy Destroys all the instances
 *
 */

class ScrollbarByCount {
    get options() {
        return {};
    }
    
    // Glide object
    get scrollbar() {
        return this._scrollbar;
    }
    
    constructor(element, options = {}) {
        this.settings = Object.assign(this.options, options);
        this._element = element;
        this._scrollbar;
        this._init = this._init.bind(this);
        this._init();
        this._addListeners();
    }
    
    _init() {
        if (this._scrollbar) {
            this._scrollbar.scroll([0, 0], 0);
        }
        const count = parseInt(this._element.getAttribute('data-count')) || 4;
        const autoHide = this._element.getAttribute('data-autoHide') || 'leave';
        const childSelector = this._element.getAttribute('data-childSelector') || '.card';
        const subtractBottomMargin = this._element.getAttribute('data-subtractMargin') || 'true';
        const children = this._element.querySelectorAll(childSelector);
        if (children.length === 0) {
            console.error('ScrollbarByCount: No children found!');
            return;
        }
        const lastEl = children[count - 1] || children[children.length - 1];
        if (!lastEl) {
            console.error('ScrollbarByCount: Children missing!');
            return;
        }
        const topLastEl = lastEl.getBoundingClientRect().top;
        const topEl = this._element.getBoundingClientRect().top;
        const topDif = topLastEl - topEl;
        const lastElMarginBottom = parseInt(getComputedStyle(lastEl).marginBottom);
        const lastElHeight = lastEl.offsetHeight + lastElMarginBottom;
        const height = topDif + lastElHeight;
        
        // Updating if the height is changed
        if (this._height !== height) {
            this._element.style.height = height + 'px';
            if (subtractBottomMargin === 'true') {
                this._element.style.marginBottom = -lastElMarginBottom + 'px';
            }
            if (this._scrollbar) {
                this._scrollbar.update();
            } else {
                this._scrollbar = OverlayScrollbars(this._element, {
                    scrollbars: {autoHide: autoHide || 'leave', autoHideDelay: 600},
                    overflowBehavior: {x: 'hidden', y: 'scroll'},
                });
            }
            this._element.querySelector('.os-scrollbar-vertical') &&
            (this._element.querySelector('.os-scrollbar-vertical').style.height = 'calc(100% - ' + lastElMarginBottom + 'px)');
            this._height = height;
        }
    }
    
    _addListeners() {
        window.addEventListener('resize', this._init);
    }
    
    update() {
        if (this._scrollbar) {
            this._scrollbar.update();
        }
    }
    
    destroy() {
        if (this._scrollbar) {
            this._scrollbar.destroy();
            this._scrollbar = null;
        }
        window.removeEventListener('resize', this._init);
    }
}
