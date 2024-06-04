import { PlusCircleIcon } from '@heroicons/react/24/outline';
import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import Button from '../../../../../../../../components/Button';
import Card from '../../../../../../../../components/Card';
import LoadingSpinner from '../../../../../../../../components/LoadingSpinner';
import { useGetDiscountBasedOnQuery } from '../../../../../../../../features/discount/discountApi';
import {
	addNewDiscountRule,
	updateOption,
} from '../../../../../../../../features/discount/discountSlice';
import BulkOrBundleItem from './BulkOrBundleItem';

const SetBulkOrBundle = () => {
	const { discount_rules, discount_intent, discount_based_on } = useSelector(
		( state ) => state.discount
	);
	const { data: discountBaseOn, isLoading } = useGetDiscountBasedOnQuery();

	const dispatch = useDispatch();
	const handleAddBulkOption = () => {
		dispatch( addNewDiscountRule() );
	};

	const handleDiscountBasedOn = ( active ) => {
		dispatch(
			updateOption( { option: 'discount_based_on', value: active } )
		);
	};

	if ( isLoading ) {
		return (
			<Card heading={ `${ discount_intent } Rules` }>
				<div className="disco-p-4">
					<LoadingSpinner />
				</div>
			</Card>
		);
	}

	return (
		<Card heading={ `${ discount_intent } Rules` }>
			{ /* <div className="disco-flex disco-gap-6 disco-px-4 disco-py-2 disco-border-b disco-border-gray-200 disco-items-center">
				<p className="disco-text-sm disco-font-medium disco-text-gray-500">
					{ __( 'Discount Based On', 'disco' ) }
				</p>

				<SingleSelect
					className="disco-w-[200px]"
					items={ discountBaseOn.values }
					placeholder={ __(
						'Select Discount Based On',
						DISCO.TEXTDOMAIN
					) }
					selected={ discount_based_on }
					onchange={ handleDiscountBasedOn }
				/>
			</div> */ }

			<div className="disco-space-y-6 disco-p-4">
				{ discount_rules.map( ( rule, index ) => (
					<BulkOrBundleItem
						discountBased={
							discountBaseOn?.values?.[ discount_based_on ]
						}
						discountIntent={ discount_intent }
						key={ rule.id }
						index={ index }
						rule={ rule }
					/>
				) ) }
			</div>

			<div className="disco-flex disco-justify-between disco-border-t disco-border-gray-200 disco-items-center">
				<div className="disco-px-4 disco-py-2">
					<Button
						onClick={ handleAddBulkOption }
						className="!disco-px-3 !disco-py-1.5 !disco-text-sm !disco-font-normal"
						icon={
							<PlusCircleIcon className="disco-h-5 disco-w-5" />
						}
					>
						{ __( 'Add More', 'disco' ) }
					</Button>
				</div>
			</div>
		</Card>
	);
};
export default SetBulkOrBundle;
