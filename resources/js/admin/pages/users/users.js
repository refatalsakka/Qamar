/* eslint-disable no-unused-vars */
/* eslint-disable guard-for-in */
/* eslint-disable no-restricted-syntax */
$(document).ready(() => {
  // date picker
  $('#register-from').datepicker({
    format: 'dd M yyyy',
    startDate: '01/01/2019',
  });
  $('#register-to').datepicker({
    format: 'dd M yyyy',
  });
  $('#register-from').change(function () {
    $('#register-to').val('');
    if ($(this).val() !== '') {
      $('#register-to').removeAttr('disabled');
      const dataStart = $(this).val();
      $('#register-to').datepicker('setStartDate', dataStart);
    } else {
      $('#register-to').attr('disabled', 'disabled');
    }
  });

  let filterSection = false;
  $('.filter-icon').click(function () {
    let addClass;
    let removeClass;
    if (filterSection) {
      addClass = 'fa-chevron-up';
      removeClass = 'fa-chevron-down';
      filterSection = false;
    } else {
      addClass = 'fa-chevron-down';
      removeClass = 'fa-chevron-up';
      filterSection = true;
    }
    $(this).find('svg').addClass(addClass);
    $(this).find('svg').removeClass(removeClass);
    $('.filter form').slideToggle();
  });

  function filterUsersTable() {
    const idInput = $('#idInput').val().trim();
    const userInput = $('#userInput').val().trim();

    return $('table tbody tr').filter(function () {
      const id = $(this).find('.user-id strong').text().trim()
        .substr(1);
      const user = $(this).find('.user-name div a').text().trim();

      const filters = {
        id: {
          input: 'idInput',
          value: idInput,
          inputLength: idInput.length,
          match: 'id',
        },
        user: {
          input: 'userInput',
          value: userInput,
          inputLength: userInput.length,
          match: 'user',
        },
      };

      let code = '';

      for (const filter in filters) {
        if (filters[filter].value !== '' && filters[filter].value !== '-' && filters[filter].value !== '+') {
          code += `${filters[filter].match} && ${filters[filter].match}.substring(0, ${filters[filter].inputLength}) === ${filters[filter].input}.substring(0, ${filters[filter].inputLength}) && `;
        } else if (filters[filter].value === '-') {
          code += `(${filters[filter].match} === '' || ${filters[filter].match} === undefined) && `;
        } else if (filters[filter].value === '+') {
          code += `${filters[filter].match} && `;
        }
      }

      code = code.slice(0, -4);

      if (code) {
        // eslint-disable-next-line no-eval
        return eval(code);
      }
      return $(this);
    });
  }

  $('.search-input input').on('keyup', () => {
    const trs = filterUsersTable();
    $('table tbody tr').css('display', 'none');
    trs.css('display', 'table-row');
  });
});
