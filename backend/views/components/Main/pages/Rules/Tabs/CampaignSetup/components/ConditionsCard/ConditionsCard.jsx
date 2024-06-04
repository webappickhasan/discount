import { PlusCircleIcon } from '@heroicons/react/24/outline';
import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import Button from '../../../../../../components/Button';
import Card from '../../../../../../components/Card';
import { addConditionGroup } from '../../../../../../features/discount/discountSlice';
import Conditions from './components/Conditions';

const ConditionsCard = () => {
	const dispatch = useDispatch();
	const { conditions } = useSelector( ( state ) => state.discount );

	const handleAddConditionGroup = () => {
		dispatch( addConditionGroup() );
	};

	return (
		<div className="disco-mx-5">
			<Card
				heading="Conditions"
				headingButton={
					<Button
						className="!disco-px-3 !disco-py-1.5  !disco-text-sm !disco-font-normal"
						onClick={ handleAddConditionGroup }
						icon={
							<PlusCircleIcon className="disco-h-5 disco-w-5" />
						}
					>
						{ __( 'Add Condition', 'disco' ) }
					</Button>
				}
			>
				{ conditions.length > 0 && <Conditions /> }
			</Card>
		</div>
	);
};
export default ConditionsCard;
