const {src, dest, parallel, task, watch} = require('gulp');

const sourcemaps = require('gulp-sourcemaps');
const sass = require('gulp-sass');
const autoprefixer = require('gulp-autoprefixer');
const cssmin = require('gulp-cssmin');
const gulpResolveUrl = require('gulp-resolve-url');

const concat = require('gulp-concat');
const babel = require('gulp-babel');
const uglify = require('gulp-uglify');

const bulkSass = require('gulp-sass-bulk-import');

const enableSourceMaps = ['--development', '-dev', '-d'].some(item => process.argv.includes(item));

const styles = () => {
    return src('scss/*.scss')
        .pipe(bulkSass())
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(autoprefixer({
            browsers: ['last 2 versions']
        }))
        .pipe(gulpResolveUrl())
        .pipe(cssmin())
        .pipe(dest('./css/'));
};

const jsCommon = () => {
    return src([
        'js/src/vendor/jquery.min.js',
        //'js/src/vendor/jquery.form.min.js',
        //'js/src/vendor/jquery.maskedinput.min.js',
        'js/src/common/lib.js',
        'js/src/common/config.js',
        'js/src/common/common.js',
        'js/src/common/modals.js',

    ], { sourcemaps: true })
        .pipe(concat('bundle.js'))
        .pipe(babel({
            presets: ['@babel/env']
        }))
        .pipe(uglify())
        .pipe(dest('js/min', { sourcemaps: enableSourceMaps }))
};

const jsPages = () => {
    return src('js/src/pages/*.js', { sourcemaps: enableSourceMaps })
        .pipe(babel({
            presets: ['@babel/env']
        }))
        .pipe(uglify())
        .pipe(dest('js/min', { sourcemaps: enableSourceMaps }))
};

const buildTask = parallel(styles, jsCommon, jsPages);

const watchTask = () => {
    watch('scss/**/*.scss', styles);
    watch('js/src/common/*.js', jsCommon);
    watch('js/src/pages/*.js', jsPages);
};

const defaultTask = () => {
    buildTask();
    watchTask();
};

task(styles);
task(jsCommon);
task(jsPages);
task('build', buildTask);
task('watch', watchTask);
task('default', defaultTask);
