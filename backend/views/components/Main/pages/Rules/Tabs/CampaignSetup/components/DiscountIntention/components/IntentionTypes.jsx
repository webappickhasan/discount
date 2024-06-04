import { useDispatch, useSelector } from 'react-redux';
import { useGetDiscountIntentsQuery } from '../../../../../../../features/discount/discountApi';
import { changeDiscountIntention } from '../../../../../../../features/discount/discountSlice';

const IntentionTypes = () => {
	const { discount_intent } = useSelector( ( state ) => state.discount );
	const { data: intentions, isLoading } = useGetDiscountIntentsQuery();
	const dispatch = useDispatch();

	const handleIntentChange = ( intention ) => {
		dispatch( changeDiscountIntention( intention ) );
	};

	if ( isLoading ) {
		return (
			<div className="disco-max-w-6xl disco-mx-auto disco-grid disco-grid-cols-4 disco-gap-5">
				{ Array.from( Array( 6 ).keys() ).map( ( item ) => (
					<div
						key={ item }
						className="disco-animate-pulse disco-border disco-border-gray-200 disco-bg-gray-300 disco-text-center disco-rounded-lg disco-flex disco-justify-center disco-py-3 disco-text-base disco-font-medium "
					>
						<div className="disco-animate-pulse disco-bg-gray-300 disco-rounded-full">
							<span className="disco-opacity-0 disco-px-3">
								Intention
							</span>
						</div>
					</div>
				) ) }
			</div>
		);
	}

	return (
		<div className="disco-max-w-6xl disco-mx-auto disco-grid disco-grid-cols-4 disco-gap-5">
			{ intentions?.values &&
				Object.keys( intentions?.values ).map( ( intention ) => (
					<button
						key={ intention }
						onClick={ () => handleIntentChange( intention ) }
						className={ ` disco-cursor-pointer disco-block disco-text-center disco-rounded-lg disco-border disco-border-primary/60 disco-py-3 disco-text-base disco-font-medium hover:disco-border-primary hover:disco-bg-primary hover:disco-text-white disco-transition-colors disco-outline-none ${
							intention === discount_intent
								? 'disco-bg-primary disco-border-primary disco-text-white'
								: ''
						} ` }
					>
						{ intentions?.values?.[ intention ] }
					</button>
				) ) }
		</div>
	);
};

export default IntentionTypes;
