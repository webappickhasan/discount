import { apiSlice } from './../api/apiSlice';

export const discountApi = apiSlice.injectEndpoints( {
	endpoints: ( builder ) => ( {
		getFilters: builder.query( {
			query: () => `dropdown/?search=filters`,
		} ),
		getConditions: builder.query( {
			query: () => `dropdown/?search=conditions`,
		} ),
		getDiscountIntents: builder.query( {
			query: () => `dropdown/?search=discount_intents`,
		} ),
		getDiscountTypes: builder.query( {
			query: () => `dropdown/?search=discount_types`,
		} ),
		getDiscountMethods: builder.query( {
			query: () => `dropdown/?search=discount_methods`,
		} ),
		getWhatGetsDiscount: builder.query( {
			query: () => `dropdown/?search=products`,
		} ),
		getDiscountBasedOn: builder.query( {
			query: () => `dropdown/?search=discount_based_on`,
		} ),
		getBOGOTypes: builder.query( {
			query: () => `dropdown/?search=bogo_types`,
		} ),
	} ),
} );

export const {
	useGetFiltersQuery,
	useGetConditionsQuery,
	useGetDiscountIntentsQuery,
	useGetDiscountTypesQuery,
	useGetDiscountMethodsQuery,
	useGetWhatGetsDiscountQuery,
	useGetDiscountBasedOnQuery,
	useGetBOGOTypesQuery,
} = discountApi;
