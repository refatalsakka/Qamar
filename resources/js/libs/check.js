/* eslint-disable no-lonely-if */
/* eslint-disable no-unused-vars */
class Check {
  constructor(config) {
    this.elms = config;
    this.errors = [];
    this.self = this;

    for (const elm in this.elms) {
      this.name = elm;
      this.elm = document.querySelector(elm);
      const methods = Object.keys(this.elms[elm]);

      methods.forEach((method) => {
        let args = this.elms[elm][method];
        if (typeof args === 'string' && args.includes(':')) {
          args = args.split(':');
          this.self[method](args[1]);
        } else {
          // eslint-disable-next-line valid-typeof
          if (typeof args === 'boolean') {
            if (args !== false) {
              this.self[method]();
            }
          } else {
            this.self[method](args);
          }
        }
      });
    }
    console.log(this.errors);
  }

  require(msg = null) {
    const value = this.elm.innerText;

    if (value === '' || value === null) {
      msg = msg || 'this inputs is required';
      this.addError(this.name, msg);
    }
  }

  type(func, msg = null) {
    return this[func](msg);
  }

  email(msg = null) {
    const value = this.elm.innerText.toLowerCase();

    if (!value && value !== '0') return;

    // eslint-disable-next-line no-useless-escape
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    if (!re.test(value)) {
      msg = msg || 'E-Mail is not valid';
      this.addError(this.name, msg);
    }
  }

  image(msg = null) {
    const file = this.elm.files[0].type.split('/')[0].toLowerCase();

    if (!file || file !== 'image') {
      msg = msg || 'it must be image';
      this.addError(this.name, msg);
    }
  }

  number(msg = null) {
    const value = this.elm.innerText;

    if (!value && value !== '0') return;

    // eslint-disable-next-line no-restricted-globals
    if (isNaN(value)) {
      msg = msg || 'this inputs must be number';
      this.addError(this.name, msg);
    }
  }

  float(msg = null) {
    const value = this.elm.innerText;

    if (!value && value !== '0') return;

    // eslint-disable-next-line eqeqeq
    if ((value % 1) == 0) {
      msg = msg || 'this inputs must be float';
      this.addError(this.name, msg);
    }
  }

  date(options) {
    const value = this.elm.innerText;

    if (!value && value !== '0') return;

    let msg = false;

    if (!(options instanceof Object)) {
      msg = options;
    }

    if (new Date(value) === 'Invalid Date') {
      msg = msg || 'this inputs must be date';
      this.addError(this.name, msg);
      return;
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
        this.addError(this.name, msg);
      }
      return;
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
        this.addError(this.name, msg);
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
        this.addError(this.name, msg);
      }
    }
  }

  pureText(msg = null) {
    const value = this.elm.innerText.toLowerCase();

    if (!value && value !== '0') return;

    if (!/^[A-Za-z]*$/.test(value)) {
      msg = msg || 'this inputs must be text';
      this.addError(this.name, msg);
    }
  }

  text(msg = null) {
    const value = this.elm.innerText.toLowerCase();

    if (!value && value !== '0') return;

    if (typeof value !== 'string') {
      msg = msg || 'this inputs must be text';
      this.addError(this.name, msg);
    }
  }

  noNumbers(msg = null) {
    const value = this.elm.innerText;

    if (!value && value !== '0') return;

    if (/\d/.test(value)) {
      msg = msg || 'Numbers are not allow';
      this.addError(this.name, msg);
    }
  }

  noUmlautsExcept(excepts, msg = null) {
    const value = this.elm.innerText.toLowerCase();

    if (!value && value !== '0') return;

    const umlauts = 'á,â,ă,ä,ĺ,ç,č,é,ę,ë,ě,í,î,ď,đ,ň,ó,ô,ő,ö,ř,ů,ú,ű,ü,ý,ń,˙';

    if (typeof excepts === 'string' && excepts !== '') {
      excepts = excepts.split(',');
    } else if (!Array.isArray(excepts)) {
      excepts = [];
    }

    if (excepts) {
      excepts = excepts.map(chrachter => chrachter.toLowerCase());
    }
    // eslint-disable-next-line consistent-return
    umlauts.split(',').forEach((umlaut) => {
      if (value.indexOf(umlaut) >= 0 && excepts.indexOf(umlaut) < 0) {
        msg = msg || 'Umlauts are not allow';
        this.addError(this.name, msg);
        return false;
      }
    });
  }

  noCharachtersExcept(excepts, msg = null) {
    const value = this.elm.innerText.toLowerCase();

    if (!value && value !== '0') return;

    let umlauts = 'á,â,ă,ä,ĺ,ç,č,é,ę,ë,ě,í,î,ď,đ,ň,ó,ô,ő,ö,ř,ů,ú,ű,ü,ý,ń,˙';
    umlauts = umlauts.split(',').join('');

    if (!Array.isArray(excepts)) {
      const countComma = (excepts.match(/,/g) || []).length;

      if (countComma && countComma > 1) {
        excepts = excepts.split(',').join('');
      } else {
        excepts = excepts.split(' ').join('');
      }
    } else {
      excepts = excepts.join();
    }

    const re = new RegExp(`^[A-Za-z0-9${umlauts}${excepts}]*$`);

    if (!re.test(value)) {
      if (excepts) {
        msg = msg || `charachters are not allow except [ ${excepts} ]`;
      } else {
        msg = msg || 'charachters are not allow';
      }
      this.addError(this.name, msg);
    }
  }

  noSpaceBetween(msg = null) {
    const value = this.elm.innerText;

    if (!value && value !== '0') return;

    if (value.trim().includes(' ')) {
      msg = msg || 'Spaces are not allow';
      this.addError(this.name, msg);
    }
  }

  // containJust(allowes, msg = null)

  maxLen(length, msg = null) {
    const value = this.elm.innerText;

    if (!value && value !== '0') return;

    if (value.length > length) {
      msg = msg || `the value must maximum ${length} charachter`;
      this.addError(this.name, msg);
    }
  }

  minLen(length, msg = null) {
    const value = this.elm.innerText;

    if (!value && value !== '0') return;

    if (value.length < length) {
      msg = msg || `the value must be minimum ${length} charachter`;
      this.addError(this.name, msg);
    }
  }

  passes() {
    return !this.errors.length;
  }

  fails() {
    return this.errors.length;
  }

  hasError(elm) {
    return this.errors[elm];
  }

  addError(elm, msg) {
    if (!this.hasError(elm)) {
      this.errors[elm] = msg;
    }
  }

  getErrors() {
    return this.errors;
  }
}

// eslint-disable-next-line no-new
new Check({
  '#fname': {
    type: 'pureText',
    noNumbers: true,
    noUmlautsExcept: [],
    noSpaceBetween: true,
    noCharachtersExcept: ['_', '#'],
  },
  '#lname': {
    type: 'text',

  },
  '#birthday': {
    require: true,
    date: {
      start: 1920,
      end: 2004,
    },
  },
});
