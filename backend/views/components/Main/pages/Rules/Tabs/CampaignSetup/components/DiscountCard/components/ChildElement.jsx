const ChildElement = ( { heading, children, className } ) => {
	return (
		<div
			className={ `disco-grid disco-items-center disco-grid-cols-12 disco-px-4 disco-py-2 disco-border-b disco-border-gray-200 ${ className }` }
		>
			<div className="disco-text-sm disco-text-gray-500 disco-font-medium disco-col-span-3">
				{ heading && <h4>{ heading }</h4> }
			</div>
			<div className="disco-col-span-9">{ children }</div>
		</div>
	);
};
export default ChildElement;
