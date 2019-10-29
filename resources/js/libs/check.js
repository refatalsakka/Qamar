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
          this.self[method](args[0], args[1]);
        } else {
          this.self[method](args);
        }
      });
    }
    console.log(this.errors);
  }

  require(arg, msg = null) {
    if (!arg) return;

    const value = this.elm.innerText;

    if (value === '' || value === null) {
      msg = msg || 'this inputs is required';
      this.addError(this.name, msg);
    }
  }

  type(arg, msg = null) {
    return this[arg](msg);
  }

  // emal(msg = null){}

  image(arg, msg = null) {
    if (!arg) return;

    const file = this.elm.files[0].type.split('/')[0];

    if (!file || file !== 'image') {
      msg = msg || 'it must be image';
      this.addError(this.name, msg);
    }
  }

  number(msg = null) {
    const value = this.elm.innerText;

    if (!value && value !== '0') return;

    if (!value.match(/^-{0,1}\d+$/)) {
      msg = msg || 'this inputs must be number';
      this.addError(this.name, msg);
    }
  }

  // float(msg = null)

  date(args) {
    const value = this.elm.innerText;

    if (!value && value !== '0') return;

    let msg = false;

    if (!(args instanceof Object)) {
      msg = args;
    }

    if (new Date(value) === 'Invalid Date') {
      msg = msg || 'this inputs must be date';
      this.addError(this.name, msg);
      return;
    }

    if (args.start && args.end) {
      let { start, end } = args;
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

    if (args.start) {
      let year = args.start;
      if (typeof args.start === 'string' && args.start.includes(':')) {
        args.start = args.start.split(':');
        year = args.start[0];
        msg = args.start[1];
      }
      if (new Date(value).getFullYear() < year) {
        msg = msg || `the date cont be under ${year}`;
        this.addError(this.name, msg);
      }
    }

    if (args.end) {
      let year = args.end;
      if (typeof args.end === 'string' && args.end.includes(':')) {
        args.end = args.end.split(':');
        year = args.end[0];
        msg = args.end[1];
      }
      if (new Date(value).getFullYear() > year) {
        msg = msg || `the date cont be above ${year}`;
        this.addError(this.name, msg);
      }
    }
  }

  pureText(msg) {
    const value = this.elm.innerText;

    if (!value && value !== '0') return;

    if (!value.match(/^[A-Za-z]*$/)) {
      msg = msg || 'this inputs must be text';
      this.addError(this.name, msg);
    }
  }

  text(msg) {
    const value = this.elm.innerText;

    if (!value && value !== '0') return;

    if (!value.match(/^[A-Za-z]*$/)) {
      msg = msg || 'this inputs must be text';
      this.addError(this.name, msg);
    }
  }

  // textWithAllowing(allowes, msg = null)

  // noUmlaut(msg = null)

  // containJust(allowes, msg = null)

  noSpaceBetween(arg, msg = null) {
    if (!arg) return;

    const value = this.elm.innerText;

    if (!value && value !== '0') return;

    if (value.trim().includes(' ')) {
      msg = msg || 'Spaces are not allow';
      this.addError(this.name, msg);
    }
  }

  maxLen(arg, msg = null) {
    if (!arg) return;

    const value = this.elm.innerText;

    if (!value && value !== '0') return;

    if (value.length > arg) {
      msg = msg || `the value must maximum ${arg} charachter`;
      this.addError(this.name, msg);
    }
  }

  minLen(arg, msg = null) {
    if (!arg) return;

    const value = this.elm.innerText;

    if (!value && value !== '0') return;

    if (value.length < arg) {
      msg = msg || `the value must be minimum ${arg} charachter`;
      this.addError(this.name, msg);
    }
  }


  uppercaseNotAllowed(arg) {
    if (!arg) return;

    this.elm.innerText = this.elm.innerText.toLocaleLowerCase();
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
    type: 'text',
    require: true,
    noSpaceBetween: true,
    uppercaseNotAllowed: true,
    maxLen: 20,
    minLen: 3,
    // textWithAllowNumber: true,
  },
  '#lname': {
    type: 'text',
    require: true,
    noSpaceBetween: true,
    uppercaseNotAllowed: true,
    maxLen: 20,
    minLen: 3,
  },
  '#birthday': {
    require: true,
    date: {
      start: 1920,
      end: 2004,
    },
  },
});
