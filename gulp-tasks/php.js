'use strict';

/* eslint-env node */

const gulp = require('gulp');

gulp.task('php', () => {
  return gulp.src(global.config.src + '/**/*.php')
  .pipe(gulp.dest(global.config.dest));
});