const { defineConfig } = require( 'cypress' );

module.exports = defineConfig( {
	e2e: {
		baseUrl:
			'http://localhost/quasar/wp-admin/admin.php?page=create-discount',
	},
	downloadsFolder: 'cypress/downloads',
	viewportWidth: 1440,
	viewportHeight: 960,
	screenshotOnRunFailure: false,
} );
