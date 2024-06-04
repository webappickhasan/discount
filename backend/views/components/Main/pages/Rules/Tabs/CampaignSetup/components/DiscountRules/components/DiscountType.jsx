import { useDispatch, useSelector } from 'react-redux';

import { __ } from '@wordpress/i18n';
import Card from '../../../../../../../components/Card';
import Input from '../../../../../../../components/Input';
import SingleSelect from '../../../../../../../components/SingleSelect';
import {
	useGetDiscountBasedOnQuery,
	useGetDiscountTypesQuery,
} from '../../../../../../../features/discount/discountApi';
import {
	updateDiscountRule,
	updateOption,
} from '../../../../../../../features/discount/discountSlice';

const DiscountType = () => {
	const { data: types, isLoading } = useGetDiscountTypesQuery();
	const { data: discountBaseOn, isLoading: basedOnLoading } =
		useGetDiscountBasedOnQuery();

	const { discount_rules, discount_intent, discount_based_on } = useSelector(
		( state ) => state.discount
	);

	const { discount_type, discount_label, discount_value } =
		discount_rules[ 0 ];

	const dispatch = useDispatch();

	const handleTypeChange = ( active ) => {
		dispatch(
			updateDiscountRule( {
				...discount_rules[ 0 ],
				discount_type: active,
			} )
		);
	};
	const handleChange = ( e ) => {
		dispatch(
			updateDiscountRule( {
				...discount_rules[ 0 ],
				[ e.target.name ]: e.target.value,
			} )
		);
	};

	const handleDiscountBasedOn = ( active ) => {
		dispatch(
			updateOption( { option: 'discount_based_on', value: active } )
		);
	};

	return (
		<Card heading={ `${ discount_intent } Rules` }>
			<div className="disco-flex disco-gap-6 disco-p-4">
				{ /* { discount_intent === 'Cart' && (
					<div className="disco-grow disco-max-w-[200px]">
						<label className="disco-block disco-text-sm disco-text-gray-500 disco-mb-1.5">
							{ __( 'Discount Based On:', 'disco' ) }
						</label>
						{ basedOnLoading ? (
							<div className="disco-bg-gray-200 disco-h-[42px] disco-rounded-md disco-animate-pulse"></div>
						) : (
							<SingleSelect
								items={ discountBaseOn.values }
								placeholder={ __(
									'Select Discount Based On',
									DISCO.TEXTDOMAIN
								) }
								selected={ discount_based_on }
								onchange={ handleDiscountBasedOn }
							/>
						) }
					</div>
				) } */ }

				<div className="disco-grow disco-max-w-[400px]">
					<label className="disco-block disco-text-sm disco-text-gray-500 disco-mb-1.5">
						{ __( 'Discount Type:', 'disco' ) }
					</label>
					{ isLoading ? (
						<div className="disco-bg-gray-200 disco-h-[42px] disco-rounded-md disco-animate-pulse"></div>
					) : (
						<SingleSelect
							items={ types?.values }
							selected={ discount_type }
							onchange={ handleTypeChange }
							placeholder={ __(
								'Select Discount Type',
								DISCO.TEXTDOMAIN
							) }
						/>
					) }
				</div>

				{ discount_type !== 'free' && (
					<div>
						<label className="disco-block disco-text-sm disco-text-gray-500 disco-mb-2">
							{ __( 'Discount Value:', 'disco' ) }
						</label>
						<Input
							name="discount_value"
							onChange={ handleChange }
							value={ discount_value }
							placeholder={ __( 'Value', 'disco' ) }
							type="number"
						/>
					</div>
				) }

				<div className="disco-relative">
					<label className="disco-block disco-text-sm disco-text-gray-500 disco-mb-2">
						{ __( 'Discount Label:', 'disco' ) }
					</label>
					<Input
						value={ discount_label }
						onChange={ handleChange }
						name="discount_label"
						placeholder={ __( 'Discount Label', 'disco' ) }
					/>
				</div>
			</div>
		</Card>
	);
};
export default DiscountType;
