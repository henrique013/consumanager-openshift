var gulp = require('gulp')
    , less = require('gulp-less');


// LESS
gulp.task('less', function () {

    gulp.src(['public/templates/**/*.less', '!public/bower/**/*.less'])
        .pipe(less())
        .pipe(gulp.dest('public/templates'))
    ;
});