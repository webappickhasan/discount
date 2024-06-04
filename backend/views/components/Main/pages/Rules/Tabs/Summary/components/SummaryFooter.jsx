import { useEffect } from 'react';
import { useSelector } from 'react-redux';
import { useNavigate, useSearchParams } from 'react-router-dom';
import { toast } from 'react-toastify';
import Button from '../../../../../components/Button';
import LoadingSpinner from '../../../../../components/LoadingSpinner';
import {
	useAddCampaignMutation,
	useUpdateCampaignMutation,
} from '../../../../../features/campaigns/campaignsApi';

const SummaryFooter = ( {
	handleBack = () => {},
	handleContinue = () => {},
} ) => {
	const [ searchParams ] = useSearchParams();
	const [ addCampaign, { isLoading: adding, isSuccess: added } ] =
		useAddCampaignMutation();
	const [ updateCampaign, { isLoading: updating, isSuccess: updated } ] =
		useUpdateCampaignMutation();

	const discount = useSelector( ( state ) => state.discount );
	const { discount_value, products } = discount;
	const navigate = useNavigate();

	const handleSave = () => {
		// if ( ! discount_value ) {
		// 	toast.error( 'Discount Value is Required' );
		// 	return;
		// }

		if ( products.length === 0 ) {
			toast.error( 'Please Select Some Product' );
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

	const handleUpdate = () => {
		if ( products.length === 0 ) {
			toast.error( 'Please Select Some Product' );
			return;
		}

		// if ( ! discount_value ) {
		// 	toast.error( 'Discount Value is Required' );
		// 	return;
		// }
		const dataForRequest = { ...discount };
		if ( ! dataForRequest.discount_valid_from ) {
			delete dataForRequest.discount_valid_from;
		}
		if ( ! dataForRequest.discount_valid_to ) {
			delete dataForRequest.discount_valid_to;
		}
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
	}, [ added, updated ] );

	return (
		<div className="disco-flex disco-justify-between disco-mt-16">
			<Button onClick={ handleBack } type="secondary">
				Back
			</Button>
			<div className="disco-flex disco-gap-4">
				{ searchParams.get( 'edit' ) ? (
					<Button
						disabled={ updating }
						onClick={ handleUpdate }
						type="secondary"
					>
						{ updating ? (
							<div className="disco-flex disco-gap-2 disco-items-center">
								<span>Updating</span>
								<LoadingSpinner size={ 4 } />
							</div>
						) : (
							'Update & Exit'
						) }
					</Button>
				) : (
					<Button
						disabled={ adding }
						onClick={ handleSave }
						type="secondary"
					>
						{ adding ? (
							<div className="disco-flex disco-gap-2 disco-items-center">
								<span>Saving</span>
								<LoadingSpinner size={ 4 } />
							</div>
						) : (
							'Save & Exit'
						) }
					</Button>
				) }
				<Button>Publish</Button>
			</div>
		</div>
	);
};
export default SummaryFooter;
