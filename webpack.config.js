const path = require('path');

module.exports = {
  mode: 'development',
  entry: {
    // Website JS Files
    'js/website/script.js': './resources/js/website/script.js',
    'js/website/pages/home.js': './resources/js/website/pages/home.js',
    'js/website/pages/contact.js': './resources/js/website/pages/contact.js',
    'js/website/pages/imprint.js': './resources/js/website/pages/imprint.js',
    'js/website/pages/privacy.js': './resources/js/website/pages/privacy.js',
    'js/website/pages/services.js': './resources/js/website/pages/services.js',
    'js/website/pages/404.js': './resources/js/website/pages/404.js',

    // Admin JS Files
    'js/admin/script.js': './resources/js/admin/script.js',
    'js/admin/pages/home.js': './resources/js/admin/pages/home.js',
    'js/admin/pages/login.js': './resources/js/admin/pages/login.js',
    'js/admin/pages/profile.js': './resources/js/admin/pages/profile.js',
    'js/admin/pages/settings.js': './resources/js/admin/pages/settings.js',
    'js/admin/pages/users/user.js': './resources/js/admin/pages/users/user.js',
    'js/admin/pages/users/users.js': './resources/js/admin/pages/users/users.js',
    'js/admin/pages/users/new.js': './resources/js/admin/pages/users/new.js',

    // Libs JS Files
    'js/libs/update.js': './resources/js/libs/update.js',
  },
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
