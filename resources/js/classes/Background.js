// eslint-disable-next-line no-unused-vars
class Background {
  constructor(options) {
    this.elm = options.elm || false;
    this.colorClass = options.colorClass.trim(' ');
    this.addAfter = !Number.isNaN(Number(options.addAfter)) ? options.addAfter : false;
    this.removeAfter = !Number.isNaN(Number(options.removeAfter)) ? options.removeAfter : false;
  }

  static formatElm(elm) {
    if (typeof elm === 'object') return elm;

    return document.querySelector(`.${elm.trim(' ')}`);
  }

  static formatClass(name, point = false) {
    if (point === true) {
      if (name.indexOf('.') !== 0) {
        return `.${name}`;
      }
    }
    if (point === false) {
      if (name.indexOf('.') === 0) {
        return name.substr(1);
      }
    }
    return name;
  }

  add(elm = null) {
    if (!elm) elm = this.elm;

    elm = Background.formatElm(elm);

    const className = Background.formatClass(this.colorClass, false);

    if (this.addAfter) {
      setTimeout(() => {
        elm.classList.add(className);
      }, this.addAfter);
    } else {
      elm.classList.add(className);
    }

    if (this.removeAfter) {
      setTimeout(() => {
        elm.classList.remove(className);
      }, this.addAfter + this.removeAfter);
    }
  }

  remove(elm = null) {
    if (!elm) elm = this.elm;

    elm = Background.formatElm(elm);

    const className = Background.formatClass(this.colorClass, false);

    elm.classList.remove(className);
  }

  removeAll() {
    const className = Background.formatClass(this.colorClass, true);
    const elms = document.querySelectorAll(className);
    elms.forEach(elm => elm.classList.remove(Background.formatClass(this.colorClass, false)));
  }
}
