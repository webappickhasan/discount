const CampaignLoading = ( { line = 2 } ) => {
	const element = (
		<>
			<td className="disco-border-b disco-border-gray-100 disco-pb-7 disco-pt-6">
				<span className="disco-opacity-0 disco-block disco-h-5 disco-w-5 disco-bg-gray-300 disco-rounded-md"></span>
			</td>
			<td className="disco-border-b disco-border-gray-100 disco-pb-7 disco-pt-6 disco-pr-4">
				<span className="disco-block disco-h-5 disco-w-5 disco-bg-gray-300 disco-rounded-md disco-animate-pulse"></span>
			</td>
			<td className="disco-border-b disco-border-gray-100 disco-w-32 disco-pb-7 disco-pt-6 disco-pr-4">
				<span className="disco-block disco-h-5 disco-w-32 disco-bg-gray-300 disco-rounded-full disco-animate-pulse"></span>
			</td>
			<td className="disco-border-b disco-border-gray-100 disco-pb-7 disco-pt-6 disco-pr-4 ">
				<span className="disco-block disco-h-5 disco-w-full disco-bg-gray-300 disco-rounded-full disco-animate-pulse"></span>
			</td>
			<td className="disco-border-b disco-border-gray-100 disco-pb-7 disco-pt-6 disco-pr-4 ">
				<span className="disco-block disco-h-5 disco-w-full disco-bg-gray-300 disco-rounded-full disco-animate-pulse"></span>
			</td>
			<td className="disco-border-b disco-border-gray-100 disco-pb-7 disco-pt-6 disco-pr-4">
				<span className="disco-block disco-h-5 disco-w-full disco-bg-gray-300 disco-rounded-full disco-animate-pulse"></span>
			</td>
			<td className="disco-border-b disco-border-gray-100 disco-pb-7 disco-pt-6 disco-pr-4">
				<span className="disco-block disco-h-5 disco-min-w-[120px] disco-w-full disco-bg-gray-300 disco-rounded-full disco-animate-pulse"></span>
			</td>
			<td className="disco-border-b disco-border-gray-100 disco-pb-7 disco-pt-6 disco-pr-4">
				<span className="disco-block disco-h-5 disco-w-full disco-bg-gray-300 disco-rounded-full disco-animate-pulse"></span>
			</td>
			<td className="disco-border-b disco-border-gray-100 disco-pb-7 disco-pt-6">
				<span className="disco-block disco-h-5 disco-w-full disco-bg-gray-300 disco-rounded-full disco-animate-pulse"></span>
			</td>
		</>
	);
	const items = [];

	for ( let i = 0; i < line; i++ ) {
		items.push( i + 1 );
	}

	return (
		<>
			{ items.map( ( i ) => (
				<tr key={ i }>{ element }</tr>
			) ) }
		</>
	);
};
export default CampaignLoading;
