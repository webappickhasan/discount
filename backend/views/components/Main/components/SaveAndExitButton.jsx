import { __ } from '@wordpress/i18n';
import { useEffect } from 'react';
import { useSelector } from 'react-redux';
import { useNavigate, useSearchParams } from 'react-router-dom';
import { toast } from 'react-toastify';
import {
	useAddCampaignMutation,
	useUpdateCampaignMutation,
} from '../features/campaigns/campaignsApi';
import Button from './Button';
import LoadingSpinner from './LoadingSpinner';
import { SaveButton, UpdateButton } from './SaveAndUpdateButton';

const SaveAndExitButton = () => {
	const [ searchParams ] = useSearchParams();
	const [
		addCampaign,
		{ isLoading: adding, isSuccess: added, isError: addingError },
	] = useAddCampaignMutation();
	const [ updateCampaign, { isLoading: updating, isSuccess: updated } ] =
		useUpdateCampaignMutation();

	const discount = useSelector( ( state ) => state.discount );
	const { products, name } = discount;
	const navigate = useNavigate();

	const handleSaveAndExit = () => {
		if ( products.length === 0 ) {
			toast.error( 'Please Select Some Product' );
			return;
		}

		if ( name.trim().length === 0 ) {
			toast.error( 'Campaign Name is Required' );
			return;
		}

		const dataForRequest = { ...discount };
		if ( ! dataForRequest.discount_valid_from ) {
			delete dataForRequest.discount_valid_from;
		}
		if ( ! dataForRequest.discount_valid_to ) {
			delete dataForRequest.discount_valid_to;
		}
		addCampaign( dataForRequest );
	};

	const handleUpdateAndExit = () => {
		if ( products.length === 0 ) {
			toast.error( 'Please Select Some Product' );
			return;
		}

		if ( name.trim().length === 0 ) {
			toast.error( 'Campaign Name is Required' );
			return;
		}

		const dataForRequest = { ...discount };
		if ( ! dataForRequest.discount_valid_from ) {
			delete dataForRequest.discount_valid_from;
		}
		if ( ! dataForRequest.discount_valid_to ) {
			delete dataForRequest.discount_valid_to;
		}
		delete dataForRequest.created_by;
		delete dataForRequest.modified_by;
		updateCampaign( {
			id: discount.id,
			data: {
				...dataForRequest,
				priority: String( dataForRequest.priority ),
			},
		} );
	};

	useEffect( () => {
		if ( added ) {
			toast.success( 'New Campaign Added.' );
			navigate( '/' );
		}
		if ( updated ) {
			toast.success( 'Campaign Updated.' );
			navigate( '/' );
		}
		if ( addingError ) {
			toast.error( 'Something Went Wrong' );
		}
	}, [ added, updated, addingError ] );

	return (
		<div className="disco-flex disco-gap-3">
			{ searchParams.get( 'edit' ) ? (
				<div className="disco-flex disco-items-center disco-gap-3">
					<UpdateButton />
					<Button
						className="!disco-py-1.5 !disco-px-4 !disco-font-normal"
						disabled={ updating }
						onClick={ handleUpdateAndExit }
					>
						{ updating ? (
							<div className="disco-flex disco-gap-2 disco-items-center">
								<span>
									{ __( 'Updating', 'disco' ) }
								</span>
								<LoadingSpinner size={ 4 } />
							</div>
						) : (
							__( 'Update & Exit', 'disco' )
						) }
					</Button>
				</div>
			) : (
				<div className="disco-flex disco-items-center disco-gap-3">
					<SaveButton />
					<Button
						className="!disco-py-1.5 !disco-px-4 !disco-font-normal"
						disabled={ adding }
						onClick={ handleSaveAndExit }
					>
						{ adding ? (
							<div className="disco-flex disco-gap-2 disco-items-center">
								<span>
									{ __( 'Saving', 'disco' ) }
								</span>
								<LoadingSpinner size={ 4 } />
							</div>
						) : (
							__( 'Save & Exit', 'disco' )
						) }
					</Button>
				</div>
			) }
		</div>
	);
};
export default SaveAndExitButton;
