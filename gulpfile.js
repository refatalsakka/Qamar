const gulp = require('gulp');
const { series } = require('gulp');
const pugLint = require('gulp-pug-linter');
const sass = require('gulp-sass');
const sassLint = require('gulp-sass-lint');
const eslint = require('gulp-eslint');
const autoprefixer = require('gulp-autoprefixer');
const imagemin = require('gulp-imagemin');
const sourcemaps = require('gulp-sourcemaps');
const uglify = require('gulp-uglify');
const babel = require('gulp-babel');
const browsersync = require('browser-sync').create();
const rename = require('gulp-rename');
const imagesConvert = require('gulp-images-convert');

// Folders 📁
const TEMPLATE_DIR = 'resources/template';
const SASS_DIR = 'resources/sass';
const JAVASCRIPT_DIR = 'resources/js';
const IMGAGES_DIR = 'resources/imgs';
const CSS_PUBLIC_DIR = 'public/css';
const JS_PUBLIC_DIR = 'public/js';
const IMG_PUBLIC_DIR = 'public/imgs';

// Files 🗄
const LIBS = {
  css: [
    {
      libs: [
        'node_modules/bootstrap/dist/css/bootstrap.min.css',
        'node_modules/bootstrap/dist/css/bootstrap.min.css.map',
        'node_modules/@fortawesome/fontawesome-free/css/all.min.css',
        'node_modules/normalize.css/normalize.css',
        'node_modules/@coreui/icons/css/coreui-icons.min.css',
        'node_modules/@coreui/icons/css/coreui-icons.min.css.map',
        'node_modules/flag-icon-css/css/flag-icon.min.css',
        'node_modules/simple-line-icons/css/simple-line-icons.css',
        'node_modules/@coreui/coreui/dist/css/coreui.min.css',
        'node_modules/@coreui/coreui/dist/css/coreui.min.css.map',
        'node_modules/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css',
      ],
    },
    {
      fonts: [
        'node_modules/font-awesome/fonts/*',
        'node_modules/simple-line-icons/fonts/*',
        'node_modules/@coreui/icons/fonts/*',
      ],
    },
    {
      webfonts: [
        'node_modules/@fortawesome/fontawesome-free/webfonts/*',
      ],
    },
    {
      flags: [
        'node_modules/flag-icon-css/flags/**/*',
      ],
    },
  ],
  js: [
    {
      libs: [
        'node_modules/bootstrap/dist/js/bootstrap.min.js',
        'node_modules/bootstrap/dist/js/bootstrap.min.js.map',
        'node_modules/@fortawesome/fontawesome-free/js/all.min.js',
        'node_modules/jquery/dist/jquery.min.js',
        'node_modules/popper.js/dist/umd/popper.min.js',
        'node_modules/popper.js/dist/umd/popper.min.js.map',
        'node_modules/@coreui/coreui/dist/js/coreui.min.js',
        'node_modules/@coreui/coreui/dist/js/coreui.min.js.map',
        'node_modules/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js',
      ],
    },
  ],
};

// Check Templates pug-lint Cehck ✅
function templateLint() {
  return gulp.src(`${TEMPLATE_DIR}/**/*.pug`)
    .pipe(pugLint({ reporter: 'puglint-stylish' }));
}
exports.templateLint = templateLint;

// Check Style sass-lint Cehck ✅
function styleLint() {
  return gulp.src(`${SASS_DIR}/**/*.scss`)
    .pipe(sassLint({
      configFile: '.sass-lint.yml',
    }))
    .pipe(sassLint.format())
    .pipe(sassLint.failOnError());
}
exports.styleLint = styleLint;

// Check JavaScript eslint Cehck ✅
function scriptsLint() {
  return gulp
    .src(`${JAVASCRIPT_DIR}/**/*.js`)
    .pipe(eslint())
    .pipe(eslint.format())
    .pipe(eslint.failAfterError());
}
exports.scriptsLint = scriptsLint;

