import {
	PlusCircleIcon,
	TrashIcon,
	XMarkIcon,
} from '@heroicons/react/24/outline';
import { PlusIcon } from '@heroicons/react/24/solid';
import { useEffect } from '@wordpress/element';
import { useDispatch, useSelector } from 'react-redux';
import Button from '../../../../../../../components/Button';
import LoadingSpinner from '../../../../../../../components/LoadingSpinner';
import { useGetFiltersQuery } from '../../../../../../../features/discount/discountApi';
import {
	addCondition,
	addConditionGroup,
	deleteCondition,
	deleteConditionGroup,
	updateConditionGroup,
	updateConditionValues,
} from '../../../../../../../features/discount/discountSlice';

import { __ } from '@wordpress/i18n';
import Condition from './Condition';
import SelectFilterDropdown from './FilterDropdown';
import OperatorRadio from './OperatorRadio';

const Conditions = () => {
	const { conditions: conditionsGroup } = useSelector(
		( state ) => state.discount
	);
	const dispatch = useDispatch();

	const { data: allFilters, isLoading } = useGetFiltersQuery();

	const handleAddConditionGroup = () => {
		dispatch( addConditionGroup() );
	};

	const handleAddCondition = ( conditionGroup ) => {
		dispatch( addCondition( conditionGroup.id ) );
	};

	const handleChangeGroupOperator = ( operator, conditionGroup ) => {
		dispatch(
			updateConditionGroup( {
				operator,
				id: conditionGroup.id,
			} )
		);
	};

	const handleOperatorChange = ( operator, filter, group ) => {
		dispatch(
			updateConditionValues( {
				values: { ...filter, operator },
				group_id: group.id,
			} )
		);
	};

	const handleConditionGroupDelete = ( conditionsGroup ) => {
		dispatch( deleteConditionGroup( conditionsGroup.id ) );
	};

	const handleConditionDelete = ( filter, conditionGroup ) => {
		dispatch(
			deleteCondition( {
				condition_id: filter.id,
				group_id: conditionGroup.id,
			} )
		);
	};

	useEffect( () => {
		window.scroll( {
			top: window.document.body.scrollHeight,
		} );
		console.log({conditionsGroup})
	}, [ conditionsGroup.length ] );

	if ( isLoading ) {
		return (
			<div className="disco-px-5 disco-border-t disco-mt-5 disco-pt-5">
				<LoadingSpinner />
			</div>
		);
	}

	return (
		<div className="disco-px-4 disco-pb-4">
			{ conditionsGroup.map( ( conditionGroup, index ) => {
				return (
					<div
						key={ conditionGroup.id }
						className="disco-relative disco-border first:disco-mt-5 disco-mt-12 disco-pt-2"
					>
						<button
							onClick={ () =>
								handleConditionGroupDelete( conditionGroup )
							}
							className="disco-absolute -disco-top-2.5 -disco-right-2.5 disco-bg-primary hover:disco-bg-primary-dark disco-transition-colors disco-text-white disco-rounded-full disco-flex disco-justify-center disco-items-center disco-h-5 disco-w-5"
						>
							<XMarkIcon className="disco-h-4 disco-w-4" />
						</button>
						<div className="disco-flex disco-justify-center">
							{ index !== 0 && (
								<div className="disco-relative -disco-mt-11">
									<OperatorRadio
										fontSize="disco-text-[11px]"
										value={ conditionGroup.base_operator }
										onChange={ ( operator ) =>
											handleChangeGroupOperator(
												operator,
												conditionGroup
											)
										}
									/>
									<div className="disco-absolute disco-ml-[5px] disco-z-[1] -disco-top-3 disco-h-12 disco-left-1/2 disco-border-dotted disco-border-r-2  disco-border-gray-400"></div>
								</div>
							) }
						</div>
						{ conditionGroup.base_filters.map(
							( filter, index ) => (
								<div key={ filter.id }>
									{ index !== 0 && (
										<div className="disco-mt-3 disco-px-5">
											<OperatorRadio
												value={ filter.operator }
												onChange={ ( operator ) =>
													handleOperatorChange(
														operator,
														filter,
														conditionGroup
													)
												}
											/>
										</div>
									) }

									<div className="disco-flex disco-gap-4 disco-mt-3 disco-px-5">
										<div className="">
											<SelectFilterDropdown
													allFilters={ allFilters?.values ?? [] }
												conditionGroup={
													conditionGroup
												}
												condition={ filter }
											/>
										</div>
										<div className="disco-w-full disco-flex disco-gap-4 disco-justify-between disco-items-center">
											<Condition
												condition={ filter }
												conditionGroup={
													conditionGroup
												}
												allFilters={ allFilters.values }
											/>

											<button
												onClick={ () =>
													handleConditionDelete(
														filter,
														conditionGroup
													)
												}
												className="disco-flex-shrink-0"
											>
												<TrashIcon className="disco-h-4 disco-w-4 hover:disco-text-red-500 disco-transition-colors" />
											</button>
										</div>
									</div>
								</div>
							)
						) }
						<div className="disco-border-t disco-px-5 disco-mt-3">
							<button
								onClick={ () =>
									handleAddCondition( conditionGroup )
								}
								className="disco-flex disco-items-center disco-gap-1.5 disco-font-semibold disco-text-sm disco-text-primary hover:disco-text-primary-dark disco-transition-colors disco-my-4"
							>
								<PlusIcon className="disco-w-4 disco-h-4 -disco-ml-0.5" />
								{ __(
									'Add Another Condition',
									DISCO.TEXTDOMAIN
								) }
							</button>
						</div>
					</div>
				);
			} ) }
			<div className=" disco-flex disco-justify-center disco-px-5 disco-pt-7">
				<Button
					className="!disco-px-6 !disco-py-2 !disco-font-normal"
					icon={ <PlusCircleIcon className="disco-h-5 disco-w-5" /> }
					onClick={ handleAddConditionGroup }
				>
					{ __( 'Add Condition', 'disco' ) }
				</Button>
			</div>
		</div>
	);
};
export default Conditions;
