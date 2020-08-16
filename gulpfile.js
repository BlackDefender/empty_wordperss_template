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
const gulpif = require('gulp-if');

const enableSourceMaps = ['--sourcemaps', '-s', '--development', '-dev', '-d'].some(item => process.argv.includes(item));
const isProductionMode = process.env.NODE_ENV && process.env.NODE_ENV.trim() === 'production';

const needToConvert = file => !file._base.includes('vendor') && isProductionMode;
const needToUglify = file => !file.history[0].includes('.min.js') && isProductionMode;

//const browserSync = require('browser-sync').create();
//const reload = browserSync.reload;

//const server = () => {
//    browserSync.init({
//        proxy: '10.101.103.15/wordpress_cms/zelinski.ua',
//        files: '**/*.php'
//    });
//};

const styles = () => {
    return src('scss/*.scss')
        .pipe(bulkSass())
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(gulpif(isProductionMode, autoprefixer()))
        .pipe(gulpResolveUrl())
        .pipe(gulpif(isProductionMode, cssmin()))
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
        .pipe(gulpif(needToConvert, babel({ presets: ['@babel/env'] })))
        .pipe(gulpif(needToUglify, uglify()))
        .pipe(dest('js/min', { sourcemaps: enableSourceMaps }))
};

const jsPages = () => {
    return src('js/src/pages/*.js', { sourcemaps: enableSourceMaps })
        .pipe(gulpif(isProductionMode, babel({ presets: ['@babel/env'] })))
        .pipe(gulpif(isProductionMode, uglify()))
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
//    server();
};

task(styles);
task(jsCommon);
task(jsPages);
task('build', buildTask);
task('watch', watchTask);
task('default', defaultTask);
