/* eslint-disable no-unused-vars */
/* eslint-disable guard-for-in */
/* eslint-disable no-restricted-syntax */
$(document).ready(() => {
  function filterUsersTable() {
    const idInput = $('#idInput').val().trim();
    const userInput = $('#userInput').val().trim();
    const countryInput = $('#countryInput').val().trim();
    const zipInput = $('#zipInput').val().trim();

    return $('table tbody tr').filter(function () {
      const id = $(this).find('.user-id strong').text().trim()
        .substr(1);
      const user = $(this).find('.user-name div a').text().trim();
      let country = $(this).find('.user-country i').attr('data-country');
      if (country) { country = country.trim(); }
      const zip = $(this).find('.user-zip').text().trim();

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
        country: {
          input: 'countryInput',
          value: countryInput,
          inputLength: countryInput.length,
          match: 'country',
        },
        zip: {
          input: 'zipInput',
          value: zipInput,
          inputLength: zipInput.length,
          match: 'zip',
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
