import { PencilSquareIcon } from '@heroicons/react/24/solid';
import { useDispatch, useSelector } from 'react-redux';
import { setTab } from '../../../../../features/discount/discountSlice';

const CampaignSummary = () => {
	const { name, discount_intent } = useSelector(
		( state ) => state.discount
	);

	const dispatch = useDispatch();
	const handleNavigation = () => {
		dispatch( setTab( 0 ) );
	};

	return (
		<div className="disco-border-b disco-border-b-gray-200 disco-pb-4">
			<div className="disco-flex disco-items-center disco-justify-between">
				<h4 className="disco-font-medium disco-text-lg">Campaign</h4>
				<button onClick={ handleNavigation }>
					<PencilSquareIcon className="disco-h-5 disco-w-5 disco-text-gray-500" />
				</button>
			</div>
			<div className="disco-mt-1">
				<p className="disco-text-sm">
					<span>Campaign Name:</span>{ ' ' }
					<span className="disco-font-medium">{ name }</span>
				</p>
				<p className="disco-text-sm">
					<span>Intent of Discount:</span>
					<span className="disco-font-medium">
						{ ' ' }
						{ discount_intent }
					</span>
				</p>
			</div>
		</div>
	);
};
export default CampaignSummary;
