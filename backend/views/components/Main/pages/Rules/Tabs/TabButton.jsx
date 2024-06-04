import { Tab } from '@headlessui/react';
import { Fragment } from 'react';

const TabButton = ( { children, disabled } ) => {
	return (
		<Tab disabled={ disabled } as={ Fragment }>
			{ ( { selected } ) => (
				<button
					className={ `disco-box-border disco-pt-3.5 disco-pb-3 disco-w-56 disco-outline-none disco-text-base  ${
						selected
							? 'disco-bg-primary-light  disco-border-b-4 disco-border-b-primary'
							: ' disco-border-b-4 disco-border-b-transparent'
					} ${
						! disabled
							? 'disco-font-semibold disco-text-primary'
							: 'disco-text-gray-400'
					}` }
				>
					{ children }
				</button>
			) }
		</Tab>
	);
};
export default TabButton;
