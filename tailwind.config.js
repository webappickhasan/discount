/** @type {Plugin} */
const plugin = require( 'tailwindcss/plugin' );
module.exports = {
	content: [ './backend/views/components/**/*.{js,jsx,ts,tsx}' ],
	theme: {
		extend: {
			colors: {
				primary: {
					light: '#D5DEFD',
					DEFAULT: '#3056D3',
					dark: '#0a38cc',
				},
				grey: {
					light: '#F6F6F6',
					DEFAULT: '#DFDFDF',
					dark: '#4A4A4A',
				},
			},
		},
	},
	plugins: [],
	prefix: 'disco-',
};
