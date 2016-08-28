var gulp = require('gulp')
    , less = require('gulp-less');


// LESS
gulp.task('less', function () {

    gulp.src(['templates/**/*.less', '!templates/**/*-not.less'])
        .pipe(less())
        .pipe(gulp.dest('public/templates'))
    ;
});