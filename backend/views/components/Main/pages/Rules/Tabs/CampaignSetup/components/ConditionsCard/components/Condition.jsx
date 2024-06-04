import { ArrowsRightLeftIcon } from '@heroicons/react/24/outline';
import { __ } from '@wordpress/i18n';
import { useDispatch } from 'react-redux';
import AsyncMultiSelect from '../../../../../../../components/AsyncMultiSelect';
import Input from '../../../../../../../components/Input';
import MultiSelect from '../../../../../../../components/MultiSelect';
import SingleSelect from '../../../../../../../components/SingleSelect';
import { updateConditionValues } from '../../../../../../../features/discount/discountSlice';
import { useGetSearchItemQuery } from '../../../../../../../features/search/searchApi';
import { getSelectedFilterData } from '../../../../../../../utilities/utilities';

const Condition = ( { allFilters, condition, conditionGroup } ) => {
	const filterData = getSelectedFilterData(
		allFilters,
		condition.compare_with
	);
	const selectedCondition = condition?.condition;
	const selectedCompare = condition?.compare;
	const dispatch = useDispatch();

	const handleConditionSelectValueChange = ( active ) => {
		if ( active === 'between' ) {
			dispatch(
				updateConditionValues( {
					values: {
						...condition,
						condition: active,
						compare: [ '', '' ],
					},
					group_id: conditionGroup.id,
				} )
			);
		} else {
			dispatch(
				updateConditionValues( {
					values: { ...condition, condition: active },
					group_id: conditionGroup.id,
				} )
			);
		}
	};

	const handleCompareValueChange = ( active ) => {
		dispatch(
			updateConditionValues( {
				values: { ...condition, compare: active },
				group_id: conditionGroup.id,
			} )
		);
	};

	const handleBetweenValueChange = ( e, index ) => {
		const compare = [ ...condition.compare ];
		compare[ index ] = e.target.value;

		dispatch(
			updateConditionValues( {
				values: { ...condition, compare },
				group_id: conditionGroup.id,
			} )
		);
	};

	const handleMultiCompareValueChange = ( active ) => {
		dispatch(
			updateConditionValues( {
				values: { ...condition, compare: active },
				group_id: conditionGroup.id,
			} )
		);
	};

	const handleValueChange = ( e ) => {
		dispatch(
			updateConditionValues( {
				values: { ...condition, compare: e.target.value },
				group_id: conditionGroup.id,
			} )
		);
	};
	return (
		<div className="disco-flex disco-gap-4">
			{ filterData.condition && (
				<div>
					<SingleSelect
						className="disco-min-w-[210px]"
						placeHolder={ __(
							'Select Condition',
							DISCO.TEXTDOMAIN
						) }
						selected={ selectedCondition }
						items={ filterData.condition }
						onchange={ handleConditionSelectValueChange }
					/>
				</div>
			) }
			{ filterData?.input_type?.hasOwnProperty( 'type' ) ? (
				<>
					<>
						{ filterData.input_type.option_type == 'manual' && (
							<>
								{ filterData.input_type.multiple ? (
									<div>
										<MultiSelect
											placeHolder={ __(
												'Select Value',
												DISCO.TEXTDOMAIN
											) }
											selected={ selectedCompare }
											items={
												filterData.input_type.options
											}
											onChange={
												handleMultiCompareValueChange
											}
										/>
									</div>
								) : (
									<SingleSelect
										placeHolder={ __(
											'Select Value',
											DISCO.TEXTDOMAIN
										) }
										selected={ selectedCompare }
										items={ filterData.input_type.options }
										onchange={ handleCompareValueChange }
									/>
								) }
							</>
						) }
					</>
					<>
						{ filterData.input_type.option_type == 'api' && (
							<div>
								<AsyncMultiSelect
									placeHolder={ __(
										`Search ${
											filterData.title
												.split( ' ' )
												.slice( -1 )[ 0 ]
										}`,
										DISCO.TEXTDOMAIN
									) }
									endpoint={
										filterData?.input_type?.endpoint.split(
											'v1'
										)[ 1 ]
									}
									selected={ selectedCompare }
									queryHook={ useGetSearchItemQuery }
									onChange={ handleMultiCompareValueChange }
								/>
							</div>
						) }
					</>
				</>
			) : (
				<>
					{ condition.compare_with && (
						<>
							{ condition?.condition === 'between' ? (
								<div className="disco-flex disco-items-center disco-gap-3">
									<Input
										onChange={ ( e ) =>
											handleBetweenValueChange( e, 0 )
										}
										type={ filterData.input_type }
										value={ condition?.compare?.[ 0 ] }
										placeholder={ __(
											'From',
											DISCO.TEXTDOMAIN
										) }
									/>
									<div>
										<ArrowsRightLeftIcon className="disco-h-5 disco-w-5" />
									</div>
									<Input
										onChange={ ( e ) =>
											handleBetweenValueChange( e, 1 )
										}
										type={ filterData.input_type }
										value={ condition?.compare?.[ 1 ] }
										placeholder={ __(
											'To',
											DISCO.TEXTDOMAIN
										) }
									/>
								</div>
							) : (
								<Input
									onChange={ handleValueChange }
									type={ filterData.input_type }
									value={ condition?.compare }
									placeholder={ __(
										'Value',
										DISCO.TEXTDOMAIN
									) }
								/>
							) }
						</>
					) }
				</>
			) }
		</div>
	);
};
export default Condition;
