import { TrashIcon } from '@heroicons/react/24/outline';
import { __ } from '@wordpress/i18n';
import { useDispatch } from 'react-redux';
import Input from '../../../../../../../../components/Input';
import LoadingSpinner from '../../../../../../../../components/LoadingSpinner';
import { useGetDiscountTypesQuery } from '../../../../../../../../features/discount/discountApi';
import {
	deleteDiscountRule,
	updateDiscountRule,
} from '../../../../../../../../features/discount/discountSlice';
import SingleSelect from '../../.././../../../../../components/SingleSelect';

const BulkOrBundleItem = ( { rule, index, discountIntent } ) => {
	const { data: types, isLoading } = useGetDiscountTypesQuery();
	const dispatch = useDispatch();

	const handleChange = ( e ) => {
		dispatch(
			updateDiscountRule( {
				...rule,
				[ e.target.name ]: e.target.value,
			} )
		);
	};
	const handleTypeChange = ( active ) => {
		dispatch(
			updateDiscountRule( {
				...rule,
				discount_type: active,
			} )
		);
	};

	const handleRecursiveChange = ( e ) => {
		dispatch(
			updateDiscountRule( {
				...rule,
				[ e.target.name ]: rule.recursive === 'yes' ? 'no' : 'yes',
			} )
		);
	};

	const handleRuleDelete = ( id ) => {
		dispatch( deleteDiscountRule( id ) );
	};

	if ( isLoading ) {
		return (
			<div className="">
				<LoadingSpinner />
			</div>
		);
	}

	return (
		<div className="disco-flex disco-items-end disco-gap-4">
			<div className="disco-grow flex-shrink-0">
				<label
					className="disco-text-sm disco-block disco-text-gray-500 disco-mb-1"
					htmlFor="minimum-quantity"
				>
					{ discountIntent === 'Bulk'
						? __( `Min Quantity`, 'disco' )
						: 'Item Quantity' }
				</label>
				<Input
					value={ rule.min }
					onChange={ handleChange }
					name="min"
					className="disco-w-full"
					placeholder={
						discountIntent === 'Bulk'
							? __( 'Min', 'disco' )
							: 'Quantity'
					}
					type="number"
				/>
			</div>
			{ discountIntent === 'Bulk' && (
				<div className="disco-grow flex-shrink-0">
					<label
						className="disco-text-sm disco-block disco-text-gray-500 disco-mb-1"
						htmlFor="maximum-quantity"
					>
						{ __( `Max Quantity`, 'disco' ) }
					</label>
					<Input
						value={ rule.max }
						onChange={ handleChange }
						name="max"
						className="disco-w-full"
						placeholder={ __( 'Max', 'disco' ) }
						type="number"
					/>
				</div>
			) }
			<div className="disco-min-w-[250px] disco-grow flex-shrink-0">
				<label
					className="disco-text-sm disco-block disco-text-gray-500 disco-mb-1"
					htmlFor="discount-type"
				>
					{ __( 'Discount Type', 'disco' ) }
				</label>

				<SingleSelect
					className="disco-min-w-[250px] disco-w-full"
					items={ types.values }
					selected={ rule.discount_type }
					onchange={ handleTypeChange }
					placeholder={ __(
						'Select Discount Type',
						DISCO.TEXTDOMAIN
					) }
				/>
			</div>
			{ rule.discount_type !== 'free' && (
				<div className="disco-grow flex-shrink-0">
					<label
						className="disco-text-sm disco-block disco-text-gray-500 disco-mb-1"
						htmlFor="discount-value"
					>
						{ __( 'Discount Value', 'disco' ) }
					</label>
					<Input
						value={ rule.discount_value }
						onChange={ handleChange }
						name="discount_value"
						className="disco-w-full"
						placeholder={ __( 'Value', 'disco' ) }
						type="number"
					/>
				</div>
			) }
			<div className=" disco-grow flex-shrink-0">
				<label
					className="disco-text-sm disco-block disco-text-gray-500 disco-mb-1"
					htmlFor="bulk-title"
				>
					{ __( 'Discount Label', 'disco' ) }
				</label>
				<div className="disco-flex disco-items-center disco-gap-2">
					<Input
						value={ rule.discount_label }
						onChange={ handleChange }
						name="discount_label"
						className="disco-w-full"
						placeholder={ __( 'Discount Label', 'disco' ) }
					/>
					{ discountIntent === 'Bundle' && (
						<div className="disco-flex disco-items-center -disco-mb-0.5 disco-gap-1">
							<input
								className=""
								checked={
									rule.recursive === 'yes' ? true : false
								}
								name="recursive"
								onChange={ handleRecursiveChange }
								id={ rule.id }
								type="checkbox"
							/>
							<label
								className="disco-text-sm disco-select-none disco-block disco-text-gray-500 disco-mb-1"
								htmlFor={ rule.id }
							>
								{ __( 'Recursive', 'disco' ) }
							</label>
						</div>
					) }

					{ index !== 0 ? (
						<button
							onClick={ () => handleRuleDelete( rule.id ) }
							className="disco-shrink-0"
						>
							<TrashIcon className="disco-h-4 disco-w-4 hover:disco-text-red-500 disco-transition-colors" />
						</button>
					) : (
						<div className="disco-shrink-0 disco-w-4"></div>
					) }
				</div>
			</div>
		</div>
	);
};
export default BulkOrBundleItem;
