var gulp = require('gulp'),
        less = require('gulp-less'),
        sass = require('gulp-sass'),
        minify = require('gulp-clean-css'),
        concat = require('gulp-concat'),
        uglify = require('gulp-uglify'),
        rename = require('gulp-rename');

var paths = {
    'dev': {
        'less': './resources/assets/js/',
        'vendor': './resources/assets/vendor/'
    },
    'production': {
        'css': './assets/'
    }
};

var files = [
    paths.dev.js + 'MD5Hasher.js',
    paths.dev.js + 'RemoveDiacritics.js',
    paths.dev.js + 'jQuery.js',
    paths.dev.js + 'String.js',
    paths.dev.js + 'Number.js'
];

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