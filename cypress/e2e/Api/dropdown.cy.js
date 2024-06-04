import filters from '../../fixtures/filters.json';

describe( 'Dropdown Api', () => {
	describe( 'Discount Intents', () => {
		it( 'Successful Response', () => {
			cy.api( {
				method: 'GET',
				url: 'http://localhost/quasar/wp-json/disco/v1/dropdown/?search=discount_intents',
				headers: {
					Authorization: 'Basic YWRtaW46YWRtaW4=', // admin:admin
				},
			} ).then( ( response ) => {
				expect( response.status ).to.eq( 200 );
				expect( response.body ).to.deep.equal( {
					name: 'discount_intents',
					values: {
						Product: 'Product',
						Cart: 'Cart',
						Shipping: 'Free Shipping',
						Bulk: 'Bulk Discount',
						Bundle: 'Bundle Discount',
						BOGO: 'BOGO',
					},
				} );
			} );
		} );
	} );

	describe( 'All or Few Products', () => {
		it( 'Successful Response', () => {
			cy.api( {
				method: 'GET',
				url: 'http://localhost/quasar/wp-json/disco/v1/dropdown/?search=products',
				headers: {
					Authorization: 'Basic YWRtaW46YWRtaW4=', // admin:admin
				},
			} ).then( ( response ) => {
				expect( response.status ).to.eq( 200 );
				expect( response.body ).to.deep.equal( {
					name: 'products',
					values: {
						all_products: 'All Products',
						products: 'Few Products',
					},
				} );
			} );
		} );
	} );

	describe( 'Discount Methods', () => {
		it( 'Successful Response', () => {
			cy.api( {
				method: 'GET',
				url: 'http://localhost/quasar/wp-json/disco/v1/dropdown/?search=discount_methods',
				headers: {
					Authorization: 'Basic YWRtaW46YWRtaW4=', // admin:admin
				},
			} ).then( ( response ) => {
				expect( response.status ).to.eq( 200 );
				expect( response.body ).to.deep.equal( {
					name: 'discount_methods',
					values: {
						automated: 'Automated Discount',
						coupon: 'Coupon Discount',
					},
				} );
			} );
		} );
	} );

	describe( 'Discount Types', () => {
		it( 'Successful Response', () => {
			cy.api( {
				method: 'GET',
				url: 'http://localhost/quasar/wp-json/disco/v1/dropdown/?search=discount_types',
				headers: {
					Authorization: 'Basic YWRtaW46YWRtaW4=', // admin:admin
				},
			} ).then( ( response ) => {
				expect( response.status ).to.eq( 200 );
				expect( response.body ).to.deep.equal( {
					name: 'discount_types',
					values: {
						percent: '% - Percentage',
						fixed: '$ - Fixed',
						percent_per_product: '% - Percentage Per Product',
						fixed_per_product: '$ - Fixed Per Product',
						free: 'Free',
					},
				} );
			} );
		} );
	} );

	describe( 'Filters', () => {
		it( 'Successful Response', () => {
			cy.api( {
				method: 'GET',
				url: 'http://localhost/quasar/wp-json/disco/v1/dropdown/?search=filters',
				headers: {
					Authorization: 'Basic YWRtaW46YWRtaW4=', // admin:admin
				},
			} ).then( ( response ) => {
				expect( response.status ).to.eq( 200 );
				expect( response.body ).to.deep.equal( filters );
			} );
		} );
	} );

	describe( 'User Roles', () => {
		it( 'Successful Response', () => {
			cy.api( {
				method: 'GET',
				url: 'http://localhost/quasar/wp-json/disco/v1/dropdown/?search=user_roles',
				headers: {
					Authorization: 'Basic YWRtaW46YWRtaW4=', // admin:admin
				},
			} ).then( ( response ) => {
				expect( response.status ).to.eq( 200 );
				expect( response.body ).to.deep.equal( {
					name: 'user_roles',
					values: {
						administrator: 'Administrator',
						editor: 'Editor',
						author: 'Author',
						contributor: 'Contributor',
						subscriber: 'Subscriber',
						customer: 'Customer',
						shop_manager: 'Shop manager',
					},
				} );
			} );
		} );
	} );
} );
