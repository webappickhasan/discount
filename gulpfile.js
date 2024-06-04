const gulp = require('gulp');
const clean = require('gulp-clean');

const {
    src, dest, task, series, parallel, watch
} = gulp;

task('clean', function () {
	return gulp.src([
		'vendor/**/.github',
		'vendor/**/*.xml',
		'vendor/**/.gitattributes',
		'vendor/**/.editorconfig',
		'vendor/**/.gitignore',
		'vendor/**/.sh',
		'vendor/**/.xml.dist',
		'vendor/**/.dist',
		'vendor/**/.neon.dist',
		'vendor/**/tests',
		'vendor/micropackage/requirements/phpunit.xml.dist',
		"vendor/micropackage/requirements/bin/install-wp-tests.sh",
		"vendor/composer/installers/phpstan.neon.dist",
	], {read: false, allowEmpty: true})
		.pipe(clean());
});

