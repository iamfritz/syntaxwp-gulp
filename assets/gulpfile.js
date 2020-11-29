const gulp = require('gulp');
const sass = require('gulp-sass');
const del = require('del');
const minify = require("gulp-minify");
const scsslint = require('gulp-scss-lint');
const autoprefixer = require('gulp-autoprefixer');
const cleancss = require('gulp-clean-css');

const themePrefix = 'mad';

/**
 * Asset paths.
 */
const scssSrc = 'scss/**/*.scss';
const SassToCssLocation = './css/';

gulp.task('css', () => {
    return gulp.src(scssSrc)
        .pipe(sass().on('error', sass.logError))
        .pipe(autoprefixer({ cascade: false }))
        //.pipe(rename(`${themePrefix}.min.css`))
        .pipe(cleancss())
        .pipe(gulp.dest(SassToCssLocation));
});

gulp.task('js', function() {
    return gulp.src('scripts/*.js')
        .pipe(minify({
            ext: {
                min: '.min.js'
            },
            ignoreFiles: ['-min.js']
        }))
        .pipe(gulp.dest('js'))
});

gulp.task('clean', () => {
    return del([
        'css/app.css',
        'js/app.js',
    ]);
});

gulp.task('watch', () => {
    gulp.watch('sass/**/*.scss', (done) => {
        gulp.series(['clean', 'css', 'js'])(done);
    });
});

gulp.task('default', gulp.series(['clean', 'css', 'js']));