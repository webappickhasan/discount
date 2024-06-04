import { XMarkIcon } from '@heroicons/react/24/outline';
import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import { removeProduct } from '../../../../../../../features/discount/discountSlice';
import SearchProduct from './SearchProduct';

const ChoseProducts = () => {
	const { products: selectedProducts } = useSelector(
		( state ) => state.discount
	);
	const dispatch = useDispatch();
	const handleRemoveProduct = ( product ) => {
		dispatch( removeProduct( product ) );
	};

	return (
		<div>
			<div className="disco-flex disco-gap-4">
				<SearchProduct />
				<div className="disco-border disco-flex disco-items-start disco-gap-2 disco-flex-wrap disco-border-gray-200 disco-min-h-[80px] disco-w-full disco-rounded-lg disco-p-3">
					{ selectedProducts.length > 0 ? (
						selectedProducts.map( ( product ) => (
							<div
								key={ product.id }
								className="disco-bg-gray-200 disco-rounded-sm disco-ps-2 disco-py-0.5 disco-flex disco-items-center"
							>
								<span>{ `${ product.id } - ${ product.name } ` }</span>
								<button
									className="disco-font-medium disco-select-none disco-p-1"
									onClick={ () =>
										handleRemoveProduct( product )
									}
								>
									<XMarkIcon className="disco-h-4 disco-w-4" />
								</button>
							</div>
						) )
					) : (
						<div>
							<p className="disco-text-gray-500">
								{ __(
									'Selected Product Will Appear Here.',
									DISCO.TEXTDOMAIN
								) }
							</p>
						</div>
					) }
				</div>
			</div>
		</div>
	);
};
export default ChoseProducts;
