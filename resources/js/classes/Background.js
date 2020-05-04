// eslint-disable-next-line no-unused-vars
export default class Background {
  constructor(options) {
    if (options) {
      this.elm = Background.formatElm(options.elm);
      this.class = Background.formatClass(options.class);
    }
  }

  static formatElm(elm) {
    if (typeof elm === 'object') return elm;
    return document.querySelector(`${elm}`);
  }

  static formatClass(_class) {
    if (_class.indexOf('.') === 0) {
      return _class.substr(1);
    }
    return _class;
  }

  addAfter(wait = 0) {
    if (wait) {
      setTimeout(() => {
        this.elm.classList.add(this.class);
      }, wait);
    } else {
      this.elm.classList.add(this.class);
    }
    return this;
  }

  removeAfter(wait = 0) {
    if (wait) {
      setTimeout(() => {
        this.elm.classList.remove(this.class);
      }, wait);
    } else {
      this.elm.classList.remove(this.class);
    }
    return this;
  }

  removeAllAfter(wait = 0) {
    const elms = document.querySelectorAll(`.${this.class}`);
    if (!elms) return;
    if (wait) {
      setTimeout(() => {
        elms.forEach(elm => elm.classList.remove(this.class));
      }, wait);
    } else {
      elms.forEach(elm => elm.classList.remove(this.class));
    }
  }
}
