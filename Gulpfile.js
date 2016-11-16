var gulp = require('gulp');
var babelify = require('babelify');
var browserify = require('browserify');
var source = require('vinyl-source-stream');
var path = require('path');
var APP_DIR = path.resolve(__dirname, 'app/Resources/jsx/');
var BUILD_DIR = path.resolve(__dirname, 'web/js/');

gulp.task("jsxES6Item",function(){
  var x = browserify({entries: APP_DIR + '/itemApp.jsx', extensions: ['.jsx','.js'], debug: true})
  .transform(babelify.configure({presets: ["es2015","react"]}))
  .bundle().pipe(source(BUILD_DIR + '/itemApp.js')).pipe(gulp.dest('.'));
});

gulp.task("jsxES6Mail",function(){
  var x = browserify({entries: APP_DIR + '/mailApp.jsx', extensions: ['.jsx','.js'], debug: true})
  .transform(babelify.configure({presets: ["es2015","react"]}))
  .bundle().pipe(source(BUILD_DIR + '/mailApp.js')).pipe(gulp.dest('.'));
});

gulp.task("jsxES6Customer",function(){
    var x = browserify({entries: APP_DIR + '/customerApp.jsx', extensions: ['.jsx','.js'], debug: true})
    .transform(babelify.configure({presets: ["es2015","react"]}))
    .bundle().pipe(source(BUILD_DIR + '/customerApp.js')).pipe(gulp.dest('.'));
});

gulp.task("default", function() {
  gulp.task("defaultItem",["jsxES6Item"],function(){});
  gulp.task("defaultMail",["jsxES6Mail"],function(){});
  gulp.task("defaultCustomer",["jsxES6Customer"],function(){});
});

gulp.task("watch", ["default"], function () {
  gulp.watch(["app/Resources/jsx/itemApp.jsx"], ["defaultItem"]);
  gulp.watch(["app/Resources/jsx/mailApp.jsx"], ["defaultMail"]);
  gulp.watch(["app/Resources/jsx/customerApp.jsx"], ["defaultCustomer"]);
});

