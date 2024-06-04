const classNames = require( 'classnames' );

const Button = ( {
	disabled = false,
	className = '',
	children = '',
	type = 'primary',
	icon = '',
	onClick = () => {},
} ) => {
	const baseClassNames = classNames(
		'disco-text-base',
		'disco-border',
		'disco-rounded',
		'disco-px-8',
		'disco-py-2.5',
		'disco-flex',
		'disco-items-center',
		'disco-gap-2',
		'disco-outline-none',
		'disco-font-medium',
		'disco-transition-colors'
	);

	const getTypeClassNames = () => {
		switch ( type ) {
			case 'primary':
				return classNames(
					baseClassNames,
					'disco-text-white',
					'disco-bg-primary',
					'disco-border-primary',
					'hover:disco-bg-primary-dark',
					'hover:disco-border-primary-dark'
				);
			case 'secondary':
				return classNames(
					baseClassNames,
					'disco-text-grey-dark',
					'disco-bg-gray-100',
					'disco-border-gray-200',
					'hover:disco-bg-gray-200',
					'hover:disco-border-gray-300'
				);
			default:
				return baseClassNames;
		}
	};

	const buttonClasses = classNames( getTypeClassNames(), className );

	return (
		<button
			disabled={ disabled }
			onClick={ onClick }
			className={ buttonClasses }
		>
			{ icon && icon }
			{ children }
		</button>
	);
};

export default Button;
