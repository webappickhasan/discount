describe( 'Bulk Discount', () => {
	it( 'Create Discount', () => {
		cy.visit( '' );

		// login with username and password
		cy.contains( 'Username or Email Address' ).should( 'be.visible' );
		cy.wait( 500 );
		cy.login( 'admin', 'admin' );
		cy.wait( 500 );

		// navigate to crate discount page to crate
		cy.contains( 'button', 'Create a Discount' ).should( 'be.visible' );
		cy.contains( 'button', 'Create a Discount' ).click();
		cy.wait( 500 );

		// campaign name filed exist and type campaign name
		cy.contains( 'Campaign Name' ).should( 'be.visible' );
		cy.get( 'input[placeholder="20% discount on all products"]' ).type(
			'Bulk Discount'
		);

		// checking free shipping intent button render correctly
		cy.contains( 'button', 'Bulk Discount' ).should( 'be.visible' ).click();

		// campaign method test and change to coupon
		cy.contains( 'Coupon Discount' ).click();
		cy.get( 'input[placeholder="COUPON25"]' ).focus().type( 'BULK5' );

		// set discount user limit
		cy.get( 'input[placeholder="Unlimited"]' ).type( 500 );

		// type discount valid between
		cy.get(
			'input[type="datetime-local"][name="discount_valid_from"]'
		).type( '2023-11-03T10:31' );
		cy.get( 'input[type="datetime-local"][name="discount_valid_to"]' ).type(
			'2023-11-10T10:31'
		);

		// setting bulk rules base, type, value, label
		cy.contains( 'Bulk Rules' ).should( 'exist' );

		cy.get( 'input[placeholder="Min"]' ).focus().type( 500 );
		cy.get( 'input[placeholder="Max"]' ).focus().type( 1000 );
		cy.contains( 'button', '% - Percentage' )
			.should( 'be.visible' )
			.click();
		cy.contains( '$ - Fixed' ).should( 'be.visible' ).click();
		cy.get( 'input[placeholder="Value"]' ).focus().type( 50 );
		cy.get( 'input[placeholder="Discount Label"]' )
			.focus()
			.type( 'Buy More Save More' );

		// click add more and create another bulk rule
		cy.contains( 'button', 'Add More' ).should( 'be.visible' ).click();

		cy.get( 'input[placeholder="Min"]' ).eq( 1 ).focus().type( 1001 );
		cy.get( 'input[placeholder="Max"]' ).eq( 1 ).focus().type( 2000 );
		cy.contains( 'button', '% - Percentage' )
			.should( 'be.visible' )
			.click();
		cy.contains( 'li', '$ - Fixed' ).should( 'be.visible' ).click();
		cy.get( 'input[placeholder="Value"]' ).eq( 1 ).focus().type( 70 );
		cy.get( 'input[placeholder="Discount Label"]' )
			.eq( 1 )
			.focus()
			.type( 'Buy More Save More' );

		// save discount and navigate to campaign list page
		cy.contains( 'button', 'Save & Exit' ).click();

		// check new created discount edit
		cy.get( 'tr td button:contains(Actions)' ).eq( 0 ).click();
		cy.contains( 'button', 'Edit' ).click();
		cy.get( 'input[placeholder="20% discount on all products"]' ).should(
			'have.value',
			'Bulk Discount'
		);

		// check data persistent and reload then update data
		cy.reload();
		cy.get( 'input[placeholder="20% discount on all products"]' )
			.focus()
			.type( ' Updated' );
		cy.contains( 'button', 'Update' ).click();
		cy.contains( 'button', 'Updating' ).should( 'exist' );
		cy.contains( 'button', 'Update' ).should( 'exist' );

		// check data persistent after reload
		cy.reload();
		cy.get( 'input[placeholder="20% discount on all products"]' ).should(
			'have.value',
			'Bulk Discount Updated'
		);

		// check update work correctly
		cy.contains( 'Automated Discount' ).click();
		cy.contains( 'button', 'Update & Exit' ).click();
		cy.contains( 'Bulk Discount Updated' ).should( 'exist' );

		// delete new created discount
		cy.get( 'tr td button:contains(Actions)' ).eq( 0 ).click();
		cy.contains( 'button', 'Delete' ).click();
		cy.contains( 'button', 'Delete' ).click();

		// check campaign successfully deleted
		cy.contains( 'Bulk Discount Updated' ).should( 'not.exist' );
	} );
} );
