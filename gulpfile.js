var gulp = require('gulp'),
    less = require('gulp-less'),
    minify = require('gulp-clean-css'),
    uglify = require('gulp-uglify'),
    rename = require('gulp-rename');

gulp.task('less', function () {
    return gulp.src('./assets/cookiePolicy.less')
        .pipe(less())
        .pipe(minify({keepSpecialComments: 0}))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('./assets/'));
});

gulp.task('js', function () {
    return gulp.src('./assets/cookiePolicy.js')
        .pipe(uglify())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('./assets/'));
});

gulp.task('watch', function () {
    gulp.watch('./assets/cookiePolicy.less', ['less']);
    gulp.watch('./assets/cookiePolicy.js', ['js']);
});

gulp.task('default', ['less', 'js', 'watch']);