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
