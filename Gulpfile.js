var gulp = require('gulp'), 
    sass = require('gulp-sass'),
    sourcemaps = require('gulp-sourcemaps'),
    autoprefixer = require('gulp-autoprefixer');

var sassSettings = {
    files: './resources/sass/**/*.scss',
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

gulp.task('sass', function () {
    return gulp
        .src(sassSettings.input)
        .pipe(sourcemaps.init())
        .pipe(sass(sassSettings.options)).on('error', sass.logError)
        .pipe(autoprefixer())
        .pipe(sourcemaps.write(sassSettings.sourcemapPath))
        .pipe(gulp.dest(sassSettings.output));
});

gulp.task('watch', function() {
    return gulp
        .watch(sassSettings.files, ['sass'])
        .on('change', function(event) {
            console.log('File ' + event.path + ' was ' + event.type + ', running tasks...');
        });
});

gulp.task('default', ['sass', 'watch']);

gulp.task('prod', function () {
    return gulp
        .src(sassSettings.input)
        .pipe(sass({
            outputStyle: 'compressed',
            includePaths: sassSettings.options.includePaths
        }))
        .pipe(autoprefixer())
        .pipe(gulp.dest(sassSettings.output));
});