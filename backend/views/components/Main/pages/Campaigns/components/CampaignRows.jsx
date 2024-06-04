import { useEffect, useState } from 'react';
import { DragDropContext, Draggable, Droppable } from 'react-beautiful-dnd';
import { usePatchCampaignMutation } from '../../../features/campaigns/campaignsApi';
import CampaignRow from './CampaignRow/CampaignRow';

const CampaignRows = ( { campaigns, discountIntents, searchTerm } ) => {
	const [ reOrderCampaigns, setReOrderCampaigns ] = useState( [] );

	const [ patchCampaign, { isLoading: priorityUpdating } ] =
		usePatchCampaignMutation();

	/**
	 * handle drag and drop priority update
	 * @param {object} result - drag and drop end result
	 * @returns {void}
	 */
	const handleDragEnd = ( result ) => {
		console.log( result );
		const items = Array.from( reOrderCampaigns );
		const [ reorderedItem ] = items.splice( result.source.index, 1 );
		items.splice( result.destination.index, 0, reorderedItem );

		setReOrderCampaigns( items );

		items.forEach( ( campaign, index ) => {
			if ( Number( campaign.priority ) !== items.length - index ) {
				patchCampaign( {
					id: campaign.id,
					data: { priority: String( items.length - index ) },
				} );
			}
		} );
	};

	useEffect( () => {
		setReOrderCampaigns( campaigns );
	}, [ campaigns ] );

	return (
		<DragDropContext onDragEnd={ handleDragEnd }>
			<Droppable droppableId="campaigns">
				{ ( provided ) => (
					<tbody
						{ ...provided.droppableProps }
						ref={ provided.innerRef }
					>
						{ reOrderCampaigns.map( ( campaign, index ) => (
							<Draggable
								isDragDisabled={
									priorityUpdating || Boolean( searchTerm )
								}
								key={ campaign.id }
								draggableId={ campaign.id }
								index={ index }
							>
								{ ( provided, snapshot ) => (
									<CampaignRow
										snapshot={ snapshot }
										provided={ provided }
										discountIntents={ discountIntents }
										campaign={ campaign }
									/>
								) }
							</Draggable>
						) ) }
						{ provided.placeholder }
					</tbody>
				) }
			</Droppable>
		</DragDropContext>
	);
};
export default CampaignRows;
