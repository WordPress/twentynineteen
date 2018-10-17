// Imports
const gulp = require( 'gulp' ),
    banner = require( 'gulp-banner' ),
    sass = require( 'gulp-sass' ),
    autoprefixer = require( 'gulp-autoprefixer' ),
    rename = require( 'gulp-rename' ),
    log = require( 'fancy-log' );

// These paths should alawys be ignored when watching files
const alwaysIgnoredPaths = [ '!node_modules/**' ];

function doSass( done ) {
    if ( arguments.length && typeof arguments[ 0 ] !== 'function' ) {
        log( 'Sass file ' + arguments[ 0 ].path + ' changed.' );
    }
    log( 'Building CSS bundle...' );
    gulp.src( 'style.scss' )
        .pipe( sass( { outputStyle: 'expanded' } ).on( 'error', sass.logError ) )
        .pipe( autoprefixer( { browsers: [ 'last 2 versions', 'ie >= 8' ] } ) )
        .pipe( banner( '/* Do not modify this file directly. It is compiled from other files. */\n' ) )
        .pipe( rename( 'style.css' ) )
        .pipe( gulp.dest( './' ) )
        .on( 'end', function() {
            log( 'SCSS finished.' );
        } );
}

gulp.task( 'sass', doSass );

gulp.task( 'sass:watch', function() {
    doSass();
    gulp.watch( [ './**/*.scss', ...alwaysIgnoredPaths ], doSass );
} );