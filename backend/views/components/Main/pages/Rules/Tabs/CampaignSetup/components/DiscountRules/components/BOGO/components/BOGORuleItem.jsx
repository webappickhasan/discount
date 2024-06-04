import { TrashIcon } from '@heroicons/react/24/outline';
import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import AsyncMultiSelect from '../../../../../../../../../components/AsyncMultiSelect';
import Input from '../../../../../../../../../components/Input';
import LoadingSpinner from '../../../../../../../../../components/LoadingSpinner';
import SingleSelect from '../../../../../../../../../components/SingleSelect';
import { useGetDiscountTypesQuery } from '../../../../../../../../../features/discount/discountApi';
import {
	deleteDiscountRule,
	updateDiscountRule,
} from '../../../../../../../../../features/discount/discountSlice';
import { useGetSearchItemQuery } from '../../../../../../../../../features/search/searchApi';

const BOGORuleItem = ( { rule, index } ) => {
	const { data: types, isLoading } = useGetDiscountTypesQuery();
	const { bogo_type } = useSelector( ( state ) => state.discount );
	const dispatch = useDispatch();

	const handleChange = ( e ) => {
		dispatch(
			updateDiscountRule( {
				...rule,
				[ e.target.name ]: e.target.value,
			} )
		);
	};

	const handleProductMultiSelect = ( selected ) => {
		dispatch(
			updateDiscountRule( {
				...rule,
				get_ids: selected,
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

	const handleTypeChange = ( active ) => {
		dispatch(
			updateDiscountRule( {
				...rule,
				discount_type: active,
			} )
		);
	};

	const handleRuleDelete = ( id ) => {
		dispatch( deleteDiscountRule( id ) );
	};

	if ( isLoading ) {
		return (
			<div>
				<LoadingSpinner />
			</div>
		);
	}

	return (
		<div className="disco-grid disco-grid-cols-12 disco-gap-4">
			<div className="disco-col-span-3 disco-border disco-p-3 disco-pt-2 disco-rounded-md disco-border-gray-200 disco-flex disco-gap-4">
				<div className="disco-grow flex-shrink-0">
					<label
						className="disco-text-sm disco-block disco-text-gray-500 disco-mb-1"
						htmlFor="minimum-quantity"
					>
						{ rule.recursive === 'yes'
							? __( 'Item Quantity', 'disco' )
							: __( 'Min Quantity', 'disco' ) }
					</label>
					<Input
						value={ rule.min }
						onChange={ handleChange }
						name="min"
						className="disco-w-full"
						placeholder={
							rule.recursive === 'yes'
								? __( 'Item Quantity', 'disco' )
								: __( 'Min', 'disco' )
						}
						type="number"
					/>
				</div>

				{ rule.recursive === 'no' && (
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
			</div>

			<div className="disco-col-span-9 disco-border disco-p-3 disco-pt-2 disco-rounded-md disco-border-gray-200 disco-flex disco-gap-4">
				{ bogo_type === 'products' && (
					<div className="disco-grow flex-shrink-0">
						<label
							className="disco-text-sm disco-block disco-text-gray-500 disco-mb-1"
							htmlFor="discount-value"
						>
							{ __( 'Select Products', 'disco' ) }
						</label>
						<AsyncMultiSelect
							widthClass="disco-w-60"
							placeHolder={ __(
								'Search Product',
								DISCO.TEXTDOMAIN
							) }
							endpoint="/search/product/?search="
							selected={ rule.get_ids }
							queryHook={ useGetSearchItemQuery }
							onChange={ handleProductMultiSelect }
						/>
					</div>
				) }

				{ bogo_type === 'categories' && (
					<div className="disco-grow flex-shrink-0">
						<label
							className="disco-text-sm disco-block disco-text-gray-500 disco-mb-1"
							htmlFor="discount-value"
						>
							{ __( 'Select Categories', 'disco' ) }
						</label>
						<AsyncMultiSelect
							widthClass="disco-w-60"
							placeHolder={ __(
								'Search Category',
								DISCO.TEXTDOMAIN
							) }
							endpoint="/search/category/?search="
							selected={ rule.get_ids }
							queryHook={ useGetSearchItemQuery }
							onChange={ handleProductMultiSelect }
						/>
					</div>
				) }

				<div className="disco-grow flex-shrink-0">
					<label
						className="disco-text-sm disco-block disco-text-gray-500 disco-mb-1"
						htmlFor="discount-value"
					>
						{ __( 'Get Quantity', 'disco' ) }
					</label>
					<Input
						value={ rule.get_quantity }
						onChange={ handleChange }
						name="get_quantity"
						className="disco-w-full"
						placeholder={ __( 'Get Quantity', 'disco' ) }
						type="number"
					/>
				</div>
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
						className="disco-opacity-0 disco-text-sm disco-block disco-text-gray-500 disco-mb-1"
						htmlFor="bulk-title"
					>
						{ __( 'Placeholder', 'disco' ) }
					</label>
					<div className="disco-flex disco-items-center disco-mt-2 disco-gap-4">
						<div className="disco-flex disco-items-center disco-mt-1.5 disco-gap-1">
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
						{ index !== 0 ? (
							<button
								onClick={ () => handleRuleDelete( rule.id ) }
								className=" disco-rounded-full disco-shrink-0 disco-items-center"
							>
								<TrashIcon className="disco-h-4 disco-w-4 hover:disco-text-red-500 disco-transition-colors" />
							</button>
						) : (
							<div className="disco-shrink-0 disco-w-4"></div>
						) }
					</div>
				</div>
			</div>
		</div>
	);
};
export default BOGORuleItem;
