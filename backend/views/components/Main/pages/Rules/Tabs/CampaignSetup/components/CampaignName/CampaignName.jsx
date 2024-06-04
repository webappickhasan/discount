import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import Input from '../../../../../../components/Input';
import { updateOption } from '../../../../../../features/discount/discountSlice';

const CampaignName = () => {
	const dispatch = useDispatch();
	const { name } = useSelector( ( state ) => state.discount );

	const handleNameChange = ( e ) => {
		dispatch( updateOption( { option: 'name', value: e.target.value } ) );
	};
	return (
		<div className="disco-flex disco-flex-col disco-items-center disco-space-y-4 disco-mt-10">
			<h3 className="disco-text-2xl disco-font-medium">
				{ __( 'Campaign Name', 'disco' ) }
			</h3>
			<Input
				name="campaign_name"
				value={ name }
				onChange={ handleNameChange }
				className="disco-w-80"
				placeholder="20% discount on all products"
			/>
		</div>
	);
};
export default CampaignName;
