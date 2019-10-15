/* eslint-disable guard-for-in */
/* eslint-disable no-restricted-syntax */
$(document).ready(() => {
  // eslint-disable-next-line prefer-arrow-callback
  $('.search-input input').keyup(function () {
    const idInput = $('#idInput').val().trim();
    const userInput = $('#userInput').val().trim();
    const countryInput = $('#countryInput').val().trim();
    const zipInput = $('#zipInput').val().trim();

    const trs = $('table tbody tr').filter(function () {
      // eslint-disable-next-line no-unused-vars
      const id = $(this).find('.user-id strong').text()
        .trim()
        .substr(1);
      // eslint-disable-next-line no-unused-vars
      const user = $(this).find('.user-name div a').text().trim();
      // eslint-disable-next-line no-unused-vars
      let country = $(this).find('.user-country i').attr('data-country');
      if (country) {
        country = country.trim();
      }
      // eslint-disable-next-line no-unused-vars
      const zip = $(this).children('.user-zip').text().trim();

      const filters = {
        id: {
          input: 'idInput',
          value: idInput,
          length: idInput.length,
          match: 'id',
        },
        user: {
          input: 'userInput',
          value: userInput,
          length: userInput.length,
          match: 'user',
        },
        country: {
          input: 'countryInput',
          value: countryInput,
          length: countryInput.length,
          match: 'country',
        },
        zip: {
          input: 'zipInput',
          value: zipInput,
          length: zipInput.length,
          match: 'zip',
        },
      };

      let code = '';
      for (const filter in filters) {
        if (filters[filter].value !== '') {
          code += `${filters[filter].match} && ${filters[filter].match}.substring(0, ${filters[filter].length}) === ${filters[filter].input}.substring(0, ${filters[filter].length}) && `;
        }
      }
      code = code.slice(0, -4);

      if (code) {
        // eslint-disable-next-line no-eval
        return eval(code);
      }

      return $(this);
    });
    $('table tbody tr').css('display', 'none');
    trs.css('display', 'table-row');
  });
});
