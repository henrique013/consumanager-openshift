var gulp = require('gulp')
    , less = require('gulp-less');


// LESS
gulp.task('less', function () {

    gulp.src(['public/**/*.less', '!public/**/*-not.less', '!public/bower/**/*.less'])
        .pipe(less())
        .pipe(gulp.dest('public'))
    ;
});