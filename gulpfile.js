var gulp = require('gulp'),
        less = require('gulp-less'),
        minify = require('gulp-clean-css'),
        rename = require('gulp-rename');

gulp.task('less', function () {
    return gulp.src('./assets/cookiePolicy.less')
            .pipe(less())
            .pipe(minify({keepSpecialComments: 0}))
            .pipe(rename({suffix: '.min'}))
            .pipe(gulp.dest('./assets/'));
});

gulp.task('watch', function () {
    gulp.watch('./assets/cookiePolicy.less', ['less']);
});

gulp.task('default', ['less', 'watch']); 