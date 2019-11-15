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

    if (Number.isNaN(Number(value))) {
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

  noUmlautsExcept(excepts = [], msg = null) {
    if (excepts === false) return this;

    if (this.hasError(this.id)) return this;

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
          excepts = excepts.join(',');
          if (excepts) {
            msg = msg || `just [ ${excepts} ] can be used`;
          } else {
            msg = msg || 'umlauts are not allow';
          }
          this.addError(this.id, msg);
          throw BreakException;
        }
      });
    } catch (e) {
      if (e !== BreakException) throw e;
    }
    return this;
  }

  noCharachtersExcept(options = [], msg = null) {
    if (options === false) return this;

    if (this.hasError(this.id)) return this;

    const value = this.value();

    if (!value && value !== '0') return this;

    let umlauts = 'á,â,ă,ä,ĺ,ç,č,é,ę,ë,ě,í,î,ď,đ,ň,ó,ô,ő,ö,ř,ů,ú,ű,ü,ý,ń,˙';
    umlauts = umlauts.split(',').join('');

    let excepts = options.excepts || [];
    const times = options.times || 1;

    if (excepts) {
      if (!Array.isArray(excepts)) {
        const countComma = (excepts.match(/,/g) || []).length;

        if (countComma && countComma > 1) {
          excepts = excepts.split(',');
        } else {
          excepts = excepts.split('');
        }
      }
      // eslint-disable-next-line consistent-return
      excepts.forEach((except) => {
        const test = new RegExp(except, 'g');
        const countCharachter = value.match(test);
        if (countCharachter && countCharachter.length > times) {
          msg = msg || `[ ${excepts.join(', ')} ] can be used just ${times} times`;
          this.addError(this.id, msg);
          return this;
        }
      });
      excepts = excepts.join('');
    } else {
      excepts = '';
    }

    const re = new RegExp(`^[A-Za-z0-9${umlauts}${excepts}]*$`);

    if (!re.test(value)) {
      if (excepts) {
        msg = msg || `just [ ${excepts} ] can be used`;
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

    if (value.trim().includes(' ')) {
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

  unique(table = null, msg = null) {
    if (table === false) return this;

    const value = this.value();

    if (!value && value !== '0') return this;

    // const form = $('form');
    // const action = form.attr('action');

    // $.ajax({
    //   type: 'POST',
    //   url: action,
    //   data: form.serialize(),
    //   success: (data) => {
    //     data = JSON.parse(data);
    //     console.log(data[this.id]);
    //     if (data[this.id]) {
    //       msg = msg || data[this.id];
    //       this.addError(this.id, msg);
    //     }
    //   },
    // });
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
