describe( 'Product Intent ', () => {
	it( 'Discount Create', () => {
		cy.visit( '' );

		// login with username and password
		cy.contains( 'Username or Email Address' ).should( 'be.visible' );
		cy.login( 'admin', 'admin' );
		cy.wait( 500 );

		// navigate to crate discount page to crate
		cy.contains( 'button', 'Create a Discount' ).should( 'be.visible' );
		cy.contains( 'button', 'Create a Discount' ).click();
		cy.wait( 500 );

		// campaign name filed exist and type campaign name
		cy.contains( 'Campaign Name' ).should( 'be.visible' );
		cy.get( 'input[placeholder="20% discount on all products"]' ).type(
			'Product Based Discount'
		);
		cy.contains( 'button', 'Product' ).should( 'be.visible' );

		// campaign method test and change to coupon
		cy.contains( 'Coupon Discount' ).click();
		cy.get( 'input[placeholder="COUPON25"]' ).focus().type( 'NEWYEAR' );

		// click few product button for select product
		cy.contains( 'button', 'Few Products' ).should( 'be.visible' );
		cy.contains( 'button', 'Few Products' ).click();

		// search for product
		cy.get( 'input[placeholder="Search Product"]' ).type( 'Be' );
		cy.wait( 500 );

		// select products from search result
		cy.contains( 'Beanie' ).should( 'be.visible' ).click();
		cy.contains( 'Belt' ).should( 'be.visible' ).click();
		cy.contains( 'Cap' ).should( 'be.visible' ).click();
		cy.wait( 500 );
		cy.contains( 'Cap' ).should( 'be.visible' ).click();

		// after select product clear search field
		cy.get( 'input[placeholder="Search Product"]' ).clear();
		cy.contains( 'Beanie' ).should( 'be.visible' );
		cy.contains( 'Belt' ).should( 'be.visible' );
		cy.contains( 'Cap' ).should( 'not.exist' );

		// set discount user limit
		cy.get( 'input[placeholder="Unlimited"]' ).type( 500 );

		// type discount valid between

		cy.get(
			'input[type="datetime-local"][name="discount_valid_from"]'
		).type( '2023-11-03T10:31' );
		cy.get( 'input[type="datetime-local"][name="discount_valid_to"]' ).type(
			'2023-11-10T10:31'
		);
		cy.contains( 'Product Rules' ).should( 'exist' );

		// change discount type percent to fixed
		cy.contains( '% - Percentage' ).should( 'exist' ).click();
		cy.contains( '$ - Fixed' ).should( 'exist' ).click();

		// set discount value and label
		cy.get( 'input[placeholder="Value"]' ).focus().type( 50 );
		cy.get( 'input[placeholder="Discount Label"]' )
			.focus()
			.type( 'Product Based Discount' );
		cy.contains( 'h2', 'Conditions' ).should( 'exist' );

		// create a condition and populate values
		cy.contains( 'button', 'Add Condition' ).should( 'exist' ).click();
		cy.contains( 'Select Filter' ).should( 'exist' ).click();
		cy.contains( 'ID' ).should( 'exist' ).click();
		cy.contains( 'Equal' ).should( 'exist' ).click();
		cy.contains( 'Greater Than' ).should( 'exist' ).click();
		cy.get( 'input[placeholder="Value"]' ).eq( 1 ).focus().type( 405 );

		// create another condition and populate values
		cy.contains( 'button', 'Add Another Condition' )
			.should( 'exist' )
			.click();
		cy.contains( 'Select Filter' ).should( 'exist' ).click();
		cy.contains( 'Title' ).should( 'exist' ).click();
		cy.contains( 'Equal' ).should( 'exist' ).click();
		cy.contains( 'Contain' ).should( 'exist' ).click();
		cy.get( 'input[placeholder="Value"]' ).eq( 2 ).focus().type( 'Hoodie' );

		// create another condition group and populate values
		cy.get( 'button:contains(Add Condition)' ).eq( 1 ).click();
		cy.get( 'button:contains(Add Another Condition)' ).should(
			'have.length',
			2
		);
		cy.contains( 'Select Filter' ).should( 'exist' ).click();
		cy.contains( 'Description' ).should( 'exist' ).click();
		cy.contains( 'Equal' ).should( 'exist' ).click();
		cy.contains( 'Does Not Contain' ).should( 'exist' ).click();
		cy.get( 'input[placeholder="Value"]' ).eq( 3 ).focus().type( 'Shirt' );

		// save discount
		cy.contains( 'button', 'Save & Exit' ).click();

		// check new created discount edit
		cy.get( 'tr td button:contains(Actions)' ).eq( 0 ).click();
		cy.contains( 'button', 'Edit' ).click();
		cy.get( 'input[placeholder="20% discount on all products"]' ).should(
			'have.value',
			'Product Based Discount'
		);
		cy.get( 'button:contains(Add Another Condition)' ).should(
			'have.length',
			2
		);

		// check data persistent after reload
		cy.reload();
		cy.get( 'input[placeholder="20% discount on all products"]' )
			.focus()
			.type( ' Updated' );
		cy.contains( 'button', 'Update' ).click();
		cy.contains( 'button', 'Updating' ).should( 'exist' );
		cy.contains( 'button', 'Update' ).should( 'exist' );
		cy.reload();
		cy.get( 'input[placeholder="20% discount on all products"]' ).should(
			'have.value',
			'Product Based Discount Updated'
		);

		// check update work correctly
		cy.contains( 'Automated Discount' ).click();
		cy.contains( 'button', 'Update & Exit' ).click();
		cy.contains( 'Product Based Discount Updated' ).should( 'exist' );

		// delete new created discount
		cy.get( 'tr td button:contains(Actions)' ).eq( 0 ).click();
		cy.contains( 'button', 'Delete' ).click();
		cy.contains( 'button', 'Delete' ).click();
		cy.contains( 'Product Based Discount Updated' ).should( 'not.exist' );
	} );
} );
