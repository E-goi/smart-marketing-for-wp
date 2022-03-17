var gulp         = require( 'gulp' );
var sass         = require( 'gulp-sass' );
var cleancss     = require( 'gulp-clean-css' );
var csscomb      = require( 'gulp-csscomb' );
var rename       = require( 'gulp-rename' );
var autoprefixer = require( 'gulp-autoprefixer' );
var pug          = require( 'gulp-pug' );

var paths = {
	style_source: './admin/spectre/src/*.scss',
	script_source: './admin/js/*.js'
};

gulp.task(
	'watch',
	function() {
		gulp.watch( './**/*.scss', ['build'] );
		gulp.watch( './**/*.scss', ['docs'] );
		gulp.watch( './**/*.pug', ['docs'] );
	}
);

gulp.task(
	'build',
	function() {
		gulp.src( paths.style_source )
		.pipe(
			sass( {outputStyle: 'compact', precision: 10} )
			.on( 'error', sass.logError )
		)
		.pipe( autoprefixer() )
		.pipe( csscomb() )
		.pipe( gulp.dest( './admin/spectre/dist' ) )
		.pipe( gulp.dest( './admin/css' ) )
		.pipe( cleancss() )
		.pipe(
			rename(
				{
					suffix: '.min'
				}
			)
		)
		.pipe( gulp.dest( './admin/spectre/dist' ) )
		.pipe( gulp.dest( './admin/css' ) );
	}
);

gulp.task( 'default', ['build'] );
