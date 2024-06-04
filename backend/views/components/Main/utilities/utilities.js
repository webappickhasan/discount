import moment from 'moment';

export const getSelectedFilterData = ( items, current ) => {
	let value = {};
	items.forEach( ( group ) => {
		if ( group.options[ current ] ) {
			value = group.options[ current ];
		}
	} );

	return value;
};

export const dateTimeFormatter = ( datetime ) => {
	if ( datetime ) {
		return moment( datetime ).format( 'Do MMM, YYYY h:mm A' );
	} else {
		return '-';
	}
};

export const prepareCampaignForRequest = ( campaign, prefix = '' ) => {
	const dataForRequest = { ...campaign };

	if ( dataForRequest.id ) {
		delete dataForRequest.id;
	}
	if ( dataForRequest.created_by ) {
		delete dataForRequest.created_by;
	}
	if ( dataForRequest.created_date ) {
		delete dataForRequest.created_date;
	}
	if ( dataForRequest.modified_by ) {
		delete dataForRequest.modified_by;
	}
	if ( dataForRequest.modified_date ) {
		delete dataForRequest.modified_date;
	}
	if ( dataForRequest._links ) {
		delete dataForRequest._links;
	}
	if ( dataForRequest.name ) {
		dataForRequest.name = dataForRequest.name + ' - ' + prefix;
	}
	if ( dataForRequest.priority ) {
		dataForRequest.priority = '1';
	}

	return dataForRequest;
};
