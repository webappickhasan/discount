import { __ } from '@wordpress/i18n';
import { Link } from 'react-router-dom';
import Button from './Button';
import SaveAndExitButton from './SaveAndExitButton';

const FooterButtons = ( {
	handleBack = () => {},
	handleContinue = () => {},
	next = true,
	cancel = false,
} ) => {
	return (
		<div className="disco-bg-white disco-shadow-[0_-3px_10px_5px_rgba(0,0,0,0.05)] disco-border-t disco-z-40 disco-border-gray-200 disco-py-2.5 disco-px-5 disco-flex disco-items-center disco-gap-3 disco-justify-end disco-mt-8 disco-sticky disco-bottom-0">
			{ cancel ? (
				<Link to="/">
					<Button
						className="!disco-py-1.5 !disco-px-4 !disco-font-normal"
						type="secondary"
					>
						{ __( 'Cancel', 'disco' ) }
					</Button>
				</Link>
			) : (
				<Button
					className="!disco-py-1.5 !disco-px-4 !disco-font-normal"
					onClick={ handleBack }
					type="secondary"
				>
					{ __( 'Back', 'disco' ) }
				</Button>
			) }

			<SaveAndExitButton />
			{/*{ next && (*/}
			{/*	<Button*/}
			{/*		className="!disco-py-1.5 !disco-px-4 !disco-font-normal"*/}
			{/*		type="secondary"*/}
			{/*		onClick={ handleContinue }*/}
			{/*	>*/}
			{/*		{ __( 'Continue', 'disco' ) }*/}
			{/*	</Button>*/}
			{/*) }*/}
		</div>
	);
};
export default FooterButtons;
