import {
	MinusCircleIcon,
	PlusCircleIcon,
	TrashIcon,
} from '@heroicons/react/24/outline';
import { useDispatch, useSelector } from 'react-redux';
import { updateCartPage } from '../../../../../../features/discount/discountSlice';

const CartCard = ( { cart } ) => {
	const { design_blocks } = useSelector( ( state ) => state.discount );
	const dispatch = useDispatch();
	const content = (
		<p className="disco-font-bold">{ design_blocks?.cartPage?.text }</p>
	);

	const handleCartStyleChange = ( value ) => {
		dispatch( updateCartPage( { name: 'cart_style', value } ) );
	};

	return (
		<div
			onClick={ () => handleCartStyleChange( cart.id ) }
			className="disco-cursor-pointer"
		>
			<h4 className="disco-text-sm disco-font-medium disco-text-gray-500">
				{ cart.title }
			</h4>
			<div
				className={ `disco-border disco-mt-1 disco-flex disco-items-center disco-gap-4  disco-rounded-md disco-p-2 ${
					design_blocks?.cartPage?.cart_style === cart.id
						? 'disco-border-primary'
						: 'disco-border-gray-200'
				}` }
			>
				<div>
					{/*Image Placeholder*/}
				</div>
				<div className="disco-flex disco-items-center disco-flex-grow disco-justify-between">
					<div className="disco-grow">
						<div className="disco-mb-2">
							{ cart.id === 'before_title' && content }
						</div>
						{ cart.id === 'beside_title' ? (
							<div className="disco-flex disco-gap-4">
								<div className="disco-h-3 disco-grow disco-bg-gray-200 disco-rounded-full"></div>
								<div className="disco-mr-8 -disco-mt-1">
									{ content }
								</div>
							</div>
						) : (
							<div className="disco-h-3 disco-w-4/5 disco-bg-gray-200 disco-rounded-full"></div>
						) }

						<div className="disco-mt-2">
							{ cart.id === 'below_title' && content }
						</div>
						<div className="disco-h-2 disco-w-3/5 disco-mt-2 disco-bg-gray-200 disco-rounded-full"></div>
						<p className="disco-font-bold disco-mt-2">$75.00</p>
						<div className="disco-mt-1">
							{ cart.id === 'below_price' && content }
						</div>
					</div>
					<div className="disco-w-16 disco-shrink-0 disco-flex disco-items-center disco-gap-2">
						<MinusCircleIcon className="disco-h-5 disco-w-5 disco-text-gray-500" />
						<span className="disco-text-base disco-font-medium disco-text-gray-700">
							{ /* { Math.ceil( Math.random() * 4 ) } */ }1
						</span>
						<PlusCircleIcon className="disco-h-5 disco-w-5 disco-text-gray-500" />
					</div>

					<div className="disco-w-16 disco-shrink-0">
						<TrashIcon className="disco-ms-auto disco-mr-2 disco-w-5 disco-h-5" />
					</div>
				</div>
			</div>
		</div>
	);
};
export default CartCard;
