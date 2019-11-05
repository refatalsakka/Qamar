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
    const type = $(elm).attr('data-type') || 'text';
    const name = $(elm).attr('data-name');
    const value = $(elm).attr('data-value') || $(elm).text();

    if (tag === 'input') {
      if (type === 'date') {
        return `<input type="text" value="${value}" data-text="${value}" name="${name}" class="form-control input-edit date"/>`;
      }
      return `<input type="${type}" value="${value}" data-text="${value}" name="${name}" class="form-control input-edit"/>`;
    }
    if (tag === 'textarea') {
      return `<textarea value="${value}" data-text="${value}" name="${name}" class="form-control input-edit">${value}</textarea>`;
    }
    if (tag === 'select') {
      const options = $(elm).attr('data-options');
      if (options) {
        let option = '';
        // loop over the options that contain in the data-options attribute
        options.split(',').forEach((op) => {
          // check the right value
          const selected = (op === value) ? 'selected' : '';
          option += `<option ${selected} value="${op}">${op}</option>`;
        });
        // insert the select tag in it
        return `
        <select class="form-control input-edit" data-text="${value}" name="${name}">
          ${option}
        </select>
        `;
      }
      return '<select class="form-control input-edit" data-text="" name=""></select>';
    }
    return false;
  }

  function isElmOpen(elm) {
    return $(elm).find('.form-editable').length > 0;
  }

  function isClickOnElm(e) {
    return $(e.target).is('.editable');
  }

  function closeAllElms() {
    // close all the elms that was opened
    $('.editable').each(function () {
      // get the value that storage in the data-text of the elm
      const text = $(this).find('.input-edit').attr('data-text');
      $(this).html(text);
    });
  }

  // create html form and insert the elm in it
  function createFormInElm(elm) {
    const url = genetareAction('update');
    const htmlCodeInput = createElm(elm);

    $(elm).html(`
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
  }

  function closeElmOnClick() {
    $('.btn-cl').click(function () {
      const form = $(this).parents('.form-editable');
      // check if the td is open
      // if has form then the elm is open
      if (form.length) {
        const input = $(form).find('.input-edit');
        if (input.length) {
          // get the storge value
          const text = $(input).attr('data-text');
          // set the value in the elm
          $(form).parents('.editable').html(text);
        }
      }
    });
  }

  // remove the background fronm td when clicking on the document
  $(document).on('click', (e) => {
    if (!$(e.target).is('.editable, .editable *')) closeAllElms();
  });

  $('.editable').click(function (event) {
    // check if the td is open before
    // check that the user doesn't click on the buttons
    if (isElmOpen(this) || !isClickOnElm(event)) return;

    closeAllElms();

    createFormInElm(this);

    // date picker
    $('.editable input.date').datepicker({
      format: 'dd M yyyy',
      startDate: '01/01/1920',
      endDate: '31/12/2004',
    });

    // focus on the elm
    $('.input-edit').focus();

    closeElmOnClick();

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
            let value = '';
            // "not text" means that it's let the input empty so it will be jsut empty
            if (json.success !== 'no text') value = json.success.trim();
            td.html(value);
            td.attr('data-value', value);
            // eslint-disable-next-line no-undef
            const bg = new Background({
              colorClass: 'success-bg',
              removeAfter: 3000,
            });
            bg.add(td[0]);
          } else if (json.error) {
            const input = Object.keys(json.error)[0];
            // eslint-disable-next-line no-undef
            const alert = new Alert({
              insertIn: td[0],
              msg: json.error[input],
              mood: 'danger',
            });
            alert.append();
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
  });
});
