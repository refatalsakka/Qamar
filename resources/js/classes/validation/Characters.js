export default class Characters {
  constructor(value, object = {}) {
    this.object = object;
    this.chars = '';
    this.times = 0;
    this.atFirst = null;
    this.atEnd = null;
    this.between = null;
    this.langsRegex = '';
    this.languages = '';
    this.value = value || null;
  }

  isObject() {
    return (typeof this.object === 'object' && Object.keys(this.object).length);
  }

  isCharsString() {
    return (typeof this.chars === 'string');
  }

  isCharsAnArray() {
    return Array.isArray(this.chars);
  }

  canCharsSeparateViaComma() {
    return this.char.match(/,/) && this.char.match(/,/g).length > 1;
  }

  formatCharsViaComma($comma) {
    if ($comma) {
      this.chars = this.chars.split(',');
    } else {
      this.chars = this.chars.split('');
    }
    return `\\${this.chars.join('\\')}`;
  }

  formatCharsString() {
    return this.formatCharsViaComma(this.canCharsSeparateViaComma());
  }
}
