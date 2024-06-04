import { Switch } from '@headlessui/react';
import { useState } from 'react';
import { useDispatch, useSelector } from 'react-redux';
import { updateBadge } from '../../../../../features/discount/discountSlice';
import Button from './../../../../../components/Button';
import ComponentBox from './../../../../../components/ComponentBox';
import ProductBadgeCard from './components/ProductBadgeCard';
import ProductBadgeModal from './components/ProductBadgeModal';

const ProductBadges = () => {
	const [ openColorPicker, setOpenColorPicker ] = useState( false );
	const dispatch = useDispatch();
	const { design_blocks } = useSelector( ( state ) => state.discount );
	const productBadges = [
		'left_bar',
		'top_left',
		'top_left_border',
		'top_left_angle',
	];
	const handleColorPickerModal = () => {
		setOpenColorPicker( true );
	};

	const handleBadgeStatus = ( status ) => {
		dispatch( updateBadge( { name: 'enable', value: status } ) );
	};

	return (
		<ComponentBox>
			<div className="disco-flex disco-justify-between disco-items-center">
				<h3 className="disco-text-lg disco-font-medium">
					Product Badges
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
							checked={ design_blocks?.badge?.enable }
							onChange={ handleBadgeStatus }
							className={ `${
								design_blocks?.badge?.enable
									? 'disco-bg-primary'
									: 'disco-bg-gray-200'
							}
											disco-relative disco-inline-flex disco-h-5 disco-w-9 disco-flex-shrink-0 disco-cursor-pointer disco-rounded-full disco-border-2 disco-border-transparent disco-transition-colors disco-duration-200 disco-ease-in-out focus:disco-outline-none 
										` }
						>
							<span
								aria-hidden="true"
								className={ ` ${
									design_blocks?.badge?.enable
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
			<div className="disco-flex disco-justify-between disco-gap-4 disco-mt-8">
				{ productBadges.map( ( productBadge ) => (
					<ProductBadgeCard
						key={ productBadge }
						badge={ productBadge }
					/>
				) ) }
			</div>
			<ProductBadgeModal
				open={ openColorPicker }
				setOpen={ setOpenColorPicker }
			/>
		</ComponentBox>
	);
};
export default ProductBadges;
