// eslint-disable-next-line no-unused-vars
class DateV {
  constructor(value, options = []) {
    this.format = options.format || 'd M Y';
    this.value = value;
    this.start = options.start || null;
    this.end = options.end || null;
    this.year = (this.isAdate()) ? new Date(value).getFullYear() : null;
  }

  isAdate() {
    return !Number.isNaN(new Date(this.value).getTime());
  }

  isDateBetween(start = null, end = null) {
    if (!this.start || !this.end) {
      this.start = start;
      this.end = end;
    }

    if (this.year < this.start || this.year > this.end) {
      return false;
    }
    return true;
  }

  minimum(start = null) {
    this.start = this.start || start;

    if (this.year < this.start) {
      return false;
    }
    return true;
  }

  maximum(end = null) {
    this.end = this.end || end;

    if (this.year > this.end) {
      return false;
    }
    return true;
  }

  static dateMethods(options) {
    let method = null;
    let msgMethod = null;

    if (options.start && options.end) {
      method = 'isDateBetween';
      msgMethod = `this field must be between ${options.start} and ${options.end} JS`;
    } else if (options.start) {
      method = 'minimum';
      msgMethod = `the date can't be under ${options.start}`;
    } else if (options.end) {
      method = 'maximum';
      msgMethod = `the date can't be above ${options.end}`;
    }
    return { method, msgMethod };
  }
}
