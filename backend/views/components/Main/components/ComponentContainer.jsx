import Heading from './Heading';

// @ts-ignore
const ComponentContainer = ( { heading, children, className = '' } ) => {
	return (
		<div className={ `disco-mt-8 ${ className }` }>
			<Heading className="disco-mb-4">{ heading }</Heading>
			{ children }
		</div>
	);
};
export default ComponentContainer;
