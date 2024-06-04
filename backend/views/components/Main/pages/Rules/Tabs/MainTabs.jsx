import { Tab } from '@headlessui/react';
import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import { setTab } from '../../../features/discount/discountSlice';
import CampaignSetup from './CampaignSetup/CampaignSetup';
import Summary from './Summary/Summary';
import TabButton from './TabButton';

const MainTabs = () => {
	const tabsButtons = [
		{
			id: 1,
			text: 'Campaign Setup',
		},

		{
			id: 2,
			text: 'Design Blocks',
		},
		{
			id: 3,
			text: 'Summary',
		},
	];
	const { ui } = useSelector( ( state ) => state.discount );
	const dispatch = useDispatch();
	const handleTabChange = ( index ) => {
		dispatch( setTab( index ) );
	};

	return (
		<Tab.Group
			onChange={ handleTabChange }
			// selectedIndex={ 1 }
			selectedIndex={ ui[ 0 ] }
			manual
		>
			<Tab.List className="disco-flex disco-sticky disco-shadow-md disco-top-8 disco-z-20 disco-mx-5 disco-bg-grey-light">
				{ tabsButtons.map( ( button, index ) => (
					<TabButton disabled={ ui[ 1 ] < index }>
						{ button.text }
					</TabButton>
					// <TabButton key={ button.id }>{ button.text }</TabButton>
				) ) }
			</Tab.List>
			<div className="">
				<Tab.Panels>
					<Tab.Panel>
						<CampaignSetup />
					</Tab.Panel>
					<Tab.Panel>
						{/*<DesignBlock />*/}
						<h1>{__('Coming soon')}</h1>
					</Tab.Panel>
					<Tab.Panel>
						<Summary />
					</Tab.Panel>
				</Tab.Panels>
			</div>
		</Tab.Group>
	);
};
export default MainTabs;
