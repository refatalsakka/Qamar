// eslint-disable-next-line import/extensions
// import Check from '../classes/Check.js';
/* eslint-disable no-undef */
$(document).ready(() => {
  // generate action URL
  // problem is the url is like http://localhost/admin/users/00000
  // and when e.g. updating must be like that http://localhost/admin/users/updae/00000
  // the method parameter will be insert after users and befor id
  function genetareAction(method) {
    const url = new URL(window.location.href);
    const { origin } = url;
    const { pathname } = url;
    const id = pathname.split('/').pop();
    const newUrl = `${origin}${pathname.slice(0, pathname.length - id.length)}${method}/${id}`;
    return newUrl;
  }

  // eslint-disable-next-line no-unused-vars
  function convertedToJson(data) {
    try {
      return JSON.parse(data);
    } catch (error) {
      return window.location.reload();
    }
  }

  function createElm(elm) {
    const tag = $(elm).attr('data-tag') || 'input';
    let type = $(elm).attr('data-type') || 'text';
    const name = $(elm).attr('data-name');
    const value = $(elm).attr('data-value') || $(elm).text();
    const options = $(elm).attr('data-options');
    const id = $(elm).attr('data-name');
    const classes = ['form-control', 'input-edit'];
    const selected = $(elm).attr('data-value');

    if (type === 'date') {
      classes.push('date');
      type = 'text';
    }

    return new CreateElm({
      type,
      name,
      value,
      id,
      classes,
      options,
      selected,
      data: {
        type,
      },
    }).create(tag);
  }

  function isElmOpen(elm) {
    return $(elm).find('.form-editable').length > 0;
  }

  function isClickOnElm(e) {
    return $(e.target).is('.editable');
  }

  // create html form and insert the elm in it
  function createForm(elm) {
    const url = genetareAction('update');
    const htmlCodeInput = createElm(elm);
    const btnPrimary = '<button class="btn btn-brand btn-sm btn-primary btn-sub" type="submit"><i class="fas fa-check"></i></button>';
    const btnDanger = '<button class="btn btn-brand btn-sm btn-danger btn-cl" type="button"><i class="fas fa-times"></i></button>';
    const value = `<div>${htmlCodeInput}</div><div>${btnPrimary} ${btnDanger}</div>`;
    const form = `<form class='form-editable' method='POST' action='${url}' autocomplete='off'>${value}</form>`;
    $(elm).html(form);
  }

  function closeElms() {
    return $('.editable').each(function () {
      // get the value that storage in the data-value of the elm
      const name = $(this).attr('data-name');
      const icon = $(this).attr('data-icon');
      const value = $(this).attr('data-value') || '';
      if (name === 'country') {
        $(this).html(`<i class="flag-icon h4 mb-0 ${icon}" title="${value}"></i>${value}`);
      } else {
        $(this).html(value);
      }
    });
  }

  function closeElm() {
    return $('.btn-cl').click(function () {
      const editable = $(this).parents('.editable');
      // get the storge value
      const name = $(editable).attr('data-name');
      const icon = $(editable).attr('data-icon');
      const value = $(editable).attr('data-value') || '';
      // set the value in the elm
      if (name === 'country') {
        $(editable).html(`<i class="flag-icon h4 mb-0 ${icon}" title="${value}"></i>`);
      } else {
        $(editable).html('.editable').html(value);
      }
    });
  }

  // eslint-disable-next-line no-unused-vars
  function checkInput(elm, columns) {
    const check = new Check();
    const column = $(elm).find('.input-edit').attr('name');
    const filters = columns[column].filters;

    for (const [func, arg] of Object.entries(filters)) {
      if (typeof check.input(column)[func] !== 'undefined') {
        if (typeof arg === 'boolean') {
          if (arg) {
            check.input(column)[func]();
          }
        } else {
          check.input(column)[func](arg);
        }
      }
    }

    const errors = check.getErrors();

    return errors[column] ? errors[column] : true;
  }

  // eslint-disable-next-line no-unused-vars
  function submit(columns) {

  }

  let columns = null;
  $(document).on('click', (e) => {
    // remove the background fronm td when clicking on the document
    if (!$(e.target).is('.editable, .editable *')) closeElms();
    // remove bg
    new Background({ colorClass: 'success-bg' }).removeAll();
  });

  $('.editable').click(function (event) {
    if (isElmOpen(this) || !isClickOnElm(event)) return;

    closeElms();
    createForm(this);

    $('.editable input.date').datepicker({
      defaultViewDate: '01/01/1970',
      format: 'dd M yyyy',
      startDate: '01/01/1920',
      endDate: '31/12/2004',
    });

    $('.input-edit').focus();

    closeElm();

    if (!columns) {
      $.ajaxSetup({ async: false });
      $.getJSON('../../../config/admin/users/columns.json', (data) => {
        columns = data;
      });
    }
    // eslint-disable-next-line consistent-return
    return submit(columns);
  });
});
