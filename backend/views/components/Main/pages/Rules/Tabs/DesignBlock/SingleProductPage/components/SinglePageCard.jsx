import { useDispatch, useSelector } from 'react-redux';
import { updateSinglePage } from '../../../../../../features/discount/discountSlice';

const SinglePageCard = ( { page } ) => {
	const { design_blocks } = useSelector( ( state ) => state.discount );
	const dispatch = useDispatch();
	const contain = (
		<div
			style={ {
				backgroundColor: design_blocks?.singlePage?.bg_color,
				color: design_blocks?.singlePage?.text_color,
			} }
			className="disco-text-center disco-rounded disco-px-4 disco-py-1 disco-text-sm"
		>
			{ design_blocks?.singlePage?.text }
		</div>
	);
	const handleSinglePageStyleChange = ( value ) => {
		dispatch( updateSinglePage( { name: 'single_page_style', value } ) );
	};
	return (
		<div
			className="disco-cursor-pointer"
			onClick={ () => handleSinglePageStyleChange( page.id ) }
		>
			<h4 className="disco-text-sm disco-text-gray-600 disco-font-medium">
				{ page?.title }
			</h4>
			<div
				className={ `disco-border disco-mt-2 ${
					design_blocks?.singlePage?.single_page_style === page.id
						? 'disco-border-primary'
						: 'disco-border-gray-200'
				} ` }
			>
				{ page?.id === 'center' && (
					<div className="disco-flex disco-justify-center disco-mt-3">
						{ contain }
					</div>
				) }
				<div className="disco-gap-4 disco-p-3 disco-grid disco-grid-cols-12">
					<div className="disco-col-span-5">
						{/*Image Placeholder*/}
					</div>
					<div className="disco-col-span-7 disco-flex disco-flex-col disco-justify-between">
						<div>
							<div className="disco-h-5 disco-w-full disco-bg-gray-200"></div>
							{ page?.id === 'below_title' && (
								<div className="disco-mt-3 disco-flex">
									{ contain }
								</div>
							) }
							<div className="disco-space-y-2 disco-mt-3">
								<div className="disco-h-2 disco-w-4/5 disco-bg-gray-200"></div>
								<div className="disco-h-2 disco-w-4/5 disco-bg-gray-200"></div>
								<div className="disco-h-2 disco-w-3/5 disco-bg-gray-200"></div>
							</div>
						</div>

						<div className="">
							<div className="disco-text-base disco-font-medium">
								$00.00
							</div>
							{ page?.id === 'before_add_cart' && (
								<div className="disco-my-3 disco-flex">
									{ contain }
								</div>
							) }
							<button className="!disco-block disco-mt-1 !disco-px-1.5 disco-text-xs !disco-py-1.5 disco-border disco-font-medium disco-text-primary disco-border-primary disco-w-full">
								Add to Cart
							</button>
							{ page?.id === 'after_add_cart' && (
								<div className="disco-mt-3 disco-flex">
									{ contain }
								</div>
							) }
						</div>
					</div>
				</div>
			</div>
		</div>
	);
};
export default SinglePageCard;
