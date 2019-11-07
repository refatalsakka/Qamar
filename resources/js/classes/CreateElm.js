/* eslint-disable no-underscore-dangle */
// eslint-disable-next-line no-unused-vars
class CreateElm {
  constructor(options) {
    this.tag = options.tag || '';
    this.name = options.name || '';
    this.value = options.value || '';
    this.type = options.type || '';
    this.id = options.id || '';
    this.classes = options.classes || '';
    this.data = options.data || '';
    this.options = options.options || '';
    this.selected = options.selected || '';
  }

  _class() {
    if (!this.classes && this.classes !== 0) return false;

    if (Array.isArray(this.classes)) {
      return this.classes.join(' ');
    } if (typeof this.classes === 'string') {
      if (this.classes.indexOf(',') > -1) {
        return this.classes.split(',').map(cl => cl.trim(' ')).join(' ');
      }
      return this.classes.trim(' ');
    }
    return '';
  }

  _id() {
    if (!this.id && this.id !== 0) return false;

    if (typeof this.id === 'string') {
      return this.id;
    }
    return '';
  }

  _data() {
    if (!this.data) return false;

    let statement = '';
    for (const key in this.data) {
      const value = this.data[key].trim(' ');
      statement += `data-${key}="${value}" `;
    }
    return statement.trimRight(' ');
  }

  _options() {
    let tag = '';

    if (Array.isArray(this.options)) {
      this.options.forEach((option) => {
        const selected = (this.selected === option) ? ' selected ' : ' ';
        option = option.trim(' ');
        tag += `<option${selected}value="${option}">${option}</option>`;
      });
    } if (typeof this.options === 'string') {
      if (this.options.indexOf(',') > -1) {
        this.options.split(',').forEach((option) => {
          const selected = (this.selected === option) ? ' selected ' : ' ';
          option = option.trim(' ');
          tag += `<option${selected}value="${option}">${option}</option>`;
        });
      } else {
        this.options = this.options.trim(' ');
        tag += `<option value="${this.options}">${this.options}</option>`;
      }
    }
    return tag;
  }

  input() {
    let tag = `<input type="${this.type}" `;

    if (this.name) {
      tag += `name="${this.name}" `;
    }
    if (this.value && this.value !== 0) {
      tag += `value="${this.value}" `;
    }
    if (this._id()) {
      tag += `id="${this._id()}" `;
    }
    if (this._class()) {
      tag += `class="${this._class()}" `;
    }
    if (this._data()) {
      tag += this._data();
    }
    tag = `${tag.trimRight(' ')}/>`;
    return tag;
  }

  textarea() {
    let tag = '<textarea ';

    if (this.name) {
      tag += `name="${this.name}" `;
    }
    if (this._id()) {
      tag += `id="${this._id()}" `;
    }
    if (this._class()) {
      tag += `class="${this._class()}" `;
    }
    if (this._data()) {
      tag += this._data();
    }
    tag = `${tag.trimRight(' ')}>`;
    if (this.value && this.value !== 0) {
      tag += `${this.value}`;
    }
    tag += '</textarea>';
    return tag;
  }

  select() {
    let tag = '<select ';

    if (this.name) {
      tag += `name="${this.name}" `;
    }
    if (this._id()) {
      tag += `id="${this._id()}" `;
    }
    if (this._class()) {
      tag += `class="${this._class()}" `;
    }
    if (this._data()) {
      tag += this._data();
    }
    tag = `${tag.trimRight(' ')}>`;
    if (this._options()) {
      tag += this._options();
    }
    tag += '</select>';
    return tag;
  }

  button() {
    let tag = '<button ';

    if (this.name) {
      tag += `name="${this.name}" `;
    }
    if (this._id()) {
      tag += `id="${this._id()}" `;
    }
    if (this._class()) {
      tag += `class="${this._class()}" `;
    }
    if (this._data()) {
      tag += this._data();
    }
    tag = `${tag.trimRight(' ')}>`;
    if (this.value && this.value !== 0) {
      tag += `${this.value}`;
    }
    tag += '</button>';
    return tag;
  }

  create(tag = null) {
    if (!tag) tag = this.tag;
    return this[tag]();
  }
}
