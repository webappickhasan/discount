import { createSlice } from '@reduxjs/toolkit';
import { v4 as uuidv4 } from 'uuid';

const initialState = {
	name: '',
	discount_intent: 'Product',

	discount_method: 'automated',
	discount_coupon: '',

	priority: '1',
	status: '1',

	show_discount_on_cart_page: false,

	bogo_type: 'all',
	discount_based_on: 'item_quantity',

	discount_rules: [
		{
			id: uuidv4(),
			min: '',
			max: '',
			get_quantity: '',
			get_ids: [],
			discount_type: 'percent',
			discount_value: '',
			discount_label: '',
			recursive: 'no',
		},
	],

	discount_max_user: '0',
	discount_valid_from: '',
	discount_valid_to: '',

	products: [ 'all' ],
	conditions: [],

	ui: [ 0, 0 ],

	design_blocks: {
		badge: {
			enable: true,
			badge_style: 'left_bar',
			text: 'Your Text',
			text_color: '#fff8f8',
			bg_color: '#282726',
		},
		singlePage: {
			enable: true,
			single_page_style: 'below_title',
			text: 'Single Products Page Text',
			text_color: '#fff8f8',
			bg_color: '#282726',
		},
		cartPage: {
			enable: true,
			cart_style: 'below_price',
			text: 'You Got Free Shipping 🚙',
			text_color: '#fff8f8',
			bg_color: '#282726',
		},
	},
};

export const discountSlice = createSlice( {
	name: 'discount',
	initialState,
	reducers: {
		reset: () => initialState,
		editCampaign: ( state, action ) => {
			return action.payload;
		},
		updateOption: ( state, action ) => {
			state[ action.payload.option ] = action.payload.value;
		},

		updateProducts: ( state, action ) => {
			if (
				state.products.find(
					( _product ) => _product.id === action.payload.id
				)
			) {
				state.products = state.products.filter(
					( _product ) => _product.id !== action.payload.id
				);
			} else {
				state.products.push( action.payload );
			}
		},

		removeProduct: ( state, action ) => {
			state.products = state.products.filter(
				( _product ) => _product.id !== action.payload.id
			);
		},

		// *! Condition's Reducers

		addCondition: ( state, action ) => {
			const condition_group_index = state.conditions.findIndex(
				( filter ) => filter.id === action.payload
			);
			if ( condition_group_index !== -1 ) {
				state.conditions[ condition_group_index ].base_filters.push( {
					id: uuidv4(),
					compare_with: '',
					operator: 'and',
				} );
			}
		},

		addConditionGroup: ( state, action ) => {
			state.conditions.push( {
				id: uuidv4(),
				base_operator: 'and',
				base_filters: [
					{
						id: uuidv4(),
						compare_with: '',
						operator: 'and',
					},
				],
			} );
		},

		updateConditionGroup: ( state, action ) => {
			const condition_group_index = state.conditions.findIndex(
				( filter ) => filter.id === action.payload.id
			);
			if ( condition_group_index !== -1 ) {
				state.conditions[ condition_group_index ].base_operator =
					action.payload.operator;
			}
		},

		deleteConditionGroup: ( state, action ) => {
			state.conditions = state.conditions.filter(
				( conditionGroup ) => conditionGroup.id !== action.payload
			);
		},

		updateConditionValues: ( state, action ) => {
			const condition_group_index = state.conditions.findIndex(
				( filter ) => filter.id === action.payload.group_id
			);

			if ( condition_group_index !== -1 ) {
				const condition_index = state.conditions[
					condition_group_index
				].base_filters.findIndex(
					( filter ) => filter.id === action.payload.values.id
				);

				if ( condition_index !== -1 ) {
					state.conditions[ condition_group_index ].base_filters[
						condition_index
					] = action.payload.values;
				}
			}
		},

		deleteCondition: ( state, action ) => {
			const condition_group_index = state.conditions.findIndex(
				( filter ) => filter.id === action.payload.group_id
			);
			if ( condition_group_index !== -1 ) {
				if (
					state.conditions[ condition_group_index ].base_filters
						.length === 1
				) {
					state.conditions = state.conditions.filter(
						( conditionGroup ) =>
							conditionGroup.id !== action.payload.group_id
					);
				} else {
					state.conditions[ condition_group_index ].base_filters =
						state.conditions[
							condition_group_index
						].base_filters.filter(
							( condition ) =>
								condition.id !== action.payload.condition_id
						);
				}
			}
		},

		// *! UI Tab Reducers

		setTab: ( state, action ) => {
			state.ui[ 0 ] = action.payload;
			if ( state.ui[ 1 ] < state.ui[ 0 ] ) {
				state.ui[ 1 ] = state.ui[ 0 ];
			}
		},

		// *! Discount Rules Reducers

		changeBOGOType: ( state, action ) => {
			if ( state.bogo_type !== action.payload ) {
				state.bogo_type = action.payload;
				state.discount_rules = state.discount_rules.map( ( rule ) => ( {
					...rule,
					get_ids: [],
				} ) );
			}
		},

		changeDiscountIntention: ( state, action ) => {
			state.discount_intent = action.payload;
			state.discount_rules = [
				{
					id: uuidv4(),
					min: '',
					max: '',
					get_quantity: '',
					get_ids: [],
					discount_type: 'percent',
					discount_value: '',
					discount_label: '',
					recursive: 'no',
				},
			];
		},

		addNewDiscountRule: ( state, action ) => {
			state.discount_rules.push( {
				id: uuidv4(),
				min: '',
				max: '',
				get_quantity: '',
				get_ids: [],
				discount_type: 'percent',
				discount_value: '',
				discount_label: '',
				recursive: 'no',
			} );
		},

		updateDiscountRule: ( state, action ) => {
			const index = state.discount_rules.findIndex(
				( item ) => item.id === action.payload.id
			);
			if ( index !== -1 ) {
				state.discount_rules[ index ] = action.payload;
			}
		},
		deleteDiscountRule: ( state, action ) => {
			state.discount_rules = state.discount_rules.filter(
				( item ) => item.id !== action.payload
			);
		},
	},

	// *! Design Block Reducers

	updateBadge: ( state, action ) => {
		state.design_blocks.badge[ action.payload.name ] = action.payload.value;
	},
	updateSinglePage: ( state, action ) => {
		state.design_blocks.singlePage[ action.payload.name ] =
			action.payload.value;
	},
	updateCartPage: ( state, action ) => {
		state.design_blocks.cartPage[ action.payload.name ] =
			action.payload.value;
	},
} );

export const {
	updateBadge,
	reset,
	editCampaign,
	updateOption,
	updateProducts,
	removeProduct,
	addCondition,
	addConditionGroup,
	updateConditionGroup,
	deleteConditionGroup,
	updateConditionValues,
	deleteCondition,
	setTab,

	changeBOGOType,
	changeDiscountIntention,
	addNewDiscountRule,
	updateDiscountRule,
	deleteDiscountRule,

	updateSinglePage,
	updateCartPage,
} = discountSlice.actions;

export default discountSlice.reducer;
