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

// add green background to td after insert successfully
// remove the background after 3 second
function addSuccessBg(td) {
  td.addClass('success-bg');

  setTimeout(() => {
    td.removeClass('success-bg');
  }, 3000);
}

// check if the string can be parsed to JSON
function convertedToJson(data) {
  try {
    return JSON.parse(data);
  } catch (error) {
    return window.location.reload();
  }
}

$(document).ready(() => {
  // remove the background fronm td when clicking on the document
  $(document).on('click', () => {
    $('.editable').removeClass('success-bg');
  });

  // append alert if not exists
  // if exists change the message
  function appendAlert(td, msg) {
    if (!$('.alert-danger')[0]) {
      td.prepend(`<div class="alert alert-danger" role="alert">${msg}</div>`);
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

  $('.editable').click(function (event) {
    // check if the td is open before
    // check that the use dosn't click on the buttons
    if (!$(this).find('.form-editable')[0] && !$(event.target).is('btn-cl, btn-cl *, btn-sub, btn-sub *')) {
      // close all the input are opend before when click on new td
      $('.editable').each(function () {
        // get the value that storage in the data-text of the input
        const text = $(this).find('.input-edit').attr('data-text');
        $(this).html(text);
      });

      // get the value from td
      const value = $(this).text();

      // attributes values
      const type = $(this).attr('data-type');
      const name = $(this).attr('data-name');
      const date = $(this).attr('data-date');
      const url = genetareAction('update');
      const tag = $(this).attr('data-tag') || 'input';

      // $extraClasses will contain classes for the input in $htmlCodeInput
      let extraClasses = '';

      // if date attribute exists add date class to $extraClasses
      if (date) {
        extraClasses += ' date';
      }

      // append inputs
      let htmlCodeInput = '';

      if (tag === 'input') {
        htmlCodeInput = `<input type="${type}" value="${value}" data-text="${value}" name="${name}" class="form-control input-edit${extraClasses}"/>`;
      } else if (tag === 'textarea') {
        htmlCodeInput = `<textarea value="${value}" data-text="${value}" name="${name}" class="form-control input-edit${extraClasses}">${value}</textarea>`;
      } else if (tag === 'select') {
        const options = $(this).attr('data-options');
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
          <select class="form-control input-edit" data-text="${value}" name="${name}">
            ${option}
          </select>
          `;
        } else {
          htmlCodeInput = '<select class="form-control input-edit" data-text="" name=""></select>';
        }
      }

      // create html form
      $(this).html(`
        <form class='form-editable' method='POST' action='${url}' autocomplete='off'>
          <div>
          ${htmlCodeInput}
          </div>
          <div>
            <button class="btn btn-brand btn-sm btn-primary btn-sub" type="submit"><i class="fas fa-check"></i></button>
            <button class="btn btn-brand btn-sm btn-danger btn-cl" type="button"><i class="fas fa-times"></i></button>
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

      // close input on click
      $('.btn-cl').click(function () {
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
              // "not text" means that it's let the input empty so it will be jsut empty
              if (json.success === 'no text') {
                td.html('');
              } else {
                td.html(json.success.trim());
              }

              addSuccessBg(td);
            } else if (json.error) {
              const input = Object.keys(json.error)[0];
              appendAlert(td, json.error[input]);
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
