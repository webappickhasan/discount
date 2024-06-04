import { useDispatch } from 'react-redux';
import FooterButtons from '../../../../components/FooterButtons';
import { setTab } from '../../../../features/discount/discountSlice';
import ComponentContainer from './../../../../components/ComponentContainer';
import CartPage from './CartPage/CartPage';
import ProductBadges from './ProductBadges/ProductBadges';
import SingleProductPage from './SingleProductPage/SingleProductPage';
const DesignBlock = () => {
	const dispatch = useDispatch();
	const handleContinue = () => {
		dispatch( setTab( 2 ) );
	};

	const handleBack = () => {
		dispatch( setTab( 0 ) );
	};

	return (
		<>
			<div className="disco-mx-5">
				<ComponentContainer heading="Design Blocks">
					<ProductBadges />
					<SingleProductPage />
					<CartPage />
				</ComponentContainer>
			</div>
			<FooterButtons
				handleContinue={ handleContinue }
				handleBack={ handleBack }
			/>
		</>
	);
};
export default DesignBlock;
