import { CheckCircleIcon } from '@heroicons/react/24/solid';
import { useDispatch, useSelector } from 'react-redux';
import { updateBadge } from '../../../../../../features/discount/discountSlice';

const ProductBadgeCard = ( { badge } ) => {
	const dispatch = useDispatch();
	const { design_blocks } = useSelector( ( state ) => state.discount );
	const handleSelectBadgeType = ( badgeType ) => {
		dispatch( updateBadge( { name: 'badge_style', value: badgeType } ) );
	};

	let badgeContent = '';

	switch ( badge ) {
		case 'left_bar':
			badgeContent = (
				<div
					style={ {
						backgroundColor: design_blocks?.badge?.bg_color,
					} }
					className="disco-absolute disco-flex disco-justify-center disco-items-center disco-top-0 disco-bottom-0 disco-left-0 disco-w-7 disco-bg-primary"
				>
					<span
						style={ {
							color: design_blocks?.badge?.text_color,
							writingMode: 'tb-rl',
						} }
						className="disco-block disco-rotate-180 disco-text-sm disco-font-medium"
					>
						{ design_blocks?.badge?.text }
					</span>
				</div>
			);
			break;
		case 'top_left':
			badgeContent = (
				<div
					style={ {
						backgroundColor: design_blocks?.badge?.bg_color,
						border: `1px solid ${ design_blocks?.badge?.bg_color }`,
					} }
					className="disco-rounded disco-px-2.5 disco-py-1 disco-absolute disco-flex disco-justify-center disco-items-center disco-top-2 disco-left-2"
				>
					<span
						style={ {
							color: design_blocks?.badge?.text_color,
						} }
						className="disco-block disco-text-xs "
					>
						{ design_blocks?.badge?.text }
					</span>
				</div>
			);
			break;
		case 'top_left_border':
			badgeContent = (
				<div
					style={ {
						backgroundColor: design_blocks?.badge?.bg_color,
						border: `1px solid ${ design_blocks?.badge?.text_color }`,
					} }
					className="disco-rounded disco-px-2.5 disco-py-1 disco-absolute disco-flex disco-justify-center disco-items-center disco-top-2 disco-left-2"
				>
					<span
						style={ {
							color: design_blocks?.badge?.text_color,
						} }
						className="disco-block disco-text-xs "
					>
						{ design_blocks?.badge?.text }
					</span>
				</div>
			);
			break;
		case 'top_left_angle':
			badgeContent = (
				<div
					style={ {
						backgroundColor: design_blocks?.badge?.bg_color,
					} }
					className="-disco-rotate-45 disco-w-24 disco-rounded disco-px-2.5 disco-py-1 disco-absolute disco-flex disco-justify-center disco-items-center disco-top-3 -disco-left-6"
				>
					<span
						style={ {
							color: design_blocks?.badge?.text_color,
						} }
						className="disco-block disco-text-xs"
					>
						{ design_blocks?.badge?.text }
					</span>
				</div>
			);
			break;

		default:
			break;
	}

	return (
		<div
			onClick={ () => handleSelectBadgeType( badge ) }
			className={ `disco-border disco-rounded-xl disco-p-2 ${
				design_blocks?.badge?.badge_style === badge
					? 'disco-border-primary'
					: 'disco-border-gray-200'
			}` }
		>
			<div className="disco-relative disco-rounded-lg disco-overflow-hidden">
				{ badgeContent }
				{ design_blocks?.badge?.badge_style === badge && (
					<div className="disco-absolute disco-top-2 disco-right-2">
						<CheckCircleIcon className="disco-relative disco-z-10 disco-h-6 disco-w-6 disco-text-primary" />
						<div className="disco-rounded-full disco-absolute disco-top-1 disco-left-1 disco-h-4 disco-w-4 disco-bg-white"></div>
					</div>
				) }
				<img
					className="disco-w-full disco-aspect-square disco-object-cover"
					src=""
				/>
			</div>
			<div className="disco-p-3 disco-pb-2">
				<p className="disco-text-base disco-font-medium disco-text-gray-500">
					Men's Black Shirt
				</p>
				<div className="disco-mt-1.5 disco-flex disco-justify-between disco-items-center">
					<div className="disco-flex disco-gap-2 disco-items-center">
						<span className="disco-text-lg disco-font-medium">
							$99
						</span>
						<span className="disco-text-sm disco-line-through">
							$129
						</span>
					</div>

					<button className="!disco-px-1.5 disco-text-xs !disco-py-1 disco-border disco-font-medium disco-text-primary disco-border-primary disco-rounded">
						Buy Now
					</button>
				</div>
			</div>
		</div>
	);
};
export default ProductBadgeCard;
