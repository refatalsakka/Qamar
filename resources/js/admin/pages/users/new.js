$(document).ready(() => {
  // date picker
  $('input.date').datepicker({
    format: 'dd M yyyy',
    startDate: '01/01/1920',
    endDate: '31/12/2004',
  });
});
