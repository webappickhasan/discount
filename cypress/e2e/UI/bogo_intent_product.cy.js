describe( 'BOGO Discount Products', () => {
	it( 'Create Discount', () => {
		cy.visit( '' ); // Navigating to the plugins's root URL

		// Logging in with admin credentials
		cy.contains( 'Username or Email Address' ).should( 'be.visible' );
		cy.wait( 500 );
		cy.login( 'admin', 'admin' );
		cy.wait( 200 );

		// Navigate to the "Create Discount" page
		cy.contains( 'button', 'Create a Discount' ).should( 'be.visible' );
		cy.contains( 'button', 'Create a Discount' ).click();
		cy.wait( 500 );

		// Verify that the "Campaign Name" field is visible and set the campaign name
		cy.contains( 'Campaign Name' ).should( 'be.visible' );
		cy.get( 'input[placeholder="20% discount on all products"]' ).type(
			'BOGO Products Discount'
		);

		// Check if the "BOGO" button is displayed correctly and click it
		cy.contains( 'button', 'BOGO' ).should( 'be.visible' ).click();

		// Switch campaign method to "Coupon"
		cy.contains( 'Coupon Discount' ).click();
		cy.get( 'input[placeholder="COUPON25"]' ).focus().type( 'NEWYEAR24' );

		// Set the discount user limit
		cy.get( 'input[placeholder="Unlimited"]' ).type( 500 );

		// Specify the valid date range for the discount
		cy.get(
			'input[type="datetime-local"][name="discount_valid_from"]'
		).type( '2023-11-10T10:31' );
		cy.get( 'input[type="datetime-local"][name="discount_valid_to"]' ).type(
			'2023-11-20T10:31'
		);

		// Check if the "BOGO" card exists and change the BOGO type to "Products"
		cy.contains( 'BOGO' ).should( 'exist' );
		cy.contains( 'button span', 'All' ).should( 'be.visible' ).click();
		cy.contains( 'li span', 'Products' ).should( 'be.visible' ).click();

		// Create a product-based BOGO rule
		cy.get( 'input[placeholder="Min"]' ).focus().type( 5 );
		cy.get( 'input[placeholder="Max"]' ).focus().type( 10 );

		// Search for a product and select it
		cy.get( 'input[placeholder="Search Product"]' ).focus().type( 'Bea' );
		cy.contains( 'Beanie' ).should( 'be.visible' ).click();
		cy.get( 'input[placeholder="Search Product"]' ).clear();

		// Fill in other input fields for the discount rule
		cy.get( 'input[placeholder="Get Quantity"]' ).focus().type( 1 );
		cy.contains( 'button', '% - Percentage' )
			.should( 'be.visible' )
			.click();
		cy.contains( '$ - Fixed' ).should( 'be.visible' ).click();
		cy.get( 'input[placeholder="Value"]' ).focus().type( 50 );

		// Click "Add More" to create another product-based BOGO rule
		cy.contains( 'button', 'Add More' ).should( 'be.visible' ).click();
		cy.get( 'input[placeholder="Min"]' ).eq( 1 ).focus().type( 11 );
		cy.get( 'input[placeholder="Max"]' ).eq( 1 ).focus().type( 15 );
		cy.get( 'input[placeholder="Search Product"]' )
			.eq( 1 )
			.focus()
			.type( 'Bel' );
		cy.contains( 'Belt' ).should( 'be.visible' ).click();
		cy.get( 'input[placeholder="Search Product"]' ).clear();
		cy.get( 'input[placeholder="Get Quantity"]' ).eq( 1 ).focus().type( 1 );
		cy.contains( 'button', '% - Percentage' )
			.should( 'be.visible' )
			.click();
		cy.contains( '$ - Fixed' ).should( 'be.visible' ).click();
		cy.get( 'input[placeholder="Value"]' ).eq( 1 ).focus().type( 70 );

		// Ensure the "Recursive" option works correctly
		cy.get( 'label:contains(Recursive)' ).eq( 1 ).click();
		cy.get( 'input[placeholder="Min"]' ).should( 'have.length', 1 );
		cy.get( 'input[placeholder="Max"]' ).should( 'have.length', 1 );
		cy.get( 'input[placeholder="Item Quantity"]' ).should( 'exist' );

		// Save the discount and navigate to the campaign list page
		cy.contains( 'button', 'Save & Exit' ).click();

		// Check if the newly created discount can be edited, and the data is persistent
		cy.get( 'tr td button:contains(Actions)' ).eq( 0 ).click();
		cy.contains( 'button', 'Edit' ).click();
		cy.get( 'input[placeholder="20% discount on all products"]' ).should(
			'have.value',
			'BOGO Products Discount'
		);

		// Update the discount name and check data persistence after reloading
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
			'BOGO Products Discount Updated'
		);

		// Check if the update works correctly
		cy.contains( 'Automated Discount' ).click();
		cy.contains( 'button', 'Update & Exit' ).click();
		cy.contains( 'BOGO Products Discount Updated' ).should( 'exist' );

		// Delete the newly created discount
		cy.get( 'tr td button:contains(Actions)' ).eq( 0 ).click();
		cy.contains( 'button', 'Delete' ).click();
		cy.contains( 'button', 'Delete' ).click();

		// Check if the campaign is successfully deleted
		cy.contains( 'BOGO Products Discount Updated' ).should( 'not.exist' );
	} );
} );
