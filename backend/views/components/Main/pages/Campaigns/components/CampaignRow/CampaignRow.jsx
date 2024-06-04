import { Bars3Icon, CheckIcon } from '@heroicons/react/24/outline';
import { useState } from 'react';

import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import { toggleCampaignSelect } from '../../../../features/campaigns/campaignSlice';
import { dateTimeFormatter } from '../../../../utilities/utilities';
import ActionMenu from './components/ActionMenu';
import DeleteConfirmation from './components/DeleteConfirmation';
import StatusToggler from './components/StatusToggler';
import TableCell from './components/TableCell';

const CampaignRow = ( { discountIntents, campaign, snapshot, provided } ) => {
	const dispatch = useDispatch();
	const navigate = useNavigate();
	const { campaign_ids } = useSelector( ( state ) => state.campaignState );

	const [ deleteModalOpen, setDeleteModalOpen ] = useState( false );

	const handleToggleCampaignSelect = ( id ) => {
		dispatch( toggleCampaignSelect( id ) );
	};

	const handleNavigateToEdit = () => {
		navigate( `disco?edit=${ campaign.id }`, { state: campaign } );
	};

	return (
		<tr
			className={ ` disco-w-full ${
				snapshot.isDragging
					? 'disco-shadow-md disco-bg-gray-50'
					: 'disco-bg-white'
			}` }
			ref={ provided.innerRef }
			{ ...provided.draggableProps }
		>
			<TableCell
				isDragging={ snapshot.isDragging }
				{ ...provided.dragHandleProps }
				className={ `disco-pr-3 disco-border-b disco-border-gray-100 disco-whitespace-nowrap` }
			>
				<Bars3Icon className="disco-w-6 disco-h-6" />
			</TableCell>
			<TableCell
				isDragging={ snapshot.isDragging }
				className=" disco-border-b disco-border-gray-100 disco-whitespace-nowrap disco-py-3 disco-pr-4 disco-text-sm "
			>
				<button
					onClick={ () => handleToggleCampaignSelect( campaign.id ) }
					className={ `disco-shrink-0 disco-h-4 disco-w-4 disco-rounded disco-border disco-flex disco-justify-center disco-items-center ${
						campaign_ids.includes( campaign.id )
							? 'disco-border-primary-dark'
							: 'disco-border-gray-500 '
					}` }
				>
					{ campaign_ids.includes( campaign.id ) && (
						<CheckIcon className="disco-text-primary-dark" />
					) }
				</button>
			</TableCell>
			<TableCell
				isDragging={ snapshot.isDragging }
				className="disco-align-top disco-border-b disco-border-gray-100 disco-whitespace-nowrap disco-py-3 disco-pr-4 disco-text-sm "
			>
				<div role="button" onClick={ handleNavigateToEdit }>
					<span className="disco-block disco-mtt-8 disco-font-bold disco-text-primary hover:disco-text-primary-dark disco-transition-colors">
						{ campaign.name }
					</span>
				</div>
				<span className="disco-block disco-mt-1 disco-text-[11px] disco-text-gray-400">
					{ __( 'Created By:', 'disco' ) }{ ' ' }
					{ campaign.created_by },{ ' ' }
					{ dateTimeFormatter( campaign.created_date ) }
				</span>
				<span className="disco-block disco-text-[11px] disco-text-gray-400">
					{ __( 'Modified By:', 'disco' ) }{ ' ' }
					{ campaign.modified_by },{ ' ' }
					{ dateTimeFormatter( campaign.modified_date ) }
				</span>
				<span className="disco-block disco-text-[11px] disco-text-gray-400">
					Priority: { campaign.priority }
				</span>
			</TableCell>
			<TableCell
				isDragging={ snapshot.isDragging }
				className=" disco-align-top disco-border-b disco-border-gray-100 disco-whitespace-nowrap disco-py-3 disco-pr-4 disco-text-sm disco-text-gray-900"
			>
				{ discountIntents.values[ campaign.discount_intent ] }
			</TableCell>
			<TableCell
				isDragging={ snapshot.isDragging }
				className=" disco-align-top disco-border-b disco-border-gray-100 disco-whitespace-nowrap disco-py-3 disco-pr-4 disco-text-sm disco-text-gray-900"
			>
				<span className="disco-capitalize">
					{ campaign.discount_method }{ ' ' }
				</span>
				{ campaign.discount_coupon && (
					<span>({ campaign.discount_coupon })</span>
				) }
			</TableCell>
			<TableCell
				isDragging={ snapshot.isDragging }
				className=" disco-align-top disco-border-b disco-border-gray-100 disco-whitespace-nowrap disco-py-3 disco-pr-4 disco-text-sm disco-text-gray-900"
			>
				{ campaign.discount_intent === 'Product' ||
				campaign.discount_intent === 'Cart' ? (
					<>
						<span className="disco-capitalize">
							{ campaign?.discount_rules[ 0 ]?.discount_type
								?.split( '_' )
								?.join( ' ' ) +
								' (' +
								campaign?.discount_rules[ 0 ]?.discount_value +
								')' }
						</span>
					</>
				) : (
					<span>Mixed</span>
				) }
			</TableCell>
			<TableCell
				isDragging={ snapshot.isDragging }
				className=" disco-align-top disco-border-b disco-border-gray-100 disco-whitespace-nowrap disco-py-3 disco-pr-4 disco-text-sm disco-text-gray-900"
			>
				<div className="disco-flex disco-flex-col">
					<div className="disco-flex disco-gap-1">
						<p className="disco-w-[66px]">
							{ ' ' }
							{ __( 'Start On:', 'disco' ) }{ ' ' }
						</p>
						<p>
							{ dateTimeFormatter(
								campaign.discount_valid_from
							) }
						</p>
					</div>
					<div className="disco-flex disco-gap-1">
						<p className="disco-w-[66px]">
							{ __( 'Expired On:', 'disco' ) }{ ' ' }
						</p>
						<p>
							{ dateTimeFormatter( campaign.discount_valid_to ) }
						</p>
					</div>
				</div>
			</TableCell>

			<TableCell
				isDragging={ snapshot.isDragging }
				className=" disco-align-top disco-border-b disco-border-gray-100 disco-whitespace-nowrap disco-py-3 disco-pr-4 disco-text-sm disco-text-gray-900"
			>
				<StatusToggler campaign={ campaign } />
			</TableCell>
			<TableCell
				isDragging={ snapshot.isDragging }
				className=" disco-align-top disco-border-b disco-border-gray-100 disco-whitespace-nowrap disco-py-3 disco-text-sm disco-text-gray-900"
			>
				<div className="disco-flex disco-items-center disco-gap-2">
					<ActionMenu
						campaign={ campaign }
						setDeleteModalOpen={ setDeleteModalOpen }
					/>
				</div>
			</TableCell>
			<DeleteConfirmation
				deleteId={ campaign.id }
				open={ deleteModalOpen }
				setOpen={ setDeleteModalOpen }
			/>
		</tr>
	);
};
export default CampaignRow;
