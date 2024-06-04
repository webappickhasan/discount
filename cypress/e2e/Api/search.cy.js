describe( 'Search Api', () => {
	describe( 'Product Search', () => {
		it( 'Successful Response', () => {
			cy.api( {
				method: 'GET',
				url: 'http://localhost/quasar/wp-json/disco/v1/search/product/?search=beanie',
				headers: {
					Authorization: 'Basic YWRtaW46YWRtaW4=', // admin:admin
				},
			} ).then( ( response ) => {
				expect( response.status ).to.eq( 200 );
				expect( response.body ).to.deep.equal( [
					{
						id: 17,
						name: 'Beanie',
						sku: 'woo-beanie',
						image: 'http://localhost/quasar/wp-content/uploads/2023/09/beanie-2.jpg',
					},
				] );
			} );
		} );
	} );

	describe( 'Category Search', () => {
		it( 'Successful Response', () => {
			cy.api( {
				method: 'GET',
				url: 'http://localhost/quasar/wp-json/disco/v1/search/category/?search=acc',
				headers: {
					Authorization: 'Basic YWRtaW46YWRtaW4=', // admin:admin
				},
			} ).then( ( response ) => {
				expect( response.status ).to.eq( 200 );
				expect( response.body ).to.deep.equal( [
					{
						id: 20,
						name: 'Accessories',
						sku: null,
						image: null,
					},
				] );
			} );
		} );
	} );

	describe( 'Tag Search', () => {
		it( 'Successful Response', () => {
			cy.api( {
				method: 'GET',
				url: 'http://localhost/quasar/wp-json/disco/v1/search/tag/?search=jeans',
				headers: {
					Authorization: 'Basic YWRtaW46YWRtaW4=', // admin:admin
				},
			} ).then( ( response ) => {
				expect( response.status ).to.eq( 200 );
				expect( response.body ).to.deep.equal( [
					{
						id: 30,
						name: 'Jeans',
						sku: null,
						image: null,
					},
				] );
			} );
		} );
	} );

	describe( 'Attribute Search', () => {
		it( 'Successful Response', () => {
			cy.api( {
				method: 'GET',
				url: 'http://localhost/quasar/wp-json/disco/v1/search/attribute/?search=color',
				headers: {
					Authorization: 'Basic YWRtaW46YWRtaW4=', // admin:admin
				},
			} ).then( ( response ) => {
				expect( response.status ).to.eq( 200 );
				expect( response.body ).to.deep.equal( [
					{
						id: 'color',
						name: 'Color',
						sku: null,
						image: null,
					},
				] );
			} );
		} );
	} );

	describe( 'Coupon Search', () => {
		it( 'Successful Response', () => {
			cy.api( {
				method: 'GET',
				url: 'http://localhost/quasar/wp-json/disco/v1/search/coupon/?search=druppg',
				headers: {
					Authorization: 'Basic YWRtaW46YWRtaW4=', // admin:admin
				},
			} ).then( ( response ) => {
				expect( response.status ).to.eq( 200 );
				expect( response.body ).to.deep.equal( [
					{
						id: 42,
						name: 'druppg',
						sku: null,
						image: null,
					},
				] );
			} );
		} );
	} );
	describe( 'Customer Search', () => {
		it( 'Successful Response', () => {
			cy.api( {
				method: 'GET',
				url: 'http://localhost/quasar/wp-json/disco/v1/search/customer/?search=tarek',
				headers: {
					Authorization: 'Basic YWRtaW46YWRtaW4=', // admin:admin
				},
			} ).then( ( response ) => {
				expect( response.status ).to.eq( 200 );
				expect( response.body ).to.deep.equal( [
					{
						id: 2,
						name: 'Tarekul Islam (tarek.webappick@gmail.com)',
						sku: null,
						image: null,
					},
				] );
			} );
		} );
	} );
} );
