import { __ } from '@wordpress/i18n';
import { useEffect, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { toast } from 'react-toastify';
import Button from '../../../components/Button';
import Input from '../../../components/Input';
import LoadingSpinner from '../../../components/LoadingSpinner';
import SingleSelect from '../../../components/SingleSelect';
import {
	resetSelectedIDs,
	setCampaignsInState,
	setSearchTerm,
} from '../../../features/campaigns/campaignSlice';
import {
	useDeleteCampaignMutation,
	usePatchCampaignMutation,
} from '../../../features/campaigns/campaignsApi';

const ActionsBar = ( { allCampaigns } ) => {
	const actions = {
		enable: __( 'Enable', 'disco' ),
		disable: __( 'Disable', 'disco' ),
		delete: __( 'Delete', 'disco' ),
	};
	const dispatch = useDispatch();
	const [ selectedAction, setSelectedAction ] = useState( '' );

	const { campaign_ids, searchTerm } = useSelector(
		( state ) => state.campaignState
	);

	const [
		patchCampaign,
		{ isLoading: patchLoading, isSuccess: patchSuccess },
	] = usePatchCampaignMutation();
	const [
		deleteCampaign,
		{ isLoading: deleteLoading, isSuccess: bulkDeleteSuccess },
	] = useDeleteCampaignMutation();

	const handleActionChange = ( action ) => {
		setSelectedAction( action );
	};

	const handleBulkAction = () => {
		if ( selectedAction ) {
			if ( selectedAction === 'enable' ) {
				campaign_ids.forEach( ( id ) => {
					patchCampaign( { id, data: { status: '1' } } );
				} );
			}
			if ( selectedAction === 'disable' ) {
				campaign_ids.forEach( ( id ) => {
					patchCampaign( { id, data: { status: '0' } } );
				} );
			}
			if ( selectedAction === 'delete' ) {
				campaign_ids.forEach( ( id ) => {
					deleteCampaign( id );
				} );
				dispatch( setSearchTerm( '' ) );
			}
		}
	};

	useEffect( () => {
		if ( bulkDeleteSuccess ) {
			dispatch( resetSelectedIDs() );
			toast.error( __( 'Campaigns Deleted', 'disco' ) );
			setSelectedAction( '' );
		}

		if ( patchSuccess && selectedAction == 'enable' ) {
			toast.success( __( 'Campaigns Enabled', 'disco' ) );
			setSelectedAction( '' );
		}
		if ( patchSuccess && selectedAction == 'disable' ) {
			toast.warn( __( 'Campaigns Disabled', 'disco' ) );
			setSelectedAction( '' );
		}
	}, [ bulkDeleteSuccess, patchSuccess ] );

	const handleSearchTermChange = ( e ) => {
		if ( e.target.value === '' ) {
			dispatch( setCampaignsInState( allCampaigns ) );
		}
		dispatch( setSearchTerm( e.target.value ) );
	};

	const handleSearchCampaign = () => {
		dispatch(
			setCampaignsInState(
				allCampaigns.filter( ( camp ) =>
					camp.name.toLowerCase().includes( searchTerm.toLowerCase() )
				)
			)
		);
	};

	return (
		<div className="disco-mt-10 disco-mb-4 disco-flex disco-justify-between">
			<div className="disco-flex disco-gap-4">
				<div className="disco-min-w-[160px]">
					<SingleSelect
						disabled={ patchLoading || deleteLoading }
						items={ actions }
						selected={ selectedAction }
						onchange={ handleActionChange }
						placeholder={ __( 'Bulk Actions', 'disco' ) }
					/>
				</div>
				<Button
					onClick={ handleBulkAction }
					type="secondary"
					className="!disco-py-0 !disco-px-4 !disco-text-gray-500 !disco-font-normal"
				>
					<span>{ __( 'Apply', 'disco' ) }</span>
					{ ( patchLoading || deleteLoading ) && (
						<LoadingSpinner size={ 4 } />
					) }
				</Button>
			</div>
			<div className="disco-flex disco-gap-4">
				<Input
					value={ searchTerm }
					onChange={ handleSearchTermChange }
					placeholder={ __( 'Search Campaign', 'disco' ) }
				/>
				<Button
					onClick={ handleSearchCampaign }
					type="secondary"
					className="!disco-py-0 !disco-px-4 !disco-text-gray-500 !disco-font-normal"
				>
					{ __( 'Search', 'disco' ) }
				</Button>
			</div>
		</div>
	);
};
export default ActionsBar;
