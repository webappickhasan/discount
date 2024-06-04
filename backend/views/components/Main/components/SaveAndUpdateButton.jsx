import { __ } from '@wordpress/i18n';
import { useEffect } from 'react';
import { useSelector } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';
import {
	useAddCampaignMutation,
	useUpdateCampaignMutation,
} from '../features/campaigns/campaignsApi';
import Button from './Button';
import LoadingSpinner from './LoadingSpinner';

export const SaveButton = () => {
	const [ addCampaign, { data, isLoading, isSuccess, isError } ] =
		useAddCampaignMutation();
	const navigate = useNavigate();

	const discount = useSelector( ( state ) => state.discount );
	const { products, name } = discount;
	const handleSave = () => {
		if ( products.length === 0 ) {
			toast.error( __( 'Please Select Some Product', 'disco' ) );
			return;
		}

		if ( name.trim().length === 0 ) {
			toast.error( __( 'Campaign Name is Required', 'disco' ) );
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

	useEffect( () => {
		if ( isSuccess ) {
			toast.success( __( 'New Campaign Added.', 'disco' ) );
			navigate( `?edit=${ data.id }`, {
				state: { ...data },
			} );
		}
		if ( isError ) {
			toast.success( __( 'Something Went Wrong.', 'disco' ) );
		}
	}, [ data ] );

	return (
		<Button
			className="!disco-py-1.5 !disco-px-4 !disco-font-normal"
			disabled={ isLoading }
			onClick={ handleSave }
		>
			{ isLoading ? (
				<div className="disco-flex disco-gap-2 disco-items-center">
					<span>{ __( 'Saving', 'disco' ) }</span>
					<LoadingSpinner size={ 4 } />
				</div>
			) : (
				__( 'Save', 'disco' )
			) }
		</Button>
	);
};

export const UpdateButton = () => {
	const [ updateCampaign, { isLoading, isSuccess, isError } ] =
		useUpdateCampaignMutation();
	const discount = useSelector( ( state ) => state.discount );
	const { products, name } = discount;

	const handleUpdate = () => {
		if ( products.length === 0 ) {
			toast.error( __( 'Please Select Some Product', 'disco' ) );
			return;
		}

		if ( name.trim().length === 0 ) {
			toast.error( __( 'Campaign Name is Required', 'disco' ) );
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
		if ( isSuccess ) {
			toast.success( __( 'Campaign Updated.', 'disco' ) );
		}
		if ( isError ) {
			toast.success( __( 'Something Went Wrong.', 'disco' ) );
		}
	}, [ isSuccess ] );

	return (
		<Button
			className="!disco-py-1.5 !disco-px-4 !disco-font-normal"
			disabled={ isLoading }
			onClick={ handleUpdate }
		>
			{ isLoading ? (
				<div className="disco-flex disco-gap-2 disco-items-center">
					<span> { __( 'Updating', 'disco' ) }</span>
					<LoadingSpinner size={ 4 } />
				</div>
			) : (
				__( 'Update', 'disco' )
			) }
		</Button>
	);
};
