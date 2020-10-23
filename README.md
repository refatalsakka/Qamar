[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/refatalsakka/framework/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/refatalsakka/framework/?branch=master)
[![Build Status](https://scrutinizer-ci.com/g/refatalsakka/framework/badges/build.png?b=master)](https://scrutinizer-ci.com/g/refatalsakka/framework/build-status/master)

# MVC Framework PHP

## Features

- **Easy to install and to use**
  - *[Requirements](#requirements) are too easy to install*
- **Good for team work**
  - *sass, js and php have global rules*
  - *nothing will compiled if there is an error or a bug*
- **Pugjs is used for view**
  - *easy to communicate with controllers*
  - *more flexibility*
- **Speed**
  - *compressed js and css files and images*
  - *every single page requests just own css and js file*
  - *a library can be requested just for specfic page*

## Installation

### Requirements
  - [PHP](https://www.php.net/downloads.php#gpg-7.2) v7+
  - [npm](https://www.npmjs.com/) v6+
  - [Composer](https://getcomposer.org/download/) v1.8+

### Recommended IDE Extenstions
  - [Editor Config](https://marketplace.visualstudio.com/items?itemName=EditorConfig.EditorConfig)
  - [NPM](https://marketplace.visualstudio.com/items?itemName=eg2.vscode-npm-script)
  - [PHP Intelephense](https://marketplace.visualstudio.com/items?itemName=bmewburn.vscode-intelephense-client)
  - [PHP IntelliSense](https://marketplace.visualstudio.com/items?itemName=felixfbecker.php-intellisense)
  - [phpcs](https://marketplace.visualstudio.com/items?itemName=ikappas.phpcs)
  - [Pug-Lint](https://marketplace.visualstudio.com/items?itemName=mrmlnc.vscode-puglint)
  - [Sass-Lint](https://marketplace.visualstudio.com/items?itemName=glen-84.sass-lint)
  - [ESLint](https://marketplace.visualstudio.com/items?itemName=dbaeumer.vscode-eslint)
  - [GitLens](https://marketplace.visualstudio.com/items?itemName=eamodio.gitlens)
  - [Git History](https://marketplace.visualstudio.com/items?itemName=donjayamanne.githistory)

### How to Install 🔥
```sh
$ git clone git@github.com:refatalsakka/mvc-php.git
$ cd mvc-php
$ npm install
$ composer install
$ npm run build
```

**Check Lint**
```sh
$ npm run lint
```

**Check Pugjs Lint**
```sh
$ npm run lint:pug
```

**Check SCSS Lint**
```sh
$ npm run lint:sass
```

**Check JavaScript Lint**
```sh
$ npm run lint:js
```

**Check PHP Lint**
```sh
$ npm run lint:php
```

**Fix Pugjs Lint**
```sh
*There is no yet.*
```

**Fix SCSS Lint**
```sh
$ npm run lint:fix:sass
```

**Fix JavaScript Lint**
```sh
$ npm run lint:fix:js
```

**Fix PHP Lint**
```sh
$ npm run lint:fix:php
```

**Convert to SCSS, Compresse, Output public/css**
```sh
$ npm run scss
```
*If there was any lint error, the function will not complete.*

**Convert to JS5, Compresse, Output public/js**
```sh
$ npm run js
```
*If there was any lint error, the function will not complete.*

**Convert to HTM**
```sh
*There is no HTML. PHP will automatically convert it to HTML.*
```

**Watch all** ▶
```sh
$ npm run watch
```

**Watch Pug**
```sh
$ npm run watch:pug
```

**Watch CSS**
```sh
$ npm run watch:sass
```
*Watch will stop if any error being detected.*
*After fix the error, the function will work automatically again*

**Watch JavaScript**
```sh
$ npm run watch:js
```
*Watch will stop if any error being detected.*
*After fix the error, the function will work automatically again*

**Compress Images, Convert to .png, Output public/imgs**
```sh
$ npm run imgs
```

**Copy Libraries from node_modules to public/libs**
```sh
$ npm run libs
```

**Start Server**
```sh
$ npm run server
```

**Quick Git Commit**
```sh
$ npm run gitty msg
```
