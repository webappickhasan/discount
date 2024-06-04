import { PencilSquareIcon } from '@heroicons/react/24/solid';
import { useDispatch, useSelector } from 'react-redux';
import { setTab } from '../../../../../features/discount/discountSlice';

const FilterSummery = () => {
	const { products } = useSelector( ( state ) => state.discount );
	const dispatch = useDispatch();
	const handleNavigation = () => {
		dispatch( setTab( 2 ) );
	};
	return (
		<div className="disco-border-b disco-border-b-gray-200 disco-mt-4 disco-pb-4">
			<div className="disco-flex disco-items-center disco-justify-between">
				<h4 className="disco-font-medium disco-text-lg">
					Product Filter
				</h4>
				<button onClick={ handleNavigation }>
					<PencilSquareIcon className="disco-h-5 disco-w-5 disco-text-gray-500" />
				</button>
			</div>
			<div className="disco-mt-1">
				<p className="disco-text-sm">
					<span>Product:</span>{ ' ' }
					<span className="disco-font-medium">
						{ products[ 0 ] === 'all'
							? 'All Products'
							: `Total ${ products.length } Products` }
					</span>
				</p>
			</div>
		</div>
	);
};
export default FilterSummery;
