// generate action url
// problem is the url is like that http://localhost/admin/users/00000
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
function cabBeConvertedToJson(data) {
  try {
    return JSON.parse(data);
  } catch (error) {
    return window.location.reload();
  }
}

/* eslint-disable func-names */
$(document).ready(() => {
  // append alert if not exists
  // if exists change the message
  function appendAlert(msg) {
    if (!$('.alert-danger')[0]) {
      $('.form-editable').parents('.editable').prepend(`<div class="alert alert-danger" role="alert">${msg}</div>`);
    } else {
      $('.alert-danger')[0].innerHTML = msg;
    }
  }

  // open the images on click
  // close the image when click anywhere except on the image
  $('.target-img-box').click(function () {
    const src = $(this).attr('src');
    $('.img img').attr('src', src);
    $('.img-box').fadeIn();
    $(document).on('click', (event) => {
      if (!$(event.target).is('#big-img, .img-thumbnail')) {
        $('.img-box').fadeOut();
      }
    });
  });

  // eslint-disable-next-line prefer-arrow-callback
  $('.editable').click(function (event) {
    // check if the td is open before
    // check that the use dosn't click on the buttons
    if (!$(this).find('.form-editable')[0] && !$(event.target).is('.close-input-edit, .close-input-edit *, submit-input-edit, submit-input-edit *')) {
      // close all the input are opend before when click on new td
      $('.editable').each(function () {
        // get the value that storage in the data-text of the input
        const text = $(this).find('.input-edit').attr('data-text');
        $(this).html(text);
      });

      // get the value  from td
      const value = $(this).text();

      // attributes values
      const type = $(this).attr('data-type');
      const name = $(this).attr('data-name');
      const options = $(this).attr('data-options');
      const date = $(this).attr('data-date');
      const url = genetareAction('update');

      // $extraClasses will contain classes for the input in $htmlCodeInput
      let extraClasses = '';

      // if date attribute exists add date class to $extraClasses
      if (date) {
        extraClasses += ' date';
      }

      // append inputs
      let htmlCodeInput = `<input type="${type}" value="${value}" data-text="${value}" name="${name}" class="form-control input-edit${extraClasses}"/>`;

      // if options defind then it must be the select tag
      if (options) {
        // eslint-disable-next-line no-unused-vars
        let option = '';

        // loop over the options that contain in the data-options attribute
        options.split(',').forEach((op) => {
          // check the right value
          // eslint-disable-next-line prefer-const
          let selected = (op === value) ? 'selected' : '';
          option += `<option ${selected} value="${op}">${op}</option>`;
        });

        // change the $htmlCodeInput
        // insert the select tag in it
        htmlCodeInput = `
        <select class="form-control input-edit" id="ccmonth" data-text="${value}" name="${name}">
          ${option}
        </select>
        `;
      }

      // create html form
      $(this).html(`
        <form class='form-editable' method='POST' action='${url}'>
          <div>
          ${htmlCodeInput}
          </div>
          <div>
            <button class="btn btn-brand btn-sm btn-primary submit-input-edit" type="submit"><i class="fas fa-check"></i></button>
            <button class="btn btn-brand btn-sm btn-danger close-input-edit" type="button"><i class="fas fa-times"></i></button>
          </div>
        </form>
      `);

      // date picker
      $('.editable input.date').datepicker({
        format: 'dd M yyyy',
        startDate: '01/01/1920',
        endDate: '31/12/2004',
      });

      // focus on the input
      $('.input-edit').focus();

      // eslint-disable-next-line prefer-arrow-callback
      // close input on click
      $('.close-input-edit').click(function () {
        const form = $(this).parents('.form-editable')[0];
        // check if the td is open
        // if has form then is open
        if (form) {
          const input = $(form).find('.input-edit')[0];
          if (input) {
            // get the storge value
            const text = $(input).attr('data-text');
            const td = $(form).parents('.editable')[0];
            $(td).html(text);
          }
        }
      });

      // close the input when click outside the td
      // eslint-disable-next-line prefer-arrow-callback
      $(document).on('click', function (e) {
        // check if the click outside the .editable
        if (!$(e.target).is('.editable, .editable *')) {
          // get the text from attribute data-text and put it again in .editable
          // eslint-disable-next-line prefer-arrow-callback
          $('.editable').each(function () {
            const text = $(this).find('.input-edit').attr('data-text');
            $(this).html(text);
          });
        }
      });

      // ajax request for inputs
      $('.form-editable').submit(function (e) {
        e.preventDefault();
        const form = $(this);
        const action = form.attr('action');

        $.ajax({
          type: 'POST',
          url: action,
          data: form.serialize(),
          success: (data) => {
            const json = cabBeConvertedToJson(data);
            if (json.success) {
              // "not text" means that it's let the input empty so it will be jsut empty
              if (json.success !== 'no text') {
                $(this).parents('.editable').html(json.success);
              } else {
                $(this).parents('.editable').html('');
              }
            } else if (json.error) {
              const input = Object.keys(json.error)[0];
              appendAlert(json.error[input]);
            } else {
              window.location.reload();
            }
          },
          fail: () => {
            window.location.reload();
          },
        });
      });
    }
  });

  // ajax request for status
  $('.form-status').submit(function (e) {
    e.preventDefault();
    const form = $(this);
    const action = form.attr('action');

    $.ajax({
      type: 'POST',
      url: action,
      data: form.serialize(),
      success: () => {
        window.location.reload();
      },
      fail: () => {
        window.location.reload();
      },
    });
  });
});
