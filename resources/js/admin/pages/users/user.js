$(document).ready(() => {
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

  // remove bg
  $(document).on('click', () => {
    // eslint-disable-next-line no-undef
    new Background({ colorClass: 'success-bg' }).removeAll();
  });

  // eslint-disable-next-line no-undef
  // cosnt check = new Check();
  // $.when($.getJSON('../../config/admin/users/columns.json', (data) => {
  //   columns = data;
  // })).then(() => {
  //   for (const column in columns) {
  //     const filters = columns[column].filters;
  //     for (const [func, arg] of Object.entries(filters)) {
  //       if (typeof check.input(column)[func] !== 'undefined') {
  //         if (typeof arg === 'boolean') {
  //           if (arg) {
  //             check.input(column)[func]();
  //           }
  //         } else {
  //           check.input(column)[func](arg);
  //         }
  //       }
  //     }
  //   }
  //   console.log(check.getErrors());
  // });

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
