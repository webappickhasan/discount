import {
	Cog6ToothIcon,
	DocumentArrowDownIcon,
	PlusIcon,
} from '@heroicons/react/24/outline';
import { __ } from '@wordpress/i18n';
import { useEffect } from 'react';
import { useDispatch } from 'react-redux';
import { useNavigate } from 'react-router-dom';
import { toast } from 'react-toastify';
import Button from '../../../components/Button';
import LoadingSpinner from '../../../components/LoadingSpinner';
import { useAddCampaignMutation } from '../../../features/campaigns/campaignsApi';
import { reset } from '../../../features/discount/discountSlice';
import { prepareCampaignForRequest } from '../../../utilities/utilities';

const Header = () => {
	const navigate = useNavigate();
	const dispatch = useDispatch();
	const [
		addCampaign,
		{ isLoading: importing, isError: importFailed, isSuccess: imported },
	] = useAddCampaignMutation();

	const handleJSONUpload = ( e ) => {
		const file = e.target.files[ 0 ];

		if ( file ) {
			const fileName = file.name;
			const fileExtension = fileName.split( '.' ).pop().toLowerCase();
			if ( fileExtension !== 'disco' ) {
				toast.error(
					__( 'Please Select Valid Disco File', 'disco' )
				);
				return;
			}

			const reader = new FileReader();
			reader.onload = function async( event ) {
				try {
					const fileContent = event.target.result;
					const campaignData = JSON.parse( fileContent );
					const dataForRequest = prepareCampaignForRequest(
						campaignData,
						'Imported'
					);
					addCampaign( dataForRequest );
					e.target.value = '';
				} catch ( error ) {
					toast.error(
						__(
							'Please Try Again with Valid File',
							DISCO.TEXTDOMAIN
						)
					);
				}
			};

			reader.readAsText( file );
		}
	};

	const handleNewCampaign = () => {
		navigate( 'disco' );
		dispatch( reset() );
	};

	const handleNavigateToSettings = () => {
		navigate( 'settings' );
	};

	useEffect( () => {
		if ( importFailed ) {
			toast.error( __( 'Disco File Is Not Valid', 'disco' ) );
		}
	}, [ importFailed ] );

	useEffect( () => {
		if ( imported ) {
			toast.success(
				__( 'Discount Successfully Imported', 'disco' )
			);
		}
	}, [ imported ] );

	return (
		<div className="disco-flex disco-items-center disco-justify-between">
			<div className="disco-flex disco-items-center disco-gap-3">
				<h2 className="disco-text-2xl disco-font-medium">
					{ __( 'Discount Campaigns', 'disco' ) }
				</h2>
				<div className="disco-flex disco-gap-2 disco-items-center">
					<input
						placeholder="Select Disco File"
						onChange={ handleJSONUpload }
						id="disco_import_discount_json"
						type="file"
						accept=".disco"
						className="disco-hidden"
					/>
					<label
						className="disco-bg-gray-100 disco-border disco-flex disco-items-center disco-gap-2 disco-border-gray-200 disco-font-medium disco-text-sm disco-rounded disco-px-2.5 disco-ps-2 disco-py-1"
						htmlFor="disco_import_discount_json"
					>
						<DocumentArrowDownIcon className="disco-h-4 disco-w-4" />
						{ importing
							? __( 'Importing', 'disco' )
							: __( 'Import', 'disco' ) }
					</label>
					{ importing && <LoadingSpinner size={ 4 } /> }
				</div>
			</div>
			<div className="disco-flex disco-gap-4">
				<Button
					onClick={ handleNewCampaign }
					className="!disco-px-4 !disco-py-2 disco-text-sm"
					icon={ <PlusIcon className="disco-h-4 disco-w-4" /> }
				>
					{ __( 'Create a Discount', 'disco' ) }
				</Button>

				<Button
					onClick={ handleNavigateToSettings }
					type="secondary"
					className="!disco-px-2.5 !disco-py-2 disco-text-sm"
					icon={ <Cog6ToothIcon className="disco-h-4 disco-w-4" /> }
				>
					{ __( 'Settings', 'disco' ) }
				</Button>
			</div>
		</div>
	);
};
export default Header;
