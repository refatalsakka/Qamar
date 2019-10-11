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

#### Task Runers

Install the dependencies and devDependencies

```sh
$ cd framewwork
$ npm install
$ composser install
```

#### Gulpjs

To build and run the Plugins

**Check Style lint**
```sh
$ gulp styleLint
```

**Check Javascript lint**
```sh
$ gulp scriptsLint
```

**Convert to SCSS, Compresse, Output public/css**
```sh
$ gulp styles
```

**Convert to JS5, Compresse, Output public/js**
```sh
$ gulp scripts
```
*If there was any lint error, the function will not complate.*

**Watch Pug**
```sh
$ gulp templateLint
```

**Watch CSS**
```sh
$ gulp watchStyles
```

**Watch JavaScript**
```sh
$ gulp watchScripts
```
*Watch will stop if any error being detected.*
*After fix the error, the function will work automatically again*

**Compress Images, Convert to .wepb, Output public/imgs**
```sh
$ gulp imgmin
```

**Copy Libraries from node_modules to public/ {js & css} /libs**
```sh
$ gulp libraries
```

**Start Server**
```sh
$ gulp server
```

**Run the main Plugins** ▶
```sh
$ gulp default
```

**Building and runing the Plugins in one Command** 🔥

```sh
$ gulp build
```
**OR**
```sh
$ npm run build
```

## Usage
TEST
