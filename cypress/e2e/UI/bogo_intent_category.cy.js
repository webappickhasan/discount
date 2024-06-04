describe( 'BOGO Discount Categories', () => {
	it( 'Create Discount', () => {
		cy.visit( '' );

		// Logging in with admin credentials
		cy.contains( 'Username or Email Address' ).should( 'be.visible' );
		cy.wait( 500 );
		cy.login( 'admin', 'admin' );
		cy.wait( 500 );

		// Navigating to the "Create Discount" page
		cy.contains( 'button', 'Create a Discount' ).should( 'be.visible' );
		cy.contains( 'button', 'Create a Discount' ).click();
		cy.wait( 500 );

		// Filling in the "Campaign Name" field
		cy.contains( 'Campaign Name' ).should( 'be.visible' );
		cy.get( 'input[placeholder="20% discount on all products"]' ).type(
			'BOGO Category Discount'
		);

		// Checking if the "BOGO" button is rendered correctly and clicking it
		cy.contains( 'button', 'BOGO' ).should( 'be.visible' ).click();

		// Switching the campaign method to "Coupon"
		cy.contains( 'Coupon Discount' ).click();
		cy.get( 'input[placeholder="COUPON25"]' ).focus().type( 'NEWYEAR24' );

		// Setting the discount user limit
		cy.get( 'input[placeholder="Unlimited"]' ).type( 500 );

		// Specifying the valid date range for the discount
		const startDate = '2023-11-10T10:31';
		const endDate = '2023-11-20T10:31';
		cy.get(
			'input[type="datetime-local"][name="discount_valid_from"]'
		).type( startDate );
		cy.get( 'input[type="datetime-local"][name="discount_valid_to"]' ).type(
			endDate
		);

		// Selecting the "Categories" option for the BOGO discount
		cy.contains( 'button span', 'All' ).should( 'be.visible' ).click();
		cy.contains( 'li span', 'Categories' ).should( 'be.visible' ).click();

		// Creating a BOGO discount rule
		cy.get( 'input[placeholder="Min"]' ).focus().type( 5 );
		cy.get( 'input[placeholder="Max"]' ).focus().type( 10 );

		// Searching for a category and selecting it
		cy.get( 'input[placeholder="Search Category"]' ).focus().type( 'Acc' );
		cy.contains( 'Accessories' ).should( 'be.visible' ).click();
		cy.get( 'input[placeholder="Search Category"]' ).clear();

		// Filling in other input fields for the discount rule
		cy.get( 'input[placeholder="Get Quantity"]' ).focus().type( 1 );
		cy.contains( 'button', '% - Percentage' )
			.should( 'be.visible' )
			.click();
		cy.contains( '$ - Fixed' ).should( 'be.visible' ).click();
		cy.get( 'input[placeholder="Value"]' ).focus().type( 50 );

		// Clicking the "Add More" button to add another rule and completing it
		cy.contains( 'button', 'Add More' ).should( 'be.visible' ).click();
		cy.get( 'input[placeholder="Min"]' ).eq( 1 ).focus().type( 11 );
		cy.get( 'input[placeholder="Max"]' ).eq( 1 ).focus().type( 15 );
		cy.get( 'input[placeholder="Search Category"]' )
			.eq( 1 )
			.focus()
			.type( 'Un' );
		cy.contains( 'Uncategorized' ).should( 'be.visible' ).click();
		cy.get( 'input[placeholder="Search Category"]' ).clear();
		cy.get( 'input[placeholder="Get Quantity"]' ).eq( 1 ).focus().type( 1 );
		cy.contains( 'button', '% - Percentage' )
			.should( 'be.visible' )
			.click();
		cy.contains( '$ - Fixed' ).should( 'be.visible' ).click();
		cy.get( 'input[placeholder="Value"]' ).eq( 1 ).focus().type( 70 );

		// Checking if the "Recursive" option is functioning correctly
		cy.get( 'label:contains(Recursive)' ).eq( 1 ).click();
		cy.get( 'input[placeholder="Min"]' ).should( 'have.length', 1 );
		cy.get( 'input[placeholder="Max"]' ).should( 'have.length', 1 );
		cy.get( 'input[placeholder="Item Quantity"]' ).should( 'exist' );

		// Saving the discount
		cy.contains( 'button', 'Save & Exit' ).click();

		// Checking if the newly created discount can be edited
		cy.get( 'tr td button:contains(Actions)' ).eq( 0 ).click();
		cy.contains( 'button', 'Edit' ).click();
		cy.get( 'input[placeholder="20% discount on all products"]' ).should(
			'have.value',
			'BOGO Category Discount'
		);

		// Checking if the valid data is saved correctly
		cy.get(
			'input[type="datetime-local"][name="discount_valid_from"]'
		).should( 'have.value', startDate );
		cy.get(
			'input[type="datetime-local"][name="discount_valid_to"]'
		).should( 'have.value', endDate );

		// Reloading and updating the discount name
		cy.reload();
		cy.get( 'input[placeholder="20% discount on all products"]' )
			.focus()
			.type( ' Updated' );
		cy.contains( 'button', 'Update' ).click();
		cy.contains( 'button', 'Updating' ).should( 'exist' );
		cy.contains( 'button', 'Update' ).should( 'exist' );

		// Reloading again and checking data persistence
		cy.reload();
		cy.get( 'input[placeholder="20% discount on all products"]' ).should(
			'have.value',
			'BOGO Category Discount Updated'
		);
		cy.get(
			'input[type="datetime-local"][name="discount_valid_from"]'
		).should( 'have.value', startDate );
		cy.get(
			'input[type="datetime-local"][name="discount_valid_to"]'
		).should( 'have.value', endDate );

		// Checking if the update works correctly
		cy.contains( 'Automated Discount' ).click();
		cy.contains( 'button', 'Update & Exit' ).click();
		cy.contains( 'BOGO Category Discount Updated' ).should( 'exist' );

		// Deleting the newly created discount
		cy.get( 'tr td button:contains(Actions)' ).eq( 0 ).click();
		cy.contains( 'button', 'Delete' ).click();
		cy.contains( 'button', 'Delete' ).click();

		// Checking if the campaign is successfully deleted
		cy.contains( 'BOGO Category Discount Updated' ).should( 'not.exist' );
	} );
} );
