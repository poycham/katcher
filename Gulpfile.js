var katcherNode = {
    gulp: require('gulp'),
    sass: require('gulp-sass'),
    sourcemaps: require('gulp-sourcemaps'),
    autoprefixer: require('gulp-autoprefixer')
};

var katcherSass = {
    input: './resources/sass/style.scss',
    output: './public/css',
    sourcemapPath: 'maps',
    options: {
        errLogToConsole: true,
        outputStyle: 'expanded'
    }
};

(function (katcherNode, katcherSass) {
    katcherNode.gulp.task('sass', function () {
        return katcherNode.gulp
            .src(katcherSass.input)
            .pipe(katcherNode.sourcemaps.init())
            .pipe(katcherNode.sass(katcherSass.options)).on('error', katcherNode.sass.logError)
            .pipe(katcherNode.autoprefixer())
            .pipe(katcherNode.sourcemaps.write(katcherSass.sourcemapPath))
            .pipe(katcherNode.gulp.dest(katcherSass.output));
    });
})(katcherNode, katcherSass);