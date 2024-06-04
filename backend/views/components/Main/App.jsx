import { Provider } from 'react-redux';
import { HashRouter, Route, Routes } from 'react-router-dom';
import { ToastContainer } from 'react-toastify';
import 'react-toastify/dist/ReactToastify.css';
import { store } from './app/store';
import Campaigns from './pages/Campaigns/Campaigns';
import Rules from './pages/Rules/Rules';
import Settings from './pages/Settings/Settings';
export default function CreateDiscount() {
	return (
		<Provider store={ store }>
			<HashRouter basename="/">
				<Routes>
					<Route path="/" element={ <Campaigns /> } />
					<Route path="/settings" element={ <Settings /> } />
					<Route path="/disco" element={ <Rules /> } />
				</Routes>
			</HashRouter>
			<ToastContainer autoClose={ 1500 } />
			<div
				className="hover:disco-bg-primary-dark disco-transition-colors disco-text-[11px] hidden disco-w-96 hover:disco-bg-primary/90 disco-text-gray-500
					disco-bg-gray-100
					disco-border-gray-200"
			></div>
		</Provider>
	);
}
