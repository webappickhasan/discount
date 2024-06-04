import { useEffect, useState } from 'react';
import { useDispatch } from 'react-redux';
import { useLocation, useSearchParams } from 'react-router-dom';
import LoadingSpinner from '../../components/LoadingSpinner';
import { useGetCampaignQuery } from '../../features/campaigns/campaignsApi';
import {
	useGetBOGOTypesQuery,
	useGetDiscountBasedOnQuery,
	useGetDiscountIntentsQuery,
	useGetDiscountMethodsQuery,
	useGetDiscountTypesQuery,
	useGetFiltersQuery,
	useGetWhatGetsDiscountQuery,
} from '../../features/discount/discountApi';
import { editCampaign } from '../../features/discount/discountSlice';
import MainTabs from './Tabs/MainTabs';

const RulesPage = () => {
	const [ searchParams ] = useSearchParams();
	const [ id, setId ] = useState( '' );
	const [ skip, setSkip ] = useState( true );
	const { data: campaign, isLoading } = useGetCampaignQuery( id, { skip } );
	const [ uiLoading, setUiLoading ] = useState( false );
	const dispatch = useDispatch();

	const {} = useGetFiltersQuery();
	const {} = useGetWhatGetsDiscountQuery();
	const {} = useGetDiscountMethodsQuery();
	const {} = useGetDiscountTypesQuery();
	const {} = useGetDiscountBasedOnQuery();
	const {} = useGetBOGOTypesQuery();
	const { isError, error } = useGetDiscountIntentsQuery();

	const { state } = useLocation();

	if ( searchParams.get( 'edit' ) && skip ) {
		if ( state ) {
			dispatch( editCampaign( state ) );
		} else {
			setUiLoading( true );
			const id = searchParams.get( 'edit' );
			setId( id );
			setSkip( false );
		}
	}

	useEffect( () => {
		if ( isError ) {
			if (
				error.status === 403 &&
				error.data.code === 'rest_cookie_invalid_nonce'
			) {
				window.location.replace( DISCO.site_url + '/wp-login.php' );
			}
		}
	}, [ isError ] );

	useEffect( () => {
		if ( campaign ) {
			dispatch( editCampaign( campaign ) );
		}
		setUiLoading( false );
	}, [ campaign ] );

	if ( isLoading || uiLoading ) {
		return (
			<div className="disco-h-96 disco-w-full disco-flex disco-justify-center disco-items-center">
				<LoadingSpinner />
			</div>
		);
	}

	return (
		<div className="disco-mt-2.5 disco-mr-5 disco-ml-0.5 disco-flex disco-gap-4">
			<div className="disco-flex-grow disco-border disco-border-gray-200 disco-bg-gray-50 disco-pt-5">
				<MainTabs />
			</div>
			{/*<div className="disco-min-w-[100px] disco-max-w-[300px] disco-bg-grey-light"></div>*/}
		</div>
	);
};
export default RulesPage;
