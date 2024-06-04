import { Listbox, Transition } from '@headlessui/react';
import { ChevronUpDownIcon } from '@heroicons/react/24/outline';
import { __ } from '@wordpress/i18n';

const SingleSelect = ( {
	menu = false,
	placeholder = __( 'Select', 'disco' ),
	items,
	selected,
	onchange = () => {},
	disabled = false,
	className = '',
	buttonClass = '',
} ) => {
	return (
		<Listbox
			className={ className }
			disabled={ disabled }
			value={ selected }
			onChange={ onchange }
		>
			<div className="disco-relative">
				<Listbox.Button
					className={ `disco-relative disco-cursor-pointer disco-w-full disco-rounded-md disco-border disco-text-base disco-border-gray-200 disco-py-2 disco-pl-2.5 disco-pr-8 disco-text-left focus:disco-outline-none ${ buttonClass }` }
				>
					<span className="disco-block disco-truncate">
						{ items[ selected ] || placeholder }
					</span>
					<span className="disco-pointer-events-none disco-absolute disco-inset-y-0 disco-right-0 disco-flex disco-items-center disco-pr-0.5">
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
					<Listbox.Options className="disco-z-50 disco-absolute disco-cursor-pointer disco-mt-1.5 disco-max-h-60 disco-w-full disco-overflow-auto disco-rounded-md disco-bg-white disco-text-base disco-shadow-lg disco-ring-1 disco-ring-black disco-ring-opacity-5 focus:disco-outline-none sm:disco-text-sm">
						{ items &&
							Object.keys( items ).map( ( item ) => (
								<Listbox.Option
									key={ item }
									className={ ( { active } ) =>
										`disco-relative disco-py-1 disco-pl-4 disco-mb-0 hover:disco-bg-grey ${
											active && ! menu
												? 'disco-bg-grey'
												: 'disco-text-gray-900'
										}`
									}
									value={ item }
								>
									{ ( { selected } ) => (
										<>
											<span
												className={ `disco-block disco-truncate ${
													selected && ! menu
														? 'disco-font-medium'
														: 'disco-font-normal'
												}` }
											>
												{ items[ item ] }
											</span>
										</>
									) }
								</Listbox.Option>
							) ) }
					</Listbox.Options>
				</Transition>
			</div>
		</Listbox>
	);
};
export default SingleSelect;
