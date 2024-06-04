import { Listbox, Transition } from '@headlessui/react';
import { ChevronUpDownIcon } from '@heroicons/react/24/outline';
import { __ } from '@wordpress/i18n';
import { Fragment, useEffect, useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { updateConditionValues } from '../../../../../../../features/discount/discountSlice';
import { getSelectedFilterData } from '../../../../../../../utilities/utilities';

const SelectFilterDropdown = ( { allFilters, condition, conditionGroup } ) => {
	const filterData = getSelectedFilterData(
		allFilters,
		condition?.compare_with
	);

	const dispatch = useDispatch();
	const { discount_intent } = useSelector( ( state ) => state.discount );

	const [ sliceFrom, setSliceFrom ] = useState( 0 );

	const handleFilterChange = ( active ) => {
		const filterData = getSelectedFilterData( allFilters, active );

		const { compare, ...rest } = condition;
		dispatch(
			updateConditionValues( {
				values: {
					...rest,
					compare_with: active,
					condition: filterData?.condition
						? Object.keys( filterData.condition )[ 0 ]
						: '',
				},
				group_id: conditionGroup.id,
			} )
		);
	};

	useEffect( () => {
		if ( discount_intent === 'Cart' ) {
			setSliceFrom( 6 );
		} else {
			setSliceFrom( 0 );
		}
	}, [ discount_intent ] );

	return (
		<Listbox className="disco-w-[420px]" onChange={ handleFilterChange }>
			<div className="disco-relative">
				<Listbox.Button className=" disco-relative disco-cursor-pointer disco-w-full disco-rounded-md disco-border disco-text-base disco-border-gray-200 disco-py-2 disco-pl-3 disco-pr-10 disco-text-left focus:disco-outline-none">
					<span className="disco-block disco-truncate">
						{ filterData?.title
							? filterData?.title
							: __( 'Select Filter', 'disco' ) }
					</span>
					<span className="disco-pointer-events-none disco-absolute disco-inset-y-0 disco-right-0 disco-flex disco-items-center disco-pr-2">
						<ChevronUpDownIcon
							className="disco-h-5 disco-w-5 disco-text-gray-400"
							aria-hidden="true"
						/>
					</span>
				</Listbox.Button>
				<Transition
					as={ Fragment }
					leave="disco-transition disco-ease-in disco-duration-100"
					leaveFrom="disco-opacity-100"
					leaveTo="disco-opacity-0"
				>
					<Listbox.Options
						data-testid="filters-item"
						className="disco-z-50 disco-pt-2 disco-absolute disco-mt-1.5 disco-max-h-72 disco-w-full disco-overflow-auto disco-rounded-md disco-bg-white disco-text-base disco-shadow-lg disco-ring-1 disco-ring-black disco-ring-opacity-5 focus:disco-outline-none sm:disco-text-sm"
					>
						{ allFilters.slice( sliceFrom ).map( ( o, index ) => {
							return (
								<Fragment key={ index }>
									<Listbox.Option
										disabled
										className="disco-pl-4 disco-font-bold disco-mb-0"
									>
										{ o.optionGroup }
									</Listbox.Option>
									<>
										{ Object.keys( o.options ).map(
											( opt, index ) => (
												<Listbox.Option
													key={ index }
													value={ opt }
													className={ ( {
														active,
													} ) =>
														`disco-pl-8 disco-relative disco-py-1 disco-mb-0 disco-cursor-pointer ${
															active
																? 'disco-bg-grey'
																: 'disco-text-gray-900'
														} ${
															condition?.compare_with ===
															opt
																? 'disco-font-medium'
																: ''
														}`
													}
												>
													{ o.options[ opt ].title }
												</Listbox.Option>
											)
										) }
									</>
								</Fragment>
							);
						} ) }
					</Listbox.Options>
				</Transition>
			</div>
		</Listbox>
	);
};
export default SelectFilterDropdown;
