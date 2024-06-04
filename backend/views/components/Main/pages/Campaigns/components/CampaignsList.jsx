import { CheckIcon } from '@heroicons/react/24/outline';
import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import ComponentBox from '../../../components/ComponentBox';
import { toggleCampaignSelect } from '../../../features/campaigns/campaignSlice';
import { useGetDiscountIntentsQuery } from '../../../features/discount/discountApi';
import CampaignLoading from './CampaignLoading';
import CampaignRows from './CampaignRows';

const CampaignsList = ( { isLoading, isError, error } ) => {
	const { campaign_ids, campaigns, searchTerm } = useSelector(
		( state ) => state.campaignState
	);
	const dispatch = useDispatch();

	const { data: discountIntents, isLoading: discountIntentsLoading } =
		useGetDiscountIntentsQuery();

	const handleToggleCampaignMultiSelect = ( ids ) => {
		if ( ids.every( ( id ) => campaign_ids.includes( id ) ) ) {
			ids.forEach( ( id ) => {
				dispatch( toggleCampaignSelect( id ) );
			} );
		} else {
			ids.forEach( ( id ) => {
				if ( ! campaign_ids.includes( id ) ) {
					dispatch( toggleCampaignSelect( id ) );
				}
			} );
		}
	};

	if ( isError ) {
		return (
			<>
				{ error.status !== 403 && (
					<ComponentBox className="disco-my-8">
						<div className="disco-text-xl disco-font-bold">
							{ error?.data?.message }
						</div>
					</ComponentBox>
				) }
			</>
		);
	}

	return (
		<ComponentBox className="disco-mb-8">
			<div className="">
				<table className="disco-table-auto disco-w-full disco-border-separate disco-border-spacing-0 ">
					<thead>
						<tr>
							<th
								scope="col"
								className="!disco-font-medium disco-text-base disco-top-0 disco-z-10 disco-border-b disco-text-left disco-border-gray-300 disco-pb-3.5 disco-pr-3"
							></th>
							<th
								scope="col"
								className="!disco-font-medium disco-text-base disco-top-0 disco-z-10 disco-border-b disco-text-left disco-border-gray-300 disco-pb-3.5 disco-pr-3"
							>
								<button
									disabled={ isLoading }
									onClick={ () =>
										handleToggleCampaignMultiSelect(
											campaigns?.map(
												( campaign ) => campaign.id
											)
										)
									}
									className={ `disco-shrink-0 disco-h-4 disco-w-4 disco-rounded disco-border disco-flex disco-justify-center disco-items-center ${
										campaign_ids.length > 0 &&
										campaigns
											?.map( ( campaign ) => campaign.id )
											?.every( ( id ) =>
												campaign_ids.includes( id )
											)
											? 'disco-border-primary-dark'
											: 'disco-border-gray-500 '
									}` }
								>
									{ campaign_ids.length > 0 &&
										campaigns
											?.map( ( campaign ) => campaign.id )
											?.every( ( id ) =>
												campaign_ids.includes( id )
											) && (
											<CheckIcon className="disco-text-primary-dark" />
										) }
								</button>
							</th>
							<th
								scope="col"
								className="!disco-font-medium disco-text-base disco-top-0 disco-z-10 disco-border-b disco-text-left disco-border-gray-300 disco-pb-3.5 disco-pr-3"
							>
								{ __( 'Name', 'disco' ) }
							</th>
							<th
								scope="col"
								className="!disco-font-medium disco-text-base disco-top-0 disco-z-10 disco-border-b disco-text-left disco-border-gray-300 disco-pb-3.5 disco-pr-3"
							>
								{ __( 'Intent', 'disco' ) }
							</th>
							<th
								scope="col"
								className="!disco-font-medium disco-text-base disco-top-0 disco-z-10 disco-border-b disco-text-left disco-border-gray-300 disco-pb-3.5 disco-pr-3"
							>
								{ __( 'Method', 'disco' ) }
							</th>
							<th
								scope="col"
								className="!disco-font-medium disco-text-base disco-top-0 disco-z-10 disco-border-b disco-text-left disco-border-gray-300 disco-pb-3.5 disco-pr-3"
							>
								{ __( 'Type', 'disco' ) }
							</th>
							<th
								scope="col"
								className="!disco-font-medium disco-text-base disco-top-0 disco-z-10 disco-border-b disco-text-left disco-border-gray-300 disco-pb-3.5 disco-pr-3"
							>
								{ __( 'Date', 'disco' ) }
							</th>

							<th
								scope="col"
								className="!disco-font-medium disco-text-base disco-top-0 disco-z-10 disco-border-b disco-text-left disco-border-gray-300 disco-pb-3.5 disco-pr-3"
							>
								{ __( 'Status', 'disco' ) }
							</th>
							<th
								scope="col"
								className="!disco-font-medium disco-text-base disco-top-0 disco-z-10 disco-border-b disco-text-left disco-border-gray-300 disco-pb-3.5"
							>
								{ __( 'Action', 'disco' ) }
							</th>
						</tr>
					</thead>
					{ isLoading || discountIntentsLoading ? (
						<tbody>
							<CampaignLoading line={ 3 } />
						</tbody>
					) : (
						<CampaignRows
							searchTerm={ searchTerm }
							discountIntents={ discountIntents }
							campaigns={ campaigns }
						/>
					) }
				</table>
			</div>
			{ campaigns.length === 0 && ! isLoading && (
				<div className="disco-mt-4">
					<p className="disco-text-xl disco-font-medium">
						{ __( 'Sorry, No Campaign Found!', 'disco' ) }
					</p>
				</div>
			) }
		</ComponentBox>
	);
};
export default CampaignsList;
