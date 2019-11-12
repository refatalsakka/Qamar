/* eslint-disable consistent-return */
/* eslint-disable no-undef */
$(document).ready(() => {
  // check if the string can be parsed to JSON
  function convertedToJson(data) {
    try {
      return JSON.parse(data);
    } catch (error) {
      return window.location.reload();
    }
  }

  // date picker
  $('input.date').datepicker({
    format: 'dd M yyyy',
    startDate: '01/01/1920',
    endDate: '31/12/2004',
  });

  function checkInputs(columns) {
    const check = new Check();
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

    const errors = check.getErrors();

    return Object.keys(errors).length ? errors : true;
  }

  function showErros(errors) {
    for (const error in errors) {
      $(`#${error}`).addClass('error-input').after($(`<p class='error-msg'>${errors[error]}</p>`).hide().fadeIn(200));
    }
  }

  let columns = null;
  // ajax request for inputs
  $('form').submit(function (e) {
    e.preventDefault();

    $('.error-msg').remove();
    $('input, textarea, select').removeClass('error-input');

    if (!columns) {
      $.ajaxSetup({ async: false });
      $.getJSON('../../../config/admin/users/columns.json', (data) => {
        columns = data;
      });
    }
    const errors = checkInputs(columns);

    if (errors !== true) return showErros(errors);

    const form = $(this);
    const action = form.attr('action');

    $.ajax({
      type: 'POST',
      url: action,
      data: form.serialize(),
      beforeSend: () => {
        $(this).find('button').html('Add <i class="fas fa-spinner loading"></i>');
        $('.card').append('<div class="disable-box"></div>');
      },
      success: (data) => {
        const json = convertedToJson(data);

        if (json.success) {
          window.location = `${window.location.origin}/admin/users/${json.success}`;
        } if (json === 'reload') {
          window.location.reload();
        }

        for (const error in json) {
          $(`#${error}`).addClass('error-input').after($(`<p class='error-msg'>${json[error]}</p>`).hide().fadeIn(200));
        }

        $(this).find('button').removeClass('prevent-click').html('Add');
        $('.disable-box').remove();
      },
      fail: () => {
        window.location.reload();
      },
    });
  });
});
