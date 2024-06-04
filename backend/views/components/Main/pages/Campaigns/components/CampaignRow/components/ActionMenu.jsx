import { Menu, Transition } from '@headlessui/react';
import {
	ChevronDownIcon,
	DocumentArrowUpIcon,
	DocumentDuplicateIcon,
	PencilSquareIcon,
	TrashIcon,
} from '@heroicons/react/24/outline';

import { __ } from '@wordpress/i18n';
import { Fragment, useEffect, useRef } from 'react';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';
import LoadingSpinner from '../../../../../components/LoadingSpinner';
import { useAddCampaignMutation } from '../../../../../features/campaigns/campaignsApi';
import { prepareCampaignForRequest } from '../../../../../utilities/utilities';

function classNames( ...classes ) {
	return classes.filter( Boolean ).join( ' ' );
}

const ActionMenu = ( { setDeleteModalOpen, campaign } ) => {
	const navigate = useNavigate();
	const [ addCampaign, { isLoading, isSuccess } ] = useAddCampaignMutation();

	// campaign export functionality start
	const downloadLinkRef = useRef( null );
	const campaignJSON = JSON.stringify( campaign );

	const blob = new Blob( [ campaignJSON ], { type: 'application/json' } );
	const url = URL.createObjectURL( blob );
	const fileName = campaign.name.split( ' ' ).join( '_' ) + '.disco';
	// campaign export functionality end

	const handleAction = ( action ) => {
		switch ( action ) {
			case 'edit':
				navigate( `disco?edit=${ campaign.id }`, { state: campaign } );
				break;
			case 'duplicate':
				const dataForRequest = prepareCampaignForRequest(
					campaign,
					'Copy'
				);
				addCampaign( dataForRequest );
				break;
			case 'export':
				downloadLinkRef.current.click();
				break;
			case 'delete':
				setDeleteModalOpen( true );
				break;
			default:
				break;
		}
	};

	useEffect( () => {
		if ( isSuccess ) {
			toast.success(
				__( 'Campaign Successfully Duplicated.', 'disco' )
			);
		}
	}, [ isSuccess ] );

	return (
		<>
			<Menu
				as="div"
				className="disco-relative disco-inline-block disco-text-left"
			>
				<div>
					<Menu.Button className="disco-inline-flex disco-items-center disco-w-full disco-justify-center disco-gap-x-1.5 disco-rounded-md disco-bg-white disco-border disco-border-gray-200 disco-px-3 disco-py-2 disco-text-sm disco-font-semibold disco-text-gray-900 disco-shadow-sm disco-outline-none">
						{ __( 'Actions', 'disco' ) }
						<ChevronDownIcon
							className="-disco-mr-1 disco-h-[18px] disco-w-[18px] disco-text-gray-500"
							aria-hidden="true"
						/>
					</Menu.Button>
				</div>

				<Transition
					as={ Fragment }
					enter="disco-transition disco-ease-out disco-duration-100"
					enterFrom="disco-transform disco-opacity-0 disco-scale-95"
					enterTo="disco-transform disco-opacity-100 disco-scale-100"
					leave="disco-transition disco-ease-in disco-duration-75"
					leaveFrom="disco-transform disco-opacity-100 disco-scale-100"
					leaveTo="disco-transform disco-opacity-0 disco-scale-95"
				>
					<Menu.Items className="disco-absolute disco-right-0 disco-z-10 disco-mt-2 disco-w-36 disco-origin-top-right disco-divide-y disco-divide-gray-100 disco-rounded-md disco-bg-white disco-shadow-lg disco-border disco-border-gray-200 disco-outline-none">
						<div className="disco-py-1">
							<Menu.Item>
								{ ( { active } ) => (
									<button
										onClick={ () => handleAction( 'edit' ) }
										className={ classNames(
											active
												? 'disco-bg-gray-100 disco-text-gray-900'
												: 'disco-text-gray-700',
											'disco-group disco-flex disco-items-center disco-w-full disco-px-3 disco-py-1.5 disco-text-sm disco-font-normal'
										) }
									>
										<PencilSquareIcon
											className="disco-mr-2 disco-h-[18px] disco-w-[18px] disco-text-gray-500 group-hover:disco-text-gray-600"
											aria-hidden="true"
										/>
										{ __( 'Edit', 'disco' ) }
									</button>
								) }
							</Menu.Item>
							<Menu.Item>
								{ ( { active } ) => (
									<button
										onClick={ () =>
											handleAction( 'duplicate' )
										}
										className={ classNames(
											active
												? 'disco-bg-gray-100 disco-text-gray-900'
												: 'disco-text-gray-700',
											'disco-group disco-flex disco-items-center disco-w-full disco-px-3 disco-py-1.5 disco-text-sm disco-font-normal'
										) }
									>
										<DocumentDuplicateIcon
											className="disco-mr-2 disco-h-[18px] disco-w-[18px] disco-text-gray-500 group-hover:disco-text-gray-600"
											aria-hidden="true"
										/>
										{ __( 'Duplicate', 'disco' ) }
									</button>
								) }
							</Menu.Item>
						</div>
						<div className="disco-py-1">
							<Menu.Item>
								{ ( { active } ) => (
									<button
										onClick={ () =>
											handleAction( 'export' )
										}
										className={ classNames(
											active
												? 'disco-bg-gray-100 disco-text-gray-900'
												: 'disco-text-gray-700',
											'disco-group disco-flex disco-items-center disco-w-full disco-px-3 disco-py-1.5 disco-text-sm disco-font-normal'
										) }
									>
										<DocumentArrowUpIcon
											className="disco-mr-2 disco-h-[18px] disco-w-[18px] disco-text-gray-500 group-hover:disco-text-gray-600"
											aria-hidden="true"
										/>
										{ __( 'Export', 'disco' ) }
									</button>
								) }
							</Menu.Item>
						</div>
						<div className="disco-py-1">
							<Menu.Item>
								{ ( { active } ) => (
									<button
										onClick={ () =>
											handleAction( 'delete' )
										}
										className={ classNames(
											active
												? 'disco-bg-gray-100 disco-text-gray-900'
												: 'disco-text-gray-700',
											'disco-group disco-flex disco-items-center disco-w-full disco-px-3 disco-py-1.5 disco-text-sm disco-font-normal'
										) }
									>
										<TrashIcon
											className="disco-mr-2 disco-h-[18px] disco-w-[18px] disco-text-red-500 group-hover:disco-text-red-600"
											aria-hidden="true"
										/>
										{ __( 'Delete', 'disco' ) }
									</button>
								) }
							</Menu.Item>
						</div>
					</Menu.Items>
				</Transition>
			</Menu>

			<a
				ref={ downloadLinkRef }
				href={ url }
				download={ fileName }
				style={ { display: 'none' } }
			>
				{ __( 'Download', 'disco' ) }
			</a>
			{ isLoading ? (
				<LoadingSpinner size={ 6 } />
			) : (
				<div className="disco-h-6 disco-w-6"></div>
			) }
		</>
	);
};

export default ActionMenu;
