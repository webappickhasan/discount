describe( 'Cart Intent ', () => {
	it( 'Discount Create', () => {
		cy.visit( '' );

		// login with username and password
		cy.contains( 'Username or Email Address' ).should( 'be.visible' );
		cy.wait( 500 );
		cy.login( 'admin', 'admin' );

		// navigate to crate discount page to crate
		cy.contains( 'button', 'Create a Discount' ).should( 'be.visible' );
		cy.contains( 'button', 'Create a Discount' ).click();

		// campaign name filed exist and type campaign name
		cy.contains( 'Campaign Name' ).should( 'be.visible' );
		cy.get( 'input[placeholder="20% discount on all products"]' ).type(
			'Cart Based Discount'
		);

		// checking cart intent button render correctly
		cy.contains( 'button', 'Cart' ).should( 'be.visible' ).click();

		// checking cart rules section render correctly
		cy.contains( 'h2', 'Cart Rules' ).should( 'be.visible' );

		// setting cart rules base, type, value, label
		cy.contains( 'button', '% - Percentage' )
			.should( 'be.visible' )
			.click();
		cy.contains( '$ - Fixed' ).should( 'be.visible' ).click();
		cy.get( 'input[placeholder="Value"]' ).focus().type( 50 );
		cy.get( 'input[placeholder="Discount Label"]' )
			.focus()
			.type( 'Your Cart Discount' );

		// checking cart conditions start from cart

		cy.contains( 'button', 'Add Condition' ).should( 'be.visible' ).click();
		cy.contains( 'button', 'Select Filter' ).should( 'be.visible' ).click();
		cy.get( 'ul[data-testid="filters-item"] li' ).should(
			'have.length',
			29
		);

		cy.contains( 'Cart Items Quantity' ).should( 'be.visible' ).click();
		cy.get( 'input[placeholder="Value"]' ).eq( 1 ).focus().type( 15 );
		// saving new campaign and navigate to campaign list
		cy.contains( 'Save & Exit' ).should( 'be.visible' ).click();

		// checking campaign created successfully
		cy.contains( 'Discount Campaigns' ).should( 'be.visible' );
		cy.contains( 'Cart Based Discount' ).should( 'be.visible' );

		// checking campaign edit interact correctly
		cy.get( 'tr td button:contains(Actions)' ).eq( 0 ).click();
		cy.contains( 'button', 'Edit' ).click();
		cy.get( 'input[placeholder="20% discount on all products"]' ).should(
			'have.value',
			'Cart Based Discount'
		);

		cy.get( 'input[placeholder="Value"]' ).should( 'have.value', 50 );
		cy.get( 'input[placeholder="Discount Label"]' ).should(
			'have.value',
			'Your Cart Discount'
		);

		// checking data persistent after reload
		cy.reload();
		cy.get( 'input[placeholder="20% discount on all products"]' ).should(
			'have.value',
			'Cart Based Discount'
		);

		cy.get( 'input[placeholder="Value"]' ).should( 'have.value', 50 );
		cy.get( 'input[placeholder="Discount Label"]' ).should(
			'have.value',
			'Your Cart Discount'
		);

		// update campaign name and navigate to list

		cy.get( 'input[placeholder="20% discount on all products"]' ).type(
			' Updated'
		);
		cy.contains( 'Update & Exit' ).should( 'be.visible' ).click();

		// checking update is successful or not
		cy.contains( 'Discount Campaigns' ).should( 'be.visible' );
		cy.contains( 'Cart Based Discount Updated' ).should( 'be.visible' );

		// by deleting clean up the campaign list
		cy.get( 'tr td button:contains(Actions)' ).eq( 0 ).click();
		cy.contains( 'button', 'Delete' ).click();
		cy.contains( 'button', 'Delete' ).click();
	} );
} );
