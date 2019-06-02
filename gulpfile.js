const gulp = require('gulp');
const gutil = require('gulp-util');
const rename = require('gulp-rename');
const sass = require('gulp-sass');
const watchSass = require('gulp-watch-sass');
const image = require('gulp-image');
const sourcemaps = require('gulp-sourcemaps');
const connect = require('gulp-connect');
const _if = require('gulp-if');
const ifElse = require('gulp-if-else');



// gulp.task('server', done => {
//     connect.server({
//         root: '',
//         livereload: true,
//         port: 8888
//     });
//     done();
// });


const srcFiles = {
    'css': [
        'node_modules/bootstrap/dist/css/bootstrap.min.css',
        'node_modules/@fortawesome/fontawesome-free/css/all.min.css'
    ],
    'js': [
        'node_modules/bootstrap/dist/js/bootstrap.min.js',
        'node_modules/jquery/dist/jquery.min.js',
        'node_modules/html5shiv/dist/html5shiv.min.js'

    ],
    'fontAweSome': [
        'node_modules/@fortawesome/fontawesome-free/css/all.min.css'
    ],
    'webfonts': [
        'node_modules/@fortawesome/fontawesome-free/webfonts/*',
    ],
    'logos': [
        'images/logos/*',
    ]
};

const names = {
    'node_modules/@fortawesome/fontawesome-free/css/all.min.css': 'fontawesome.css'
};

function checkRename(file) {
    for (file in names) {
        return true;
    }
}


gulp.task('copyCss', done => {
 
    
    
    done();
});
 


// names[srcFiles['css']]

// gulp.task('copyCss', done => {
//     for(let i = 0; srcFiles['css'].length < 2 ;i++) {
//         gulp.src(srcFiles['css'][i])
//             .pipe(rename(names[srcFiles['css'][i]]))
//             .pipe(gulp.dest('public/css'));
//         }
//     done();
// });

// gulp.task('copyJs', () => gulp.src(srcFiles['js']).pipe(gulp.dest('public/js')));

// gulp.task('copyWebfonts', () => gulp.src(srcFiles['webfonts']).pipe(gulp.dest('public/webfonts')));

// gulp.task('logos', () => gulp.src(srcFiles['logos']).pipe(image()).pipe(gulp.dest('public/images/logos')));

// gulp.task('homeBG', () => gulp.src(srcFiles['homeBG']).pipe(image()).pipe(gulp.dest('public/images/home/backgrounds')));

// gulp.task('homeServices', () => gulp.src(srcFiles['homeServices']).pipe(image()).pipe(gulp.dest('public/images/home/services')));

// gulp.task('homeProjects', () => gulp.src(srcFiles['homeProjects']).pipe(image()).pipe(gulp.dest('public/images/home/projects')));

// gulp.task('homeContact', () => gulp.src(srcFiles['homeContact']).pipe(image()).pipe(gulp.dest('public/images/home/contact')));

// gulp.task('homeAboutus', () => gulp.src(srcFiles['homeAboutus']).pipe(image()).pipe(gulp.dest('public/images/home/aboutus')));

// gulp.task('compressBG', ['homeBG', 'homeServices', 'homeProjects', 'homeContact', 'homeAboutus']);

// gulp.task('copySass', () => gulp.src('sass/*.scss')
//     .pipe(sourcemaps.init())
//     .pipe(sass({outputStyle:'compressed'}))
//     .pipe(sourcemaps.write())
//     .pipe(gulp.dest('public/css')));

// gulp.task('copyToPublic', ['copyCss', 'copySass', 'copyJs', 'copyWebfonts', 'compressBG']);

// gulp.task('watchSass', () => watchSass('sass/*.scss').pipe(sass({outputStyle:'compressed'})).pipe(gulp.dest('public/css')));