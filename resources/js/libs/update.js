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

  // check if the string can be parsed to JSON
  function convertedToJson(data) {
    try {
      return JSON.parse(data);
    } catch (error) {
      return window.location.reload();
    }
  }

  function createElm(elm) {
    // attributes values
    const tag = $(elm).attr('data-tag') || 'input';
    let type = $(elm).attr('data-type') || 'text';
    const name = $(elm).attr('data-name');
    const value = $(elm).attr('data-value') || $(elm).text();
    const options = $(elm).attr('data-options');
    const classes = ['form-control', 'input-edit'];

    if (type === 'date') {
      classes.push('date');
      type = 'text';
    }

    const statment = new CreateElm({
      type,
      name,
      value,
      classes,
      options,
      data: {
        value,
        type,
      },
    });
    return statment.create(tag);
  }

  function isElmOpen(elm) {
    return $(elm).find('.form-editable').length > 0;
  }

  function isClickOnElm(e) {
    return $(e.target).is('.editable');
  }

  function closeElms() {
    // close all the elms that was opened
    return $('.editable').each(function () {
      // get the value that storage in the data-value of the elm
      const value = $(this).find('.input-edit').attr('data-value');
      $(this).html(value);
    });
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

  function closeElm() {
    return $('.btn-cl').click(function () {
      const form = $(this).parents('.form-editable');
      // check if the td is open
      if (!form.length) return;
      const input = $(form).find('.input-edit');
      // get the storge value
      const value = $(input).attr('data-value');
      // set the value in the elm
      $(form).parents('.editable').html(value);
    });
  }

  let columns = null;
  async function submit() {
    const check = new Check();

    if (!columns) {
      columns = await $.when($.getJSON('../../../config/admin/users/columns.json', data => data)).then(data => data);
    }

    $('.form-editable').submit(function (e) {
      e.preventDefault();

      for (const column in columns) {
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
      }
      console.log(check.getErrors());

      const form = $(this);
      const action = form.attr('action');
      const td = $(this).parents('.editable');

      $.ajax({
        type: 'POST',
        url: action,
        data: form.serialize(),
        beforeSend: () => {
          td.append('<div class="disable-box"><i class="fas fa-spinner loading"></i></div>');
        },
        success: (data) => {
          const json = convertedToJson(data);
          if (json.success) {
            let value = '';
            // "not text" means that it's let the input empty so it will be jsut empty
            if (json.success !== 'no text') value = json.success.trim();
            td.html(value);
            td.attr('data-value', value);
            new Background({
              colorClass: 'success-bg',
              removeAfter: 3000,
            }).add(td[0]);
          } else if (json.error) {
            const input = Object.keys(json.error)[0];
            new Alert({
              insertIn: td[0],
              msg: json.error[input],
              mood: 'danger',
            }).append();
          } else {
            window.location.reload();
          }
          td.find('.disable-box').remove();
        },
        fail: () => {
          window.location.reload();
        },
      });
    });
  }

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
      format: 'dd M yyyy',
      startDate: '01/01/1920',
      endDate: '31/12/2004',
    });

    $('.input-edit').focus();

    closeElm();

    submit();
  });
});
