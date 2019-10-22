$(document).ready(() => {
  function IsJsonString(str) {
    try {
      JSON.parse(str);
    } catch (e) {
      return false;
    }
    return true;
  }

  function appendAlert(msg) {
    if (!$('.alert-danger')[0]) {
      $('.form').prepend(`<div class="alert alert-danger" role="alert">${msg}</div>`);
    } else {
      $('.alert-danger')[0].innerHTML = msg;
    }
  }

  function removeAlert() {
    $('.alert-danger').remove();
  }

  function checkEmptyInputs() {
    const inputs = [...document.querySelectorAll('.form-control')];
    const error = inputs.filter(input => input.value === '');
    return error.length === 0;
  }

  function checkTheInputsLength() {
    const inputs = [...document.querySelectorAll('.form-control')];
    const error = inputs.filter(input => input.value.length < input.dataset.minlength);
    return error.length === 0;
  }

  function animateAndMsg(msg) {
    appendAlert(msg);

    $('.card-group').addClass('wrong');

    setTimeout(() => {
      $('.card-group').removeClass('wrong');
    }, 300);
  }

  function disableButton() {
    $('.submit-btn').attr('disabled', 'disabled');
  }

  function ableButton() {
    $('.submit-btn').removeAttr('disabled', 'disabled');
  }

  function addLoading() {
    $('.submit-btn').html('Loading <i class="fas fa-spinner loading"></i>');
  }

  function removeLoading() {
    $('.submit-btn').html('Login');
  }

  $('.form').submit(function (e) {
    e.preventDefault();

    const form = $(this);
    const url = form.attr('action');

    $.ajax({
      type: 'POST',
      url,
      data: form.serialize(),
      beforeSend: () => {
        disableButton();
        addLoading();
        if (!checkEmptyInputs() || !checkTheInputsLength()) {
          animateAndMsg('Please check the inputs');
          ableButton();
          removeLoading();
          return false;
        }
        return true;
      },
      success: (data) => {
        let msg = 'Sorry, Something went wrong';
        if (IsJsonString(data)) {
          const json = JSON.parse(data);
          if (json.success || json.error === 'reload') {
            removeAlert();
            return window.location.reload();
          }
          msg = json.error;
        }
        animateAndMsg(msg);
        ableButton();
        removeLoading();
        return false;
      },
    });
  });
});
