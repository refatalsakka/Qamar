// eslint-disable-next-line no-unused-vars
class Alert {
  constructor(options) {
    this.insertIn = Alert.formatElm(options.insertIn);
    this.msg = options.msg;
    this.mood = options.mood || 'primary';
    this.type = options.type || 'default';
  }

  static formatElm(elm) {
    if (typeof elm === 'object') return elm;

    if (elm.indexOf(':') > -1) {
      const [select, name] = elm.split(':');
      if (select === 'id') return document.querySelector(`#${name}`);
      return document.querySelector(`.${name}`);
    }
    return document.querySelector(`.${elm}`);
  }

  defasult() {
    return `<div class="alert alert-${this.mood}" role="alert">${this.msg}</div>`;
  }

  dismissing() {
    return `<div class="alert alert-${this.mood} alert-dismissible fade show" role="alert">
              ${this.msg}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>`;
  }

  append() {
    const alert = (typeof this[this.type] !== 'undefined') ? this[this.type]() : this.defasult();
    this.insertIn.insertAdjacentHTML('afterBegin', alert);
  }
}
