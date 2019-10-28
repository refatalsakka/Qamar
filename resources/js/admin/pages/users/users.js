/* eslint-disable no-duplicate-case */
/* eslint-disable no-fallthrough */
/* eslint-disable default-case */
// check if the string can be parsed to JSON
function convertedToJson(data) {
  try {
    return JSON.parse(data);
  } catch (error) {
    return window.location.reload();
  }
}
/* eslint-disable no-unused-vars */
/* eslint-disable guard-for-in */
/* eslint-disable no-restricted-syntax */
$(document).ready(() => {
  // date picker
  $('.filter .register-from').datepicker({
    format: 'dd M yyyy',
    startDate: '01/01/2019',
  });
  $('.filter .register-to').datepicker({
    format: 'dd M yyyy',
  });
  $('.filter .register-from').change(function () {
    $('.filter .register-to').val('');
    if ($(this).val() !== '') {
      $('.filter .register-to').removeAttr('disabled');
      const dataStart = $(this).val();
      $('.filter .register-to').datepicker('setStartDate', dataStart);
    } else {
      $('.filter .register-to').attr('disabled', 'disabled');
    }
  });

  let filterSection = false;
  $('.filter-icon').click(function () {
    let addClass;
    let removeClass;
    if (filterSection) {
      addClass = 'fa-chevron-down';
      removeClass = 'fa-chevron-up';
      filterSection = false;
    } else {
      addClass = 'fa-chevron-up';
      removeClass = 'fa-chevron-down';
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

  function createUser(...args) {
    const user = args[0];
    const isNew = user.new ? '<span class="badge badge-success">new</span> ' : '';
    const gender = user.gender === 'male' ? '<i class="fas fa-mars"></i>' : '<i class="fas fa-venus"></i>';
    const zip = user.zip || '';
    let status = '';
    const img = user.img === 'avatar.webp' ? `imgs/logos/${user.img}` : `uploads/images/users/${user.img}`;
    const isLogin = user.is_login === '1' ? 'badge-success' : 'badge-danger';

    switch (user.status) {
      case '0':
        status = '<span class="badge badge-danger" data-status="0">Inactive</span>';
        break;
      case '1':
        status = '<span class="badge badge-warning" data-status="1">Pending</span>';
        break;
      case '2':
        status = '<span class="badge badge-success" data-status="2">Active</span>';
        break;
    }
    return `
      <tr>
        <td class="text-center user-id"><strong>#${user.id}</strong></td>
        <td class="text-center">
          <div class="avatar">
            <img class="img-avatar" src="http://localhost/public/${img}" alt="${user.fname} ${user.lname}">
            <span class="avatar-status ${isLogin}"></span>
          </div>
        </td>
        <td class="user-name">
          <div>
            <a href="http://localhost/admin/users/${user.id}">${user.fname} ${user.lname}</a>
          </div>
          <div class="small text-muted">
          ${isNew} Registered: ${user.registration}
          </div>
        </td>
        <td class="text-center user-country">
          <i class="flag-icon h4 mb-0 ${user.country_Icon}" title="${user.country}" data-country="${user.country}"></i>
        </td>
        <td class="text-center user-zip">${zip}</td>
        <td class="text-center user-status">${status}</td>
        <td class="text-center user-status">${gender}</td>
      </tr>
    `;
  }

  $('.search-input input').on('keyup', () => {
    const trs = filterUsersTable();
    $('table tbody tr').css('display', 'none');
    trs.css('display', 'table-row');
  });

  $('.filter form input, .filter form select').change(() => {
    $('.filter form').submit();
  });

  $('.filter form').submit(function (e) {
    e.preventDefault();

    const form = $(this);
    const action = form.attr('action');

    $.ajax({
      type: 'GET',
      url: action,
      data: form.serialize(),
      beforeSend: () => {
        $(this).find('button').html('Searching <i class="fas fa-spinner loading"></i>');
        $('.container-fluid').append('<div class="disable-box"></div>');
      },
      success: (data) => {
        const users = convertedToJson(data);

        if (users === 'no users') {
          if (!$(this).find('.alert-danger')[0]) {
            $(this).append('<p class="alert alert-danger text-center">No user has been found</p>');
          }
          $('.table tbody').html('');
          $(this).find('button').removeClass('prevent-click').html('Search');
          $('.disable-box').remove();
          return;
        }
        let html = '';
        for (const user in users) {
          html += createUser(users[user]);
        }
        $(this).find('.alert-danger').remove();
        $('.table tbody').html(html);
        $(this).find('button').removeClass('prevent-click').html('Search');
        $('.disable-box').remove();
      },
    });
  });
});
