import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import Card from '../../../../../../../../../components/Card';
import LoadingSpinner from '../../../../../../../../../components/LoadingSpinner';
import SingleSelect from '../../../../../../../../../components/SingleSelect';
import {
	useGetBOGOTypesQuery,
	useGetDiscountBasedOnQuery,
} from '../../../../../../../../../features/discount/discountApi';
import {
	changeBOGOType,
	updateOption,
} from '../../../../../../../../../features/discount/discountSlice';

const BOGOSetup = () => {
	const dispatch = useDispatch();
	const { discount_based_on, bogo_type } = useSelector(
		( state ) => state.discount
	);

	const { data: discountBaseOn, isLoading } = useGetDiscountBasedOnQuery();
	const { data: bogoTypes, isLoading: bogoTypesLoading } =
		useGetBOGOTypesQuery();

	const handleBOGOTypeChange = ( active ) => {
		dispatch( changeBOGOType( active ) );
	};

	const handleDiscountBasedOn = ( active ) => {
		dispatch(
			updateOption( { option: 'discount_based_on', value: active } )
		);
	};

	if ( isLoading || bogoTypesLoading ) {
		return (
			<Card heading="BOGO">
				<div className="disco-p-3">
					<LoadingSpinner />
				</div>
			</Card>
		);
	}

	return (
		<Card heading="BOGO">
			<div className="disco-p-4">
				<div className="disco-grid disco-grid-cols-12 disco-items-center">
					<div className="disco-col-span-2">
						<p className="disco-text-sm disco-font-medium disco-text-gray-500">
							{ __( 'BOGO Type', 'disco' ) }
						</p>
					</div>
					<div className="disco-col-span-2">
						<SingleSelect
							items={ bogoTypes.values }
							placeholder={ __(
								'Select BOGO Type',
								DISCO.TEXTDOMAIN
							) }
							selected={ bogo_type }
							onchange={ handleBOGOTypeChange }
						/>
					</div>
				</div>

				{ /* <div className="disco-grid disco-grid-cols-12 disco-items-center">
					<div className="disco-col-span-2">
						<p className="disco-text-sm disco-font-medium disco-text-gray-500">
							{ __( 'Discount Based On', 'disco' ) }
						</p>
					</div>
					<div className="disco-col-span-2">
						<SingleSelect
							items={ discountBaseOn.values }
							placeholder={ __(
								'Select Discount Based On',
								DISCO.TEXTDOMAIN
							) }
							selected={ discount_based_on }
							onchange={ handleDiscountBasedOn }
						/>
					</div>
				</div> */ }
			</div>
		</Card>
	);
};
export default BOGOSetup;
