describe( 'Free Shipping', () => {
	it( 'Create Discount', () => {
		cy.visit( '' ); // Navigating to the plugins's root URL

		// Logging in with admin credentials
		cy.contains( 'Username or Email Address' ).should( 'be.visible' );
		cy.login( 'admin', 'admin' );
		cy.wait( 2000 );

		// Navigating to the "Create Discount" page
		cy.contains( 'button', 'Create a Discount' ).should( 'be.visible' );
		cy.contains( 'button', 'Create a Discount' ).click();
		cy.wait( 2000 );

		// Filling in the "Campaign Name" field
		cy.contains( 'Campaign Name' ).should( 'be.visible' );
		cy.get( 'input[placeholder="20% discount on all products"]' ).type(
			'Free Shipping Offer'
		);

		// Checking if the "Free Shipping" button is rendered correctly
		cy.contains( 'button', 'Free Shipping' ).should( 'be.visible' ).click();

		// Switching the campaign method to "Coupon"
		cy.contains( 'Coupon Discount' ).click();
		cy.get( 'input[placeholder="COUPON25"]' ).focus().type( 'FREE25' );

		// Setting the discount user limit to 500
		cy.get( 'input[placeholder="Unlimited"]' ).type( 500 );

		// Specifying the valid date range for the discount
		cy.get(
			'input[type="datetime-local"][name="discount_valid_from"]'
		).type( '2023-11-03T10:31' );
		cy.get( 'input[type="datetime-local"][name="discount_valid_to"]' ).type(
			'2023-11-10T10:31'
		);

		// Verifying that the "Product Rules" section does not exist
		cy.contains( 'Product Rules' ).should( 'not.exist' );

		// Saving the discount
		cy.contains( 'button', 'Save & Exit' ).click();

		// Checking if the newly created discount can be edited
		cy.get( 'tr td button:contains(Actions)' ).eq( 0 ).click();
		cy.contains( 'button', 'Edit' ).click();

		// Verifying that the Campaign Name input field contains the correct value
		cy.get( 'input[placeholder="20% discount on all products"]' ).should(
			'have.value',
			'Free Shipping Offer'
		);

		// Checking if data persists after reloading the page
		cy.reload();
		cy.get( 'input[placeholder="20% discount on all products"]' )
			.focus()
			.type( ' Updated' );
		cy.contains( 'button', 'Update' ).click();
		cy.contains( 'button', 'Updating' ).should( 'exist' );
		cy.contains( 'button', 'Update' ).should( 'exist' );

		// Checking if data updated and persists after reloading
		cy.reload();
		cy.get( 'input[placeholder="20% discount on all products"]' ).should(
			'have.value',
			'Free Shipping Offer Updated'
		);

		// Checking if the update works correctly
		cy.contains( 'Automated Discount' ).click();
		cy.contains( 'button', 'Update & Exit' ).click();
		cy.contains( 'Free Shipping Offer Updated' ).should( 'exist' );

		// Deleting the newly created discount
		cy.get( 'tr td button:contains(Actions)' ).eq( 0 ).click();
		cy.contains( 'button', 'Delete' ).click();
		cy.contains( 'button', 'Delete' ).click();

		// Verifying that the deleted discount no longer exists
		cy.contains( 'Free Shipping Offer Updated' ).should( 'not.exist' );
	} );
} );
