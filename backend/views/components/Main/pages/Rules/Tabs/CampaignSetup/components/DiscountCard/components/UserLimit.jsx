import { __ } from '@wordpress/i18n';
import { useDispatch, useSelector } from 'react-redux';
import Input from '../../../../../../../components/Input';
import { updateOption } from '../../../../../../../features/discount/discountSlice';

const UserLimit = () => {
	const dispatch = useDispatch();
	const { discount_max_user } = useSelector( ( state ) => state.discount );

	const handleChange = ( e ) => {
		dispatch(
			updateOption( { option: e.target.name, value: e.target.value } )
		);
	};

	return (
		<Input
			onChange={ handleChange }
			name="discount_max_user"
			value={ discount_max_user === '0' ? '' : discount_max_user }
			type="number"
			className="disco-w-[170px] !disco-px-0.5 !disco-ps-2 !disco-py-0"
			placeholder={ __( 'Unlimited', 'disco' ) }
		/>
	);
};
export default UserLimit;
