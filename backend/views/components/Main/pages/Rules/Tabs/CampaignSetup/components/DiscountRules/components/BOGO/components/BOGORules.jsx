import { PlusCircleIcon } from '@heroicons/react/24/outline';
import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import Button from '../../../../../../../../../components/Button';
import Card from '../../../../../../../../../components/Card';
import { addNewDiscountRule } from '../../../../../../../../../features/discount/discountSlice';
import BOGORuleItem from './BOGORuleItem';
const BOGORules = () => {
	const { discount_rules } = useSelector( ( state ) => state.discount );
	const dispatch = useDispatch();

	const handleAddBOGORule = () => {
		dispatch( addNewDiscountRule() );
	};

	return (
		<Card heading="BOGO Rules">
			<div className="disco-p-4">
				<div className="disco-grid disco-mb-4 disco-grid-cols-12 disco-gap-4">
					<h4 className="disco-col-span-3 disco-font-medium disco-text-lg">
						{ __( 'Customer Buy', 'disco' ) }
					</h4>
					<h4 className="disco-col-span-9 disco-font-medium disco-text-lg">
						{ __( 'Customer Get', 'disco' ) }
					</h4>
				</div>
				<div className="disco-space-y-4">
					{ discount_rules.map( ( rule, index ) => (
						<BOGORuleItem
							key={ rule.id }
							index={ index }
							rule={ rule }
						/>
					) ) }
				</div>
			</div>

			<div className="disco-flex disco-justify-between disco-border-t disco-border-gray-200 disco-items-center">
				<div className="disco-px-4 disco-py-2">
					<Button
						onClick={ handleAddBOGORule }
						className="!disco-px-3 !disco-py-1.5 !disco-text-sm !disco-font-normal"
						icon={
							<PlusCircleIcon className="disco-h-5 disco-w-5" />
						}
					>
						{ __( 'Add More', 'disco' ) }
					</Button>
				</div>
			</div>
		</Card>
	);
};
export default BOGORules;
