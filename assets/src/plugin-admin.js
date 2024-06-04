import { createRoot } from 'react-dom/client';
import App from '../../backend/views/components/Main/App';
import './styles/admin.scss';

const disco = document.getElementById( 'disco' );

const root = createRoot( disco );
root.render( <App /> );

/**
 * A void function.
 *
 * @param {jQuery} $ The jQuery object to be used in the function body
 */
( ( $ ) => {
	'use strict';
	$( () => {} );
	// Place your administration-specific JavaScript here
} )( jQuery );
