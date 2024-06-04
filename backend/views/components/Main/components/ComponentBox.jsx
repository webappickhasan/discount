// @ts-ignore
const ComponentBox = ( { children, className = '' } ) => {
	return (
		<div
			className={ `disco-w-full disco-border disco-border-gray-200 disco-bg-white disco-p-7 ${ className }` }
		>
			{ children }
		</div>
	);
};
export default ComponentBox;
