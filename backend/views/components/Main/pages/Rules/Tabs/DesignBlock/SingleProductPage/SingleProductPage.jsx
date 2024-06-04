import { Switch } from '@headlessui/react';
import { useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import Button from '../../../../../components/Button';
import { updateSinglePage } from '../../../../../features/discount/discountSlice';
import ComponentBox from './../../../../../components/ComponentBox';
import SinglePageCard from './components/SinglePageCard';
import SinglePageModal from './components/SinglePageModal';
const SingleProductPage = () => {
	const [ openColorPicker, setOpenColorPicker ] = useState( false );
	const dispatch = useDispatch();
	const { design_blocks } = useSelector( ( state ) => state.discount );
	const singlePage = [
		{
			id: 'below_title',
			title: 'Below WooCommerce Product Title',
		},
		{
			id: 'before_add_cart',
			title: 'Before Add to Cart',
		},
		{
			id: 'center',
			title: 'Center of WooCommerce Product Title & Picture',
		},
		{
			id: 'after_add_cart',
			title: 'Below WooCommerce Product Title',
		},
	];
	const handleColorPickerModal = () => {
		setOpenColorPicker( true );
	};

	const handleSinglePageStatus = ( status ) => {
		dispatch( updateSinglePage( { name: 'enable', value: status } ) );
	};

	return (
		<ComponentBox className="disco-mt-12">
			<div className="disco-flex disco-justify-between disco-items-center">
				<h3 className="disco-text-lg disco-font-medium">
					Single Product Page
				</h3>
				<div className="disco-flex disco-items-center disco-gap-5">
					<Button
						onClick={ handleColorPickerModal }
						className="!disco-py-1 !disco-text-sm !disco-px-4"
						type="secondary"
					>
						Edit
					</Button>

					<div className="disco-flex disco-items-center disco-gap-3">
						<p>Enable</p>

						<Switch
							checked={ design_blocks?.singlePage?.enable }
							onChange={ handleSinglePageStatus }
							className={ `${
								design_blocks?.singlePage?.enable
									? 'disco-bg-primary'
									: 'disco-bg-gray-200'
							}
											disco-relative disco-inline-flex disco-h-5 disco-w-9 disco-flex-shrink-0 disco-cursor-pointer disco-rounded-full disco-border-2 disco-border-transparent disco-transition-colors disco-duration-200 disco-ease-in-out focus:disco-outline-none 
										` }
						>
							<span
								aria-hidden="true"
								className={ ` ${
									design_blocks?.singlePage?.enable
										? 'disco-translate-x-4'
										: 'disco-translate-x-0'
								}
												 disco-pointer-events-none disco-inline-block disco-h-4 disco-w-4 disco-transform disco-rounded-full disco-bg-white disco-shadow disco-ring-0 disco-transition disco-duration-200 disco-ease-in-out
											` }
							/>
						</Switch>
					</div>
				</div>
			</div>
			<div className="disco-grid disco-grid-cols-2 disco-gap-8 disco-mt-8">
				{ singlePage.map( ( page ) => (
					<SinglePageCard key={ page.id } page={ page } />
				) ) }
			</div>
			<SinglePageModal
				open={ openColorPicker }
				setOpen={ setOpenColorPicker }
			/>
		</ComponentBox>
	);
};
export default SingleProductPage;
