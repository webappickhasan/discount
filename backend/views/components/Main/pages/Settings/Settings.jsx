import { useEffect, useState } from 'react';
import { Link } from 'react-router-dom';
import { toast } from 'react-toastify';
import Button from '../../components/Button';
import ComponentBox from '../../components/ComponentBox';
import ComponentContainer from '../../components/ComponentContainer';
import Input from '../../components/Input';
import LoadingSpinner from '../../components/LoadingSpinner';
import {
	useGetSettingsQuery,
	useUpdateSettingsMutation,
} from '../../features/settings/settingsApi';
const Settings = () => {
	const { data, isLoading } = useGetSettingsQuery();
	const [
		updateSettings,
		{ isLoading: updateLoading, isSuccess: updateSuccess },
	] = useUpdateSettingsMutation();
	const [ settings, setSettings ] = useState( {} );

	useEffect( () => {
		if ( data ) {
			setSettings( data );
		}
	}, [ data ] );

	useEffect( () => {
		if ( updateSuccess ) {
			toast.success( 'Settings Updated' );
		}
	}, [ updateSuccess ] );

	if ( isLoading ) {
		return (
			<ComponentContainer heading="General Settings">
				<ComponentBox>
					<LoadingSpinner />
				</ComponentBox>
			</ComponentContainer>
		);
	}

	const handleSettingChange = ( e ) => {
		setSettings( ( prevState ) => ( {
			...prevState,
			[ e.target.name ]: e.target.value,
		} ) );
	};

	const handleSettingSave = () => {
		updateSettings( settings );
	};

	return (
		<div className="disco-mt-2.5 disco-mr-4 disco-ml-0.5 disco-flex disco-gap-4">
			<div className="disco-flex-grow disco-border disco-border-gray-200 disco-p-5">
				<ComponentContainer heading="General Settings">
					<ComponentBox>
						<div className="disco-space-y-4 disco-divide-y disco-divide-gray-100">
							{ Object.keys( settings ).map( ( setting ) => (
								<div className="disco-flex disco-pt-4 first:disco-pt-0 disco-items-center disco-justify-between">
									<h4 className="disco-capitalize disco-font-medium disco-grow  disco-text-base ">
										{ setting }
									</h4>
									<Input
										onChange={ handleSettingChange }
										name={ setting }
										value={ settings[ setting ] }
										placeholder="Value"
									/>
								</div>
							) ) }
						</div>
					</ComponentBox>
					<div className="disco-mt-8 disco-flex disco-justify-end disco-gap-4">
						<Link to="/">
							<Button type="secondary">Back</Button>
						</Link>
						<Button
							disabled={ updateLoading }
							onClick={ handleSettingSave }
						>
							<span>Save</span>
							{ updateLoading && <LoadingSpinner size={ 4 } /> }
						</Button>
					</div>
				</ComponentContainer>
			</div>
			{/*<div className="disco-min-w-[100px] disco-max-w-[300px] disco-bg-grey-light"></div>*/}
		</div>
	);
};
export default Settings;
