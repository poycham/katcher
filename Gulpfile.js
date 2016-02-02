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
        outputStyle: 'expanded',
        includePaths: [
            './node_modules/bootstrap-sass/assets/stylesheets'
        ]
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

    katcherNode.gulp.task('watch', function() {
        return katcherNode.gulp
            .watch(katcherSass.input, ['sass'])
            .on('change', function(event) {
                console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
            });
    });

    katcherNode.gulp.task('default', ['sass', 'watch']);

    katcherNode.gulp.task('prod', function () {
        return katcherNode.gulp
            .src(katcherSass.input)
            .pipe(katcherNode.sass({ outputStyle: 'compressed' }))
            .pipe(katcherNode.autoprefixer())
            .pipe(katcherNode.gulp.dest(katcherSass.output));
    });
})(katcherNode, katcherSass);