import { configureStore } from '@reduxjs/toolkit';
import { apiSlice } from '../features/api/apiSlice';
import discountReducer from '../features/discount/discountSlice';
import campaignReducer from './../features/campaigns/campaignSlice';

export const store = configureStore( {
	reducer: {
		[ apiSlice.reducerPath ]: apiSlice.reducer,
		discount: discountReducer,
		campaignState: campaignReducer,
	},
	middleware: ( getDefaultMiddleware ) =>
		getDefaultMiddleware().concat( apiSlice.middleware ),
} );
