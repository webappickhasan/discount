import { useSelector } from 'react-redux';
import Card from '../../../../../../components/Card';

import ChildElement from './components/ChildElement';
import ChoseProducts from './components/ChoseProducts';
import DiscountMethod from './components/DiscountMethod';
import FilterAllFewButtons from './components/FilterAllFewButtons';
import UserLimit from './components/UserLimit';
import ValidBetween from './components/ValidBetween';
const DiscountCard = () => {
	const { products } = useSelector( ( state ) => state.discount );

	return (
		<div className="disco-mx-5">
			<Card heading="Discount">
				<ChildElement heading="Method">
					<DiscountMethod />
				</ChildElement>

				<ChildElement heading="Filter Products">
					<FilterAllFewButtons />
				</ChildElement>

				{ products[ 0 ] !== 'all' && (
					<ChildElement>
						<ChoseProducts />
					</ChildElement>
				) }

				<ChildElement heading="User Limit">
					<UserLimit />
				</ChildElement>

				<ChildElement
					className="!disco-border-b-0"
					heading="Valid Between"
				>
					<ValidBetween />
				</ChildElement>
			</Card>
		</div>
	);
};
export default DiscountCard;
