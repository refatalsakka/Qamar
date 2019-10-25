/* eslint-disable guard-for-in */
/* eslint-disable no-restricted-syntax */
// check if the string can be parsed to JSON
function convertedToJson(data) {
  try {
    return JSON.parse(data);
  } catch (error) {
    return window.location.reload();
  }
}

$(document).ready(() => {
  // date picker
  $('input.date').datepicker({
    format: 'dd M yyyy',
    startDate: '01/01/1920',
    endDate: '31/12/2004',
  });

  // ajax request for inputs
  $('form').submit(function (e) {
    e.preventDefault();
    const form = $(this);
    const action = form.attr('action');

    $('.error-msg').remove();
    $('input, textarea, select').removeClass('error-input');

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
