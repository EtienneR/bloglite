var gulp = require('gulp'),
    php  = require('gulp-connect-php');

gulp.task('serve', function() {

  php.server({
      port: 80
  });

});


gulp.task('default', ['serve']);