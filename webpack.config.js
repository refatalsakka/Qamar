const path = require('path');

const jsFiles = {
  // Website JS Files
  'website/script.js': './resources/js/website/script.js',
  'website/pages/home.js': './resources/js/website/pages/home.js',
  'website/pages/contact.js': './resources/js/website/pages/contact.js',
  'website/pages/imprint.js': './resources/js/website/pages/imprint.js',
  'website/pages/privacy.js': './resources/js/website/pages/privacy.js',
  'website/pages/services.js': './resources/js/website/pages/services.js',
  'website/pages/404.js': './resources/js/website/pages/404.js',

  // Admin JS Files
  'admin/script.js': './resources/js/admin/script.js',
  'admin/pages/home.js': './resources/js/admin/pages/home.js',
  'admin/pages/login.js': './resources/js/admin/pages/login.js',
  'admin/pages/profile.js': './resources/js/admin/pages/profile.js',
  'admin/pages/settings.js': './resources/js/admin/pages/settings.js',
  'admin/pages/users/user.js': './resources/js/admin/pages/users/user.js',
  'admin/pages/users/users.js': './resources/js/admin/pages/users/users.js',
  'admin/pages/users/new.js': './resources/js/admin/pages/users/new.js',

  // Libs JS Files
  'libs/update.js': './resources/js/libs/update.js',
};

function main(entry) {
  return {
    mode: 'development',
    entry,
    output: {
      path: path.resolve(__dirname, 'public/'),
      filename: '[name]',
    },
    module: {
      rules: [
        {
          test: /\.m?js$/,
          exclude: /(node_modules|bower_components)/,
          use: {
            loader: 'babel-loader',
          },
        },
      ],
    },
  };
}

exports.webapckJsConfig = main(jsFiles);

// module.exports = main(Object.assign(jsFiles));
