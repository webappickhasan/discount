import { useEffect } from 'react';
import { useDispatch } from 'react-redux';
import { setCampaignsInState } from '../../features/campaigns/campaignSlice';
import { useGetCampaignsQuery } from '../../features/campaigns/campaignsApi';
import {
	useGetBOGOTypesQuery,
	useGetDiscountBasedOnQuery,
	useGetDiscountMethodsQuery,
	useGetDiscountTypesQuery,
	useGetFiltersQuery,
	useGetWhatGetsDiscountQuery,
} from '../../features/discount/discountApi';
import { useGetSettingsQuery } from '../../features/settings/settingsApi';
import ActionsBar from './components/ActionsBar';
import CampaignsList from './components/CampaignsList';
import Header from './components/Header';

const Campaigns = () => {
	/*
	 * for caching api data
	 * don't remove this hooks
	 */
	const {} = useGetFiltersQuery();
	const {} = useGetWhatGetsDiscountQuery();
	const {} = useGetDiscountMethodsQuery();
	const {} = useGetDiscountTypesQuery();
	const {} = useGetSettingsQuery();
	const {} = useGetDiscountBasedOnQuery();
	const {} = useGetBOGOTypesQuery();

	const dispatch = useDispatch();

	const {
		data: allCampaigns,
		isLoading,
		isError,
		error,
	} = useGetCampaignsQuery();

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
		if ( allCampaigns && allCampaigns.length > 0 ) {
			dispatch( setCampaignsInState( allCampaigns ) );
		}
	}, [ allCampaigns ] );

	return (
		<div className="disco-mt-2.5 disco-mr-4 disco-ml-0.5 disco-flex disco-gap-4">
			<div className="disco-flex-grow disco-border disco-border-gray-200 disco-p-5">
				<Header />
				<ActionsBar allCampaigns={ allCampaigns } />
				<CampaignsList
					isLoading={ isLoading }
					isError={ isError }
					error={ error }
				/>
			</div>
			{/*<div className="disco-min-w-[100px] disco-max-w-[300px] disco-bg-grey-light"></div>*/}
		</div>
	);
};
export default Campaigns;
