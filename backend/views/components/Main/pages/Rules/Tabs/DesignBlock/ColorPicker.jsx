import { useEffect, useState } from 'react';
import { CustomPicker } from 'react-color';
import { Hue, Saturation } from 'react-color/lib/components/common';
import tinycolor from 'tinycolor2';

const CustomSlider = () => {
	return (
		<div className="disco-h-4 disco-w-4 disco-shadow disco-border-2 disco-rounded-full disco-bg-transparent  -disco-translate-x-2" />
	);
};

const CustomPointer = () => {
	return (
		<div className="disco-w-4 disco-h-4 disco-shadow disco-border-2 disco-border-solid disco-border-white disco-rounded-full -disco-translate-x-2 -disco-translate-y-2" />
	);
};

const ColorPicker = ( props ) => {
	const [ hex, setHex ] = useState( props.currentColor || '#000000' );

	const [ colorState, setColorState ] = useState( {
		hsl: {
			h: 0,
			s: 0,
			l: 0,
		},
		hsv: {
			h: 0,
			s: 0,
			v: 0,
		},
	} );

	useEffect( () => {
		const color = tinycolor( hex );
		const newColorState = {
			hsv: color.toHsv(),
			hsl: color.toHsl(),
			hex: color.toHex(),
		};
		setColorState( newColorState );
	}, [ hex ] );

	const handleHueChange = ( hue ) => {
		const color = tinycolor( hue );
		props.onChange( color.toHex() );
		setHex( '#' + color.toHex() );
	};

	const handleSaturationChange = ( hsv ) => {
		const color = tinycolor( hsv );
		props.onChange( color.toHex() );
		setHex( '#' + color.toHex() );
	};

	const displayColorSwatches = ( colors ) => {
		return colors.map( ( color ) => (
			<div
				onClick={ () => {
					props.onChange( color );
					setHex( '#' + color );
				} }
				key={ color }
				className="h-6 cursor-pointer"
				style={ { backgroundColor: '#' + color } }
			/>
		) );
	};

	const handleHexChange = ( e ) => {
		const regex = /^#[0-9a-f]*$/;

		if ( ! regex.test( e.target.value.toLowerCase() ) ) {
			return;
		}

		if ( e.target.value.length > 7 ) return;

		setHex( e.target.value );
		props.onChange( e.target.value );
	};

	return (
		<div className="disco-shadow-lg disco-rounded-md disco-border disco-flex disco-flex-col disco-p-3 disco-w-full">
			<div className="disco-w-full disco-h-16 disco-relative disco-overflow-hidden disco-mb-2">
				<Saturation
					hsl={ colorState.hsl }
					hsv={ colorState.hsv }
					pointer={ CustomPointer }
					onChange={ handleSaturationChange }
				/>
			</div>
			<div className="disco-h-4 disco-relative disco-mb-4">
				<Hue
					hsl={ colorState.hsl }
					hsv={ colorState.hsv }
					pointer={ CustomSlider }
					onChange={ handleHueChange }
					direction={ 'horizontal' }
				/>
			</div>
			<div className="disco-flex disco-items-center disco-mb-1">
				<input
					className="disco-border disco-p-1 disco-w-20  disco-text-sm disco-text-gray-500 disco-outline-none"
					type="text"
					value={ hex }
					onChange={ handleHexChange }
				/>
			</div>
			{ props.colors?.length && (
				<div className="disco-grid disco-grid-cols-8 disco-gap-1">
					{ displayColorSwatches( props.colors ) }
				</div>
			) }
		</div>
	);
};

export default CustomPicker( ColorPicker );
