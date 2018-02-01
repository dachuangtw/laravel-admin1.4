var gulp = require('gulp');

// Copy vendor files from /node_modules into /public/themes/sales/vendor
var path = 'public/themes/sales/vendor/';
gulp.task('copy', function() {
	gulp.src(['node_modules/animsition/dist/**/*'])
	.pipe(gulp.dest(path+'animsition'))

	gulp.src(['node_modules/bootstrap/dist/css/*'])
	.pipe(gulp.dest(path+'bootstrap/css'))

	gulp.src([
		'node_modules/bootstrap/dist/js/bootstrap.js',
		'node_modules/bootstrap/dist/js/bootstrap.min.js'
	])
	.pipe(gulp.dest(path+'bootstrap/js'))

	gulp.src([
		'node_modules/daterangepicker/daterangepicker.css',
		'node_modules/daterangepicker/daterangepicker.js',
		'node_modules/daterangepicker/moment.*'
	])
	.pipe(gulp.dest(path+'daterangepicker'))

	gulp.src(['node_modules/jqueryui/**/*'])
	.pipe(gulp.dest(path+'jqueryui'))

	gulp.src(['node_modules/lightbox2/dist/**/*'])
	.pipe(gulp.dest(path+'lightbox2'))

	gulp.src([
		'node_modules/perfect-scrollbar/css/*',
		'node_modules/perfect-scrollbar/dist/perfect-scrollbar.min.js'
	])
	.pipe(gulp.dest(path+'perfect-scrollbar'))

	gulp.src([
		'node_modules/select2/dist/css/*',
		'node_modules/select2/dist/js/select2.js',
		'node_modules/select2/dist/js/select2.min.js'
	])
	.pipe(gulp.dest(path+'select2'))

	gulp.src(['node_modules/sweetalert/dist/*'])
	.pipe(gulp.dest(path+'sweetalert'))
});

gulp.task('default', ['copy']);