// Style lint Cehck ✅ Convert 🔂 Compresse 🔄 Output ↪ 📁 public/css
async function styles() {
  return gulp
    .src([`${SASS_DIR}/**/*.scss`])
    .pipe(sourcemaps.init({ loadMaps: true }))
    .pipe(sass({ outputStyle: 'compressed' }))
    .on('error', sass.logError)
    .pipe(autoprefixer())
    .pipe(sourcemaps.write())
    .pipe(gulp.dest(`${CSS_PUBLIC_DIR}`));
}
exports.styles = series(styleLint, styles);

// JavaScript lint Cehck ✅ Convert 🔂 Compresse 🔄 Output ↪ 📁 public/js
async function scripts() {
  return gulp
    .src(`${JAVASCRIPT_DIR}/**/*.js`)
    .pipe(sourcemaps.init({ loadMaps: true }))
    .pipe(babel())
    .pipe(uglify())
    .pipe(sourcemaps.write())
    .pipe(gulp.dest(`${JS_PUBLIC_DIR}`));
}
exports.scripts = series(scriptsLint, scripts);

// Copy SVG Output ↪ 📁 public/imgs
async function imgmSvg() {
  return gulp
    .src(`${IMGAGES_DIR}/**/*.svg`)
    .pipe(gulp.dest(`${IMG_PUBLIC_DIR}`));
}
exports.imgmSvg = imgmSvg;

// Images Compress 🔄 Convert to .WEPB 🔂 Output ↪ 📁 public/imgs
async function imgmin() {
  return gulp
    .src(`${IMGAGES_DIR}/**/*.+(jpg|png|webp)`)
    .pipe(imagemin())
    .pipe(imagesConvert({ targetType: 'webp' }))
    .pipe(rename({ extname: '.webp' }))
    .pipe(gulp.dest(`${IMG_PUBLIC_DIR}`));
}
exports.imgmin = gulp.parallel(imgmin, imgmSvg);

// Libraries Copy  ↪ 📁 node_modules/ Output ↪ 📁 public/ {js & css} /libs
async function libraries() {
  for (const LIB in LIBS) {
    LIBS[LIB].map((output) => {
      if (typeof output === 'object') {
        for (const extra in output) {
          output[extra].map(outputExtra => gulp
            .src(`${outputExtra}`)
            .pipe(gulp.dest(`public/${LIB}/${extra}`)));
        }
      } else {
        return gulp
          .src(`${output}`)
          .pipe(gulp.dest(`public/${LIB}`));
      }
    });
  }
}
exports.libraries = libraries;

// Start the Server 🖥
function server() {
  browsersync.init({
    proxy: 'localhost',
  });
}
exports.server = server;

// Check pug-lint Cehck ✅ Watch ⏳ // Output via PHP
function watchTemplate() {
  return gulp.watch(`${TEMPLATE_DIR}/**/*.pug`, templateLint);
}
exports.watchTemplate = watchTemplate;

// Check lint-sass Cehck ✅ Compress 🔄 Output ↪ public/css 📁 Watch ⏳
function watchStyles() {
  return gulp.watch(`${SASS_DIR}/**/*.scss`, series(styleLint, styles));
}
exports.watchStyles = watchStyles;

// Check lint-js Cehck ✅ Compress 🔄 Output ↪ public/js 📁 Watch ⏳
function watchScripts() {
  return gulp.watch(`${JAVASCRIPT_DIR}/**/*.js`, series(scriptsLint, scripts));
}
exports.watchScripts = watchScripts;

// Run the main Plugins ▶
exports.default = gulp.parallel(watchStyles, watchScripts, watchTemplate);

// Build the Plugins 🔥
gulp.task('build', gulp.series(templateLint, series(styleLint, styles), series(scriptsLint, scripts), series(imgmin, imgmSvg, libraries)));
