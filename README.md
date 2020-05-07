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
  - *every signle page requests just own css and js file*
  - *a library can be requsted just for specfic page*

## Installation

### Requirements

- **Tools**
  - [PHP](https://www.php.net/downloads.php#gpg-7.2) v7+
  - [npm](https://www.npmjs.com/) v6+
  - [Composer](https://getcomposer.org/download/) v1.8+
  - [Gulpjs](https://gulpjs.com/) v4+

- **Extentions**
  - [Pug-Lint](https://marketplace.visualstudio.com/items?itemName=mrmlnc.vscode-puglint)
  - [Sass-Lint](https://marketplace.visualstudio.com/items?itemName=glen-84.sass-lint)
  - [ESLint](https://marketplace.visualstudio.com/items?itemName=dbaeumer.vscode-eslint)

### How to Install

Install the dependencies and devDependencies 🔥

```sh
$ cd framewwork
$ npm install
$ composser install
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

**Convert to SCSS, Compresse, Output public/css**
```sh
$ npm run scss
```
*If there was any lint error, the function will not complate.*

**Convert to JS5, Compresse, Output public/js**
```sh
$ npm run js
```
*If there was any lint error, the function will not complate.*

**Convert to HTM**
```sh
*There is no HTML. PHP will automatically convert it to HTML.*
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

**Watch all** ▶
```sh
$ npm run watch
```

**Compress Images, Convert to .png, Output public/imgs**
```sh
$ npm run imgs
```

**Copy Libraries from node_modules to public/ {js & css} /libs**
```sh
$ npm run libs
```

**Start Server**
```sh
$ npm run server
```

**Quick Git Commit**
```sh
$ npm run gitty *message*
```
