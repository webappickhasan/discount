const Input = ( {
	testid = '',
	className = '',
	placeholder = '',
	id = '',
	name = '',
	type = 'text',
	onChange = () => {},
	value = '',
} ) => {
	return (
		<input
			autoComplete="off"
			className={ ` !disco-rounded-md !disco-pe-1 !disco-bg-white !disco-ps-3 !disco-py-1 !disco-border-1 !disco-border-gray-200 !disco-shadow-none focus:!disco-border-primary  disco-text-base disco-outline-none ${ className }` }
			type={ type }
			name={ name }
			id={ id }
			value={ value }
			placeholder={ placeholder }
			onChange={ onChange }
			min={ 0 }
			data-testid={ testid }
		/>
	);
};
export default Input;
