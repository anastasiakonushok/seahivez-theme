import { cpSync, existsSync, mkdirSync, readFileSync, rmSync, writeFileSync } from 'node:fs';
import { dirname, join } from 'node:path';
import { fileURLToPath } from 'node:url';

const root = join( dirname( fileURLToPath( import.meta.url ) ), '..' );
const sourceRoot = join( root, 'node_modules', 'intl-tel-input' );
const targetRoot = join( root, 'assets', 'vendor', 'intl-tel-input' );
const vendorImgRoot = join( root, 'assets', 'vendor', 'img' );

if ( ! existsSync( sourceRoot ) ) {
	console.error( 'intl-tel-input package not found. Run npm install first.' );
	process.exit( 1 );
}

if ( existsSync( targetRoot ) ) {
	rmSync( targetRoot, { recursive: true, force: true } );
}

mkdirSync( targetRoot, { recursive: true } );

const cssSource = readFileSync( join( sourceRoot, 'build', 'css', 'intlTelInput.css' ), 'utf8' );
const cssTarget = cssSource.replaceAll( '../img/', 'img/' );

writeFileSync( join( targetRoot, 'intlTelInput.css' ), cssTarget );
cpSync(
	join( sourceRoot, 'build', 'js', 'utils.js' ),
	join( targetRoot, 'utils.js' )
);

if ( existsSync( vendorImgRoot ) ) {
	rmSync( vendorImgRoot, { recursive: true, force: true } );
}

cpSync(
	join( sourceRoot, 'build', 'img' ),
	join( targetRoot, 'img' ),
	{ recursive: true }
);

console.log( 'Copied intl-tel-input assets to assets/vendor/intl-tel-input' );
