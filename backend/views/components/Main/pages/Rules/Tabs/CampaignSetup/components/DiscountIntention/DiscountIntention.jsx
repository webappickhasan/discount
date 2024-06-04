import { __ } from '@wordpress/i18n';
import ComponentBox from '../../../../../../components/ComponentBox';
import CampaignTypes from './components/IntentionTypes';

const DiscountIntention = () => {
	return (
		<div className="disco-mx-5">
			<ComponentBox className="disco-mt-8 disco-pb-9 ">
				<h3 className="disco-text-2xl disco-font-medium disco-text-center">
					{ __( 'Discount Intention', 'disco' ) }
				</h3>

				<div className="disco-mt-8 ">
					<CampaignTypes />
				</div>
			</ComponentBox>
		</div>
	);
};
export default DiscountIntention;
