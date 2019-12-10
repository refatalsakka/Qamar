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

    return elm.value.toLowerCase();
  }

  require(call = true, msg = null) {
    if (call === false) return this;

    const value = this.value();

    if (value === '' || value === null) {
      msg = msg || 'this field is required';
      this.addError(this.id, msg);
    }
    return this;
  }

  type(func, msg = null) {
    return this[func](msg);
  }

  email(call = true, msg = null) {
    if (call === false) return this;

    if (this.hasError(this.id)) return this;

    const value = this.value();

    if (!value && value !== '0') return this;

    const re = /^(([^<>()\\[\]\\.,;:\s@"]+(\.[^<>()\\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    if (!re.test(value)) {
      msg = msg || 'e-mail is not valid';
      this.addError(this.id, msg);
    }
    return this;
  }

  image(call = true, msg = null) {
    if (call === false) return this;

    if (this.hasError(this.id)) return this;

    const file = this.id.files[0].type.split('/')[0].toLowerCase();

    if (!file || file !== 'image') {
      msg = msg || 'image is not valid';
      this.addError(this.id, msg);
    }
    return this;
  }

  number(call = true, msg = null) {
    if (call === false) return this;

    if (this.hasError(this.id)) return this;

    const value = this.value();

    if (!value && value !== '0') return this;

    if (!/^\d+$/.test(value)) {
      msg = msg || 'this field must be number';
      this.addError(this.id, msg);
    }
    return this;
  }

  float(call = true, msg = null) {
    if (call === false) return this;

    if (this.hasError(this.id)) return this;

    const value = this.value();

    if (!value && value !== '0') return this;

    if ((value % 1) === 0) {
      msg = msg || 'this field must be a float number';
      this.addError(this.id, msg);
    }
    return this;
  }

  date(options, msg = null) {
    if (options === false) return this;

    if (this.hasError(this.id)) return this;

    const value = this.value();

    if (!value && value !== '0') return this;

    if (Number.isNaN(new Date(value).getDay())) {
      msg = msg || 'this field must be date';
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
        msg = `the field must be netween ${start} and ${end}`;
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
        msg = msg || `the date can't be under ${year}`;
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

  pureText(call = true, msg = null) {
    if (call === false) return this;

    if (this.hasError(this.id)) return this;

    const value = this.value();

    if (!value && value !== '0') return this;

    if (!/^[A-Za-z]*$/.test(value)) {
      msg = msg || 'this field must be just a text';
      this.addError(this.id, msg);
    }
    return this;
  }

  text(call = true, msg = null) {
    if (call === false) return this;

    if (this.hasError(this.id)) return this;

    const value = this.value();

    if (!value && value !== '0') return this;

    if (typeof value !== 'string') {
      msg = msg || 'this field must be a text';
      this.addError(this.id, msg);
    }
    return this;
  }

  noNumbers(call = true, msg = null) {
    if (call === false) return this;

    if (this.hasError(this.id)) return this;

    const value = this.value();

    if (!value && value !== '0') return this;

    if (/\d/.test(value)) {
      msg = msg || 'numbers are not allow';
      this.addError(this.id, msg);
    }
    return this;
  }

  noUmlauts(call = true, msg = null) {
    if (call === false) return this;

    if (this.hasError(this.id)) return this;

    const value = this.value();

    if (!value && value !== '0') return this;

    const umlauts = 'á,â,ă,ä,ĺ,ç,č,é,ę,ë,ě,í,î,ď,đ,ň,ó,ô,ő,ö,ř,ů,ú,ű,ü,ý,ń,˙'.split(',').join('');

    const re = new RegExp(`[${umlauts}]`, 'gi');

    if (re.test(value)) {
      msg = msg || 'umlauts are not allow';
      this.addError(this.id, msg);
    }
    return this;
  }

  noCharExcept(excepts = {}, msg = null) {
    if (excepts === false || excepts === {}) return this;

    if (this.hasError(this.id)) return this;

    const value = this.value();

    if (!value && value !== '0') return this;

    const umlauts = 'á,â,ă,ä,ĺ,ç,č,é,ę,ë,ě,í,î,ď,đ,ň,ó,ô,ő,ö,ř,ů,ú,ű,ü,ý,ń,˙'.split(',').join('');

    let chars = '';
    let times = null;
    let atFirst = null;
    let atEnd = null;
    let between = null;

    if (Array.isArray(excepts) && excepts.length) {
      chars = excepts.join('');
    } else if (typeof excepts === 'string') {
      if (/,/g.test(excepts) && excepts.match(/,/g).length > 1) {
        chars = `\\${excepts.split(',').join('\\')}`;
      } else {
        chars = `\\${excepts.split('').join('\\')}`;
      }
    } else if (typeof excepts === 'object' && !Array.isArray(excepts)) {
      chars = excepts.chars;
      if (Array.isArray(chars)) {
        chars = chars.join('');
      } else if (typeof chars === 'string') {
        if (/,/g.test(chars) && chars.match(/,/g).length > 1) {
          chars = `\\${chars.split(',').join('\\')}`;
        } else {
          chars = `\\${chars.split('').join('\\')}`;
        }
      }
      times = excepts.times || null;
      atFirst = excepts.atFirst;
      atEnd = excepts.atEnd;
      between = excepts.between;
    }

    if (times > 0) {
      let splitChars = chars;
      if (splitChars.length > 1) {
        splitChars = `\\${splitChars.split('').join('|\\')}`;
      }
      const re1 = new RegExp(`(${splitChars})`, 'g');
      if (value.match(re1) && value.match(re1).length > times) {
        msg = msg || 'charachters are too many';
        this.addError(this.id, msg);
        return this;
      }
    }

    if (atFirst === false) {
      let splitChars = chars;
      if (splitChars.length > 1) {
        splitChars = `\\${splitChars.split('').join('|\\')}`;
      }
      const re2 = new RegExp(`^(${splitChars}|\\s+\\${splitChars})`, 'g');
      if (re2.test(value)) {
        msg = msg || 'charachters cant be in the first';
        this.addError(this.id, msg);
        return this;
      }
    }

    if (atEnd === false) {
      let splitChars = chars;
      if (splitChars.length > 1) {
        splitChars = `\\${splitChars.split('').join('|\\')}`;
      }
      const re3 = new RegExp(`(${splitChars}|\\${splitChars}\\s+)$`, 'g');
      if (re3.test(value)) {
        msg = msg || 'charachters cant be in the end';
        this.addError(this.id, msg);
        return this;
      }
    }

    if (between === false) {
      let splitChars = chars;
      if (splitChars.length > 1) {
        splitChars = `\\${splitChars.split('').join('|\\')}`;
      }
      const re3 = new RegExp(`.+(${splitChars})(.+|\\s)`, 'g');
      if (re3.test(value)) {
        msg = msg || 'charachters cant be between';
        this.addError(this.id, msg);
        return this;
      }
    }

    const re5 = new RegExp(`^[A-Za-z0-9\\s${umlauts}${chars}]*$`);
    if (!re5.test(value)) {
      if (chars) {
        chars = chars.split('\\').join(' ');
        msg = msg || `just [ ${chars} ] can be used`;
      } else {
        msg = msg || 'charachters are not allow';
      }
      this.addError(this.id, msg);
    }
    return this;
  }

  noSpaces(call = true, msg = null) {
    if (call === false) return this;

    if (this.hasError(this.id)) return this;

    const value = this.value();

    if (!value && value !== '0') return this;

    if (/\s/.test(value)) {
      msg = msg || 'Spaces are not allow';
      this.addError(this.id, msg);
    }
    return this;
  }

  containJust(characters = [], msg = null) {
    if (characters === false) return this;

    if (this.hasError(this.id)) return this;

    const value = this.value();

    if (!value && value !== '0') return this;

    if (typeof characters === 'string' && characters.indexOf('path:') === 0) return this;

    if (!Array.isArray(characters) && value !== '') {
      characters = [characters];
    }

    if (!characters.includes(value)) {
      msg = msg || 'Wrong Value';
      this.addError(this.id, msg);
    }
    return this;
  }

  length(length = null, msg = null) {
    if (length === false) return this;

    if (this.hasError(this.id)) return this;

    const value = this.value();

    if (!value && value !== '0') return this;

    if (value.length !== length) {
      msg = msg || `this field can be just ${length} charachter`;
      this.addError(this.id, msg);
    }
    return this;
  }

  maxLen(length = null, msg = null) {
    if (length === false) return this;

    if (this.hasError(this.id)) return this;

    const value = this.value();

    if (!value && value !== '0') return this;

    if (value.length > length) {
      msg = msg || `this field can be maximum ${length} charachter`;
      this.addError(this.id, msg);
    }
    return this;
  }

  minLen(length = null, msg = null) {
    if (length === false) return this;

    if (this.hasError(this.id)) return this;

    const value = this.value();

    if (!value && value !== '0') return this;

    if (value.length < length) {
      msg = msg || `the field must be minimum ${length} charachter`;
      this.addError(this.id, msg);
    }
    return this;
  }

  match(input, msg = null) {
    if (input === false) return this;

    if (this.hasError(this.id)) return this;

    const value = this.value();

    const valueConfirm = document.querySelector(`#${input}`).value;

    if (value && valueConfirm) {
      if (value !== valueConfirm) {
        msg = msg || 'passwords doesn\'t match';

        this.addError(this.id, msg);
      }
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
    if (!this.hasError(id)) this.errors[id] = msg;
  }

  getErrors() {
    return this.errors;
  }
}
