import { Dialog, Tab, Transition } from '@headlessui/react';
import { Fragment } from 'react';
import Input from '../../../../../../components/Input';

import { useDispatch, useSelector } from 'react-redux';
import { updateBadge } from '../../../../../../features/discount/discountSlice';
import ColorPicker from '../../ColorPicker';
const ProductBadgeModal = ( { open, setOpen } ) => {
	const dispatch = useDispatch();
	const { design_blocks } = useSelector( ( state ) => state.discount );
	const handleBadgeTextChange = ( e ) => {
		const { value, name } = e.target;
		dispatch( updateBadge( { name, value } ) );
	};

	const handleTextColorChange = ( colors ) => {
		dispatch( updateBadge( { name: 'text_color', value: colors.hex } ) );
	};
	const handleBgColorChange = ( colors ) => {
		dispatch( updateBadge( { name: 'bg_color', value: colors.hex } ) );
	};
	return (
		<Transition.Root show={ open } as={ Fragment }>
			<Dialog
				as="div"
				className="disco-relative disco-z-30"
				onClose={ setOpen }
			>
				<Transition.Child
					as={ Fragment }
					enter="disco-ease-out disco-duration-300"
					enterFrom="disco-opacity-0"
					enterTo="disco-opacity-100"
					leave="disco-ease-in disco-duration-200"
					leaveFrom="disco-opacity-100"
					leaveTo="disco-opacity-0"
				>
					<div className="disco-fixed disco-inset-0 disco-bg-gray-500 disco-bg-opacity-50 disco-transition-opacity" />
				</Transition.Child>

				<div className="disco-fixed disco-inset-0 disco-z-10 disco-w-screen disco-overflow-y-auto">
					<div className="disco-flex disco-min-h-full disco-items-end disco-justify-center disco-p-4 disco-text-center sm:disco-items-center sm:disco-p-0">
						<Transition.Child
							as={ Fragment }
							enter="disco-ease-out disco-duration-300"
							enterFrom="disco-opacity-0 disco-translate-y-4 sm:disco-translate-y-0 sm:disco-scale-95"
							enterTo="disco-opacity-100 disco-translate-y-0 sm:disco-scale-100"
							leave="disco-ease-in disco-duration-200"
							leaveFrom="disco-opacity-100 disco-translate-y-0 sm:disco-scale-100"
							leaveTo="disco-opacity-0 disco-translate-y-4 sm:disco-translate-y-0 sm:disco-scale-95"
						>
							<Dialog.Panel className="disco-relative disco-transform disco-overflow-hidden disco-rounded-lg disco-bg-white disco-px-2 disco-pb-2 disco-pt-3 disco-text-left disco-shadow disco-transition-all sm:disco-my-8 sm:disco-w-full sm:disco-max-w-sm sm:disco-p-6">
								<Tab.Group>
									<Tab.List className="disco-flex disco-justify-between disco-mb-3">
										<Tab as={ Fragment }>
											{ ( { selected } ) => (
												<button
													className={ `disco-grow disco-text-center disco-border-b disco-outline-none disco-text-base disco-font-medium disco-py-2 ${
														selected
															? 'disco-bg-primary disco-border-b-primary disco-text-white'
															: 'disco-bg-white disco-border-b-gray-200 disco-text-gray-500'
													}` }
												>
													Text
												</button>
											) }
										</Tab>
										<Tab as={ Fragment }>
											{ ( { selected } ) => (
												<button
													className={ `disco-grow disco-text-center disco-border-b disco-outline-none disco-text-base disco-font-medium disco-py-2 ${
														selected
															? 'disco-bg-primary disco-border-b-primary disco-text-white'
															: 'disco-bg-white disco-border-b-gray-200 disco-text-gray-500'
													}` }
												>
													Background
												</button>
											) }
										</Tab>
									</Tab.List>
									<Tab.Panels>
										<Tab.Panel>
											<div className="disco-flex disco-flex-col disco-mb-4">
												<label className="disco-mb-1 disco-font-medium disco-text-base">
													Edit Text
												</label>
												<Input
													onChange={
														handleBadgeTextChange
													}
													value={
														design_blocks?.badge
															?.text
													}
													name="text"
													className="!disco-ps-2"
													placeholder="20% Off"
												/>
											</div>
											<div>
												<label className="disco-block disco-mb-1 disco-font-medium disco-text-base">
													Text Color
												</label>
												<ColorPicker
													onChange={
														handleTextColorChange
													}
													currentColor={
														design_blocks?.badge
															?.text_color
													}
												/>
											</div>
										</Tab.Panel>
										<Tab.Panel>
											<div className="disco-flex disco-flex-col disco-mb-4">
												<label className="disco-mb-1 disco-font-medium disco-text-base">
													Preview
												</label>
												<div
													style={ {
														color: design_blocks
															?.badge?.text_color,
														backgroundColor:
															design_blocks?.badge
																?.bg_color,
													} }
													className="disco-h-[42px] disco-rounded-md disco-flex disco-items-center disco-ps-4"
												>
													{
														design_blocks?.badge
															?.text
													}
												</div>
											</div>
											<div>
												<label className="disco-block disco-mb-1 disco-font-medium disco-text-base">
													Background Color
												</label>
												<ColorPicker
													onChange={
														handleBgColorChange
													}
													currentColor={
														design_blocks?.badge
															?.bg_color
													}
												/>
											</div>
										</Tab.Panel>
									</Tab.Panels>
								</Tab.Group>
							</Dialog.Panel>
						</Transition.Child>
					</div>
				</div>
			</Dialog>
		</Transition.Root>
	);
};
export default ProductBadgeModal;
