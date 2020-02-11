// eslint-disable-next-line no-unused-vars
class Alert {
  constructor(options) {
    this.insertIn = Alert.formatElm(options.insertIn);
    this.msg = options.msg;
    this.mood = options.mood || 'primary';
    this.type = options.type || 'default';
    this.new = options.new || false;
  }

  static formatElm(elm) {
    if (typeof elm === 'object') return elm;

    elm = elm.trim(' ');

    if (elm.indexOf(':') > -1) {
      const [select, name] = elm.split(':');
      if (select === 'id') return document.querySelector(`#${name.trim(' ')}`);
      return document.querySelector(`.${name.trim(' ')}`);
    }
    // return document.querySelector(`.${elm}`);
  }

  defasult() {
    return `<div class="alert alert-${this.mood.trim(' ')}" role="alert">${this.msg.trim(' ')}</div>`;
  }

  dismissing() {
    return `<div class="alert alert-${this.mood.trim(' ')} alert-dismissible fade show" role="alert">
              ${this.msg.trim(' ')}
              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>`;
  }

  append() {
    const alert = document.querySelector('.alert');
    if (alert && !this.new) {
      alert.innerHTML = this.msg;
    } else {
      const type = (typeof this[this.type] !== 'undefined') ? this[this.type]() : this.defasult();
      this.insertIn.insertAdjacentHTML('afterBegin', type);
    }
  }
}
