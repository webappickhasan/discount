describe( 'Discount Campaigns Page', () => {
	it( 'Work Correctly', () => {
		cy.visit( '' );

		// login with username and password
		cy.contains( 'Username or Email Address' ).should( 'be.visible' );
		cy.login( 'admin', 'admin' );
		cy.wait( 500 );

		// creating a discount for test
		cy.contains( 'button', 'Create a Discount' ).click();
		cy.wait( 500 );
		cy.get( 'input[placeholder="20% discount on all products"]' ).type(
			'Cypress Discount'
		);
		cy.get( 'input[placeholder="Value"]' ).type( '25' );
		cy.get( 'input[placeholder="Discount Label"]' ).type(
			'Cypress Discount Label'
		);
		cy.contains( 'button', 'Save & Exit' ).click();

		// checking campaign created successfully
		cy.contains( 'Cypress Discount' ).should( 'be.visible' );

		// testing search functionality
		cy.get( 'input[placeholder="Search Campaign"]' )
			.focus()
			.type( '30% Off' );
		cy.wait( 500 );
		cy.contains( 'button', 'Search' ).click();
		cy.wait( 500 );
		cy.contains( 'Cypress Discount' ).should( 'not.exist' );
		cy.get( 'input[placeholder="Search Campaign"]' ).clear();
		cy.wait( 500 );
		cy.contains( /^Cypress Discount$/ ).should( 'exist' );

		// testing campaign editing
		cy.get( 'tr td button' ).eq( 2 ).click();
		cy.wait( 500 );
		cy.contains( 'button', 'Edit' ).click();
		cy.contains( 'Campaign Name' ).should( 'exist' );
		cy.get( 'input[placeholder="20% discount on all products"]' ).should(
			'have.value',
			'Cypress Discount'
		);
		cy.wait( 500 );
		cy.contains( 'button', 'Cancel' ).click();

		// testing campaign duplicating
		cy.get( 'tr td button' ).eq( 2 ).click();
		cy.contains( 'button', 'Duplicate' ).click();
		cy.wait( 500 );
		cy.contains( /^Cypress Discount - Copy$/ ).should( 'exist' );
		cy.get( 'tr td button' ).eq( 2 ).click();
		cy.contains( 'button', 'Duplicate' ).click();
		cy.wait( 500 );
		cy.contains( /^Cypress Discount - Copy - Copy$/ ).should( 'exist' );
		cy.get( 'tr td button' ).eq( 8 ).click();
		cy.wait( 500 );

		// testing campaign delete
		cy.contains( 'button', 'Delete' ).click();
		cy.wait( 500 );
		cy.contains( 'button', 'Delete' ).click();
		cy.wait( 500 );
		cy.contains( /^Cypress Discount$/ ).should( 'not.exist' );

		// testing multi select
		cy.get( 'tr td button' ).eq( 0 ).click();
		cy.get( 'tr td button' ).eq( 3 ).click();

		// testing bulk disable
		cy.contains( 'Bulk Actions' ).click();
		cy.wait( 500 );
		cy.contains( 'Disable' ).click();
		cy.contains( 'Apply' ).click();
		cy.wait( 500 );
		cy.get( 'tr td button' )
			.eq( 1 )
			.should( 'have.attr', 'aria-checked', 'false' );
		cy.get( 'tr td button' )
			.eq( 4 )
			.should( 'have.attr', 'aria-checked', 'false' );
		cy.wait( 500 );

		// testing bulk enable
		cy.contains( 'Bulk Actions' ).click();
		cy.wait( 500 );
		cy.contains( 'Enable' ).click();
		cy.contains( 'Apply' ).click();
		cy.wait( 500 );
		cy.get( 'tr td button' )
			.eq( 1 )
			.should( 'have.attr', 'aria-checked', 'true' );
		cy.get( 'tr td button' )
			.eq( 4 )
			.should( 'have.attr', 'aria-checked', 'true' );
		cy.wait( 500 );

		// testing bulk delete
		cy.contains( 'Bulk Actions' ).click();
		cy.wait( 500 );
		cy.contains( 'Delete' ).click();
		cy.contains( 'Apply' ).click();
		cy.wait( 500 );
		cy.contains( /^Cypress Discount - Copy$/ ).should( 'not.exist' );
		cy.contains( /^Cypress Discount - Copy - Copy$/ ).should( 'not.exist' );
	} );
} );
