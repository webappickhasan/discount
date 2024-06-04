import { useDispatch } from 'react-redux';
import ComponentBox from '../../../../components/ComponentBox';
import ComponentContainer from '../../../../components/ComponentContainer';
import FooterButtons from '../../../../components/FooterButtons';
import { setTab } from '../../../../features/discount/discountSlice';
import CampaignSummary from './components/CampaignSummary';
import DiscountSummary from './components/DiscountSummary';
import FilterSummery from './components/FilterSummary';

const Summary = () => {
	const dispatch = useDispatch();
	const handleBack = () => {
		dispatch( setTab( 1 ) );
	};
	return (
		<>
			<ComponentContainer heading="Summary">
				<ComponentBox>
					<CampaignSummary />
					<FilterSummery />
					<DiscountSummary />
				</ComponentBox>
			</ComponentContainer>
			<FooterButtons next={ false } handleBack={ handleBack } />
		</>
	);
};
export default Summary;
