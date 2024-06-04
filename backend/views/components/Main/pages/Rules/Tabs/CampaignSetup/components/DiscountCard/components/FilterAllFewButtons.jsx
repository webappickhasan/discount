import { useDispatch, useSelector } from 'react-redux';
import { useGetWhatGetsDiscountQuery } from '../../../../../../../features/discount/discountApi';
import { updateOption } from '../../../../../../../features/discount/discountSlice';

const FilterAllFewButtons = () => {
	const { data, isLoading, isError } = useGetWhatGetsDiscountQuery();
	const { products } = useSelector( ( state ) => state.discount );
	const dispatch = useDispatch();

	const handleChange = ( type ) => {
		let value = [ 'all' ];
		if ( type === 'products' ) {
			value = [];
		}
		dispatch(
			updateOption( {
				option: 'products',
				value,
			} )
		);
	};

	let selected = '';
	if ( products[ 0 ] === 'all' ) {
		selected = 'all_products';
	} else {
		selected = 'products';
	}

	const loadingHelper = [ 'All Products', 'Few Products' ];

	let content = '';
	if ( isLoading ) {
		content = (
			<>
				{ loadingHelper.map( ( item ) => (
					<div
						key={ item }
						className="disco-animate-pulse disco-border disco-border-gray-200 disco-bg-gray-300 disco-text-center disco-rounded-md disco-flex disco-justify-center disco-py-2 disco-px-10 disco-text-base disco-font-medium "
					>
						<div className="disco-animate-pulse disco-bg-gray-300 disco-rounded-full">
							<span className="disco-opacity-0">{ item }</span>
						</div>
					</div>
				) ) }
			</>
		);
	}

	if ( ! isLoading && ! isError ) {
		content = Object.keys( data.values ).map( ( type ) => (
			<button
				key={ type }
				onClick={ () => handleChange( type ) }
				className={ ` disco-cursor-pointer disco-block disco-text-center disco-rounded-md disco-border disco-border-primary hover:disco-bg-primary hover:disco-text-white active:disco-bg-primary-dark disco-py-2  disco-outline-none disco-px-10 disco-text-base disco-font-medium  ${
					type === selected ? 'disco-bg-primary disco-text-white' : ''
				} ` }
			>
				{ data.values[ type ] }
			</button>
		) );
	}
	return (
		<div>
			<div className="disco-flex disco-items-center disco-gap-6">
				{ content }
			</div>
		</div>
	);
};
export default FilterAllFewButtons;
