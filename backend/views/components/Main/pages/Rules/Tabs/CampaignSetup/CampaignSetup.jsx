import { useDispatch, useSelector } from 'react-redux';
import { toast } from 'react-toastify';
import FooterButtons from '../../../../components/FooterButtons';
import { setTab } from '../../../../features/discount/discountSlice';
import CampaignName from './components/CampaignName/CampaignName';
import ConditionsCard from './components/ConditionsCard/ConditionsCard';
import DiscountCard from './components/DiscountCard/DiscountCard';
import DiscountIntention from './components/DiscountIntention/DiscountIntention';
import DiscountRules from './components/DiscountRules/DiscountRules';

const CampaignSetup = () => {
	const dispatch = useDispatch();
	const { name } = useSelector( ( state ) => state.discount );

	const handleNextPage = () => {
		if ( name.trim().length === 0 ) {
			toast.error( 'Campaign Name is Required' );
			return;
		}
		dispatch( setTab( 1 ) );
	};

	return (
		<>
			<CampaignName />
			<DiscountIntention />
			<DiscountCard />
			<DiscountRules />
			<ConditionsCard />
			<FooterButtons cancel handleContinue={ handleNextPage } />
		</>
	);
};
export default CampaignSetup;
