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
      data: {
        value,
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

  function closeElms() {
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

  function submit(columns) {
    $('.form-editable').submit(function (e) {
      e.preventDefault();

      const td = $(this).parents('.editable');

      const check = checkInput(this, columns);

      if (check !== true) return new Alert({ insertIn: td[0], msg: check }).append();

      const form = $(this);
      const action = form.attr('action');

      $.ajax({
        type: 'POST',
        url: action,
        data: form.serialize(),
        beforeSend: () => {
          td.append('<div class="disable-click"><i class="fas fa-spinner loading"></i></div>');
        },
        success: (data) => {
          const json = convertedToJson(data);
          if (json.success) {
            // "not text" means that the input is empty
            const value = (json.success !== 'no text') ? json.success.trim() : '';
            setTimeout(() => {
              td.html(value).attr('data-value', value);
              new Background({ colorClass: 'success-bg', removeAfter: 3000 }).add(td[0]);
            }, 100);
          } else if (json.error) {
            const input = Object.keys(json.error)[0];
            new Alert({ insertIn: td[0], msg: json.error[input], mood: 'danger' }).append();
          } else {
            window.location.reload();
          }
          td.find('.disable-click').remove();
        },
        fail: () => window.location.reload(),
      });
      return false;
    });
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
