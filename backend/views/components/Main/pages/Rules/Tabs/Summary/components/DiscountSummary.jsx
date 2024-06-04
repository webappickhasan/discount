import { PencilSquareIcon } from '@heroicons/react/24/solid';
import { useDispatch, useSelector } from 'react-redux';
import { setTab } from '../../../../../features/discount/discountSlice';
import { dateTimeFormatter } from '../../../../../utilities/utilities';

const DiscountSummary = () => {
	const {
		discount_method,
		discount_coupon,
		discount_type,
		discount_value,
		discount_valid_from,
		discount_valid_to,
	} = useSelector( ( state ) => state.discount );

	const dispatch = useDispatch();
	const handleNavigation = () => {
		dispatch( setTab( 1 ) );
	};

	return (
		<div className=" disco-mt-4 ">
			<div className="disco-flex disco-items-center disco-justify-between">
				<h4 className="disco-font-medium disco-text-lg">Discount</h4>
				<button onClick={ handleNavigation }>
					<PencilSquareIcon className="disco-h-5 disco-w-5 disco-text-gray-500" />
				</button>
			</div>
			<div className="disco-mt-1">
				<p className="disco-text-sm">
					<span>Discount Method:</span>{ ' ' }
					<span className="disco-font-medium">
						<span className="disco-capitalize">
							{ discount_method } Discount
						</span>
						<span>
							{ discount_method === 'coupon' && discount_coupon }
						</span>
					</span>
				</p>
				<p className="disco-text-sm">
					<span>Discount Type:</span>{ ' ' }
					<span className="disco-font-medium">
						<span className="disco-capitalize">
							{ discount_type?.split( '_' )?.join( ' ' ) }
						</span>
						<span>{ discount_value && ', ' + discount_value }</span>
					</span>
				</p>
				<p className="disco-text-sm">
					<span>Start:</span>{ ' ' }
					<span className="disco-font-medium">
						{ dateTimeFormatter( discount_valid_from ) }
					</span>
				</p>
				<p className="disco-text-sm">
					<span>End:</span>{ ' ' }
					<span className="disco-font-medium">
						{ dateTimeFormatter( discount_valid_to ) }
					</span>
				</p>
			</div>
		</div>
	);
};
export default DiscountSummary;
