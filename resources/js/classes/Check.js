/* eslint-disable no-unused-vars */
class Check {
  constructor() {
    this.errors = [];
  }

  input(id) {
    this.id = id;
    return this;
  }

  value() {
    const elm = document.querySelector(`#${this.id}`);

    if (!elm) return undefined;

    return typeof elm === 'string' ? elm.innerText.toLowerCase() : elm.innerText;
  }

  require(msg = null) {
    const value = this.value();

    if (value === '' || value === null) {
      msg = msg || 'this inputs is required';
      this.addError(this.id, msg);
    }
    return this;
  }

  type(func, msg = null) {
    return this[func](msg);
  }

  email(msg = null) {
    const value = this.value();

    if (!value && value !== '0') return this;

    const re = /^(([^<>()\\[\]\\.,;:\s@"]+(\.[^<>()\\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    if (!re.test(value)) {
      msg = msg || 'E-Mail is not valid';
      this.addError(this.id, msg);
    }
    return this;
  }

  image(msg = null) {
    const file = this.id.files[0].type.split('/')[0].toLowerCase();

    if (!file || file !== 'image') {
      msg = msg || 'it must be image';
      this.addError(this.id, msg);
    }
    return this;
  }

  number(msg = null) {
    const value = this.value();

    if (!value && value !== '0') return this;

    if (Number.isNaN(Number(value))) {
      msg = msg || 'this inputs must be number';
      this.addError(this.id, msg);
    }
    return this;
  }

  float(msg = null) {
    const value = this.value();

    if (!value && value !== '0') return this;

    if ((value % 1) === 0) {
      msg = msg || 'this inputs must be float';
      this.addError(this.id, msg);
    }
    return this;
  }

  date(options, msg = null) {
    const value = this.value();

    if (!value && value !== '0') return this;

    if (new Date(value) === 'Invalid Date') {
      msg = msg || 'this inputs must be date';
      this.addError(this.id, msg);
      return this;
    }

    if (options.start && options.end) {
      let { start, end } = options;
      if (typeof start === 'string' && end.includes(':')) {
        start = start.split(':')[0];
      }
      if (typeof end === 'string' && end.includes(':')) {
        end = end.split(':')[0];
      }
      if (new Date(value).getFullYear() < start || new Date(value).getFullYear() > end) {
        msg = `the date must be netween ${start} and ${end}`;
        this.addError(this.id, msg);
      }
      return this;
    }

    if (options.start) {
      let year = options.start;
      if (typeof options.start === 'string' && options.start.includes(':')) {
        options.start = options.start.split(':');
        year = options.start[0];
        msg = options.start[1];
      }
      if (new Date(value).getFullYear() < year) {
        msg = msg || `the date cont be under ${year}`;
        this.addError(this.id, msg);
      }
    }

    if (options.end) {
      let year = options.end;
      if (typeof options.end === 'string' && options.end.includes(':')) {
        options.end = options.end.split(':');
        year = options.end[0];
        msg = options.end[1];
      }
      if (new Date(value).getFullYear() > year) {
        msg = msg || `the date cont be above ${year}`;
        this.addError(this.id, msg);
      }
    }
    return this;
  }

  pureText(msg = null) {
    const value = this.value();

    if (!value && value !== '0') return this;

    if (!/^[A-Za-z]*$/.test(value)) {
      msg = msg || 'this inputs must be text';
      this.addError(this.id, msg);
    }
    return this;
  }

  text(msg = null) {
    const value = this.value();

    if (!value && value !== '0') return this;

    if (typeof value !== 'string') {
      msg = msg || 'this inputs must be text';
      this.addError(this.id, msg);
    }
    return this;
  }

  noNumbers(msg = null) {
    const value = this.value();

    if (!value && value !== '0') return this;

    if (/\d/.test(value)) {
      msg = msg || 'Numbers are not allow';
      this.addError(this.id, msg);
    }
    return this;
  }

  noUmlautsExcept(excepts = [], msg = null) {
    const value = this.value();

    if (!value && value !== '0') return this;

    const umlauts = 'á,â,ă,ä,ĺ,ç,č,é,ę,ë,ě,í,î,ď,đ,ň,ó,ô,ő,ö,ř,ů,ú,ű,ü,ý,ń,˙';

    if (typeof excepts === 'string' && excepts !== '') {
      excepts = excepts.split(',');
    } else if (!Array.isArray(excepts)) {
      excepts = [];
    }

    if (excepts) {
      excepts = excepts.map(chrachter => chrachter.toLowerCase());
    }

    const BreakException = {};
    try {
      umlauts.split(',').forEach((umlaut) => {
        if (value.indexOf(umlaut) >= 0 && excepts.indexOf(umlaut) < 0) {
          msg = msg || 'Umlauts are not allow';
          this.addError(this.id, msg);
          throw BreakException;
        }
      });
    } catch (e) {
      if (e !== BreakException) throw e;
    }
    return this;
  }

  noCharachtersExcept(excepts = [], msg = null) {
    const value = this.value();

    if (!value && value !== '0') return this;

    let umlauts = 'á,â,ă,ä,ĺ,ç,č,é,ę,ë,ě,í,î,ď,đ,ň,ó,ô,ő,ö,ř,ů,ú,ű,ü,ý,ń,˙';
    umlauts = umlauts.split(',').join('');

    if (excepts) {
      if (!Array.isArray(excepts)) {
        const countComma = (excepts.match(/,/g) || []).length;

        if (countComma && countComma > 1) {
          excepts = excepts.split(',');
        } else {
          excepts = excepts.split(' ');
        }
      }
      excepts = excepts.join('');
    } else {
      excepts = '';
    }

    const re = new RegExp(`^[A-Za-z0-9${umlauts}${excepts}]*$`);

    if (!re.test(value)) {
      if (excepts) {
        msg = msg || `charachters are not allow except [ ${excepts} ]`;
      } else {
        msg = msg || 'charachters are not allow';
      }
      this.addError(this.id, msg);
    }
    return this;
  }

  noSpaceBetween(msg = null) {
    const value = this.value();

    if (!value && value !== '0') return this;

    if (value.trim().includes(' ')) {
      msg = msg || 'Spaces are not allow';
      this.addError(this.id, msg);
    }
    return this;
  }

  containJust(characters, msg = null) {
    const value = this.value();

    if (!value && value !== '0') return this;

    return this;
  }

  maxLen(length, msg = null) {
    const value = this.value();

    if (!value && value !== '0') return this;

    if (value.length > length) {
      msg = msg || `the value must maximum ${length} charachter`;
      this.addError(this.id, msg);
    }
    return this;
  }

  minLen(length, msg = null) {
    const value = this.value();

    if (!value && value !== '0') return this;

    if (value.length < length) {
      msg = msg || `the value must be minimum ${length} charachter`;
      this.addError(this.id, msg);
    }
    return this;
  }

  passes() {
    return !this.errors.length;
  }

  fails() {
    return this.errors.length;
  }

  hasError(id) {
    return this.errors[id];
  }

  addError(id, msg) {
    if (!this.hasError(id)) {
      this.errors[id] = msg;
    }
  }

  getErrors() {
    return this.errors;
  }
}
