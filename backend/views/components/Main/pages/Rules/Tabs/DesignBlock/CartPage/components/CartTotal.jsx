const CartTotal = () => {
	return (
		<div className="disco-col-span-1 disco-border disco-rounded-md disco-border-gray-200 disco-p-4">
			<div className="disco-w-4/5 disco-h-4 disco-rounded-full disco-bg-gray-200 disco-mb-3"></div>
			<div className="disco-border-t disco-border-b disco-border-gray-300 disco-p-4">
				<div className="disco-flex disco-gap-6">
					<div className="disco-h-3 disco-bg-gray-200 disco-rounded-full disco-grow"></div>
					<div className="disco-h-3 disco-bg-gray-200 disco-rounded-full disco-w-12"></div>
				</div>
				<div className="disco-flex disco-gap-6 disco-mt-3">
					<div className="disco-h-3 disco-bg-gray-200 disco-rounded-full disco-grow"></div>
					<div className="disco-h-3 disco-bg-gray-200 disco-rounded-full disco-w-12"></div>
				</div>
				<div className="disco-flex disco-gap-6 disco-mt-3">
					<div className="disco-h-3 disco-bg-gray-200 disco-rounded-full disco-grow"></div>
					<div className="disco-h-3 disco-bg-gray-200 disco-rounded-full disco-w-12"></div>
				</div>
			</div>
			<div className="disco-mt-1">
				<div className="disco-flex disco-gap-6 disco-items-center disco-px-4">
					<p className="disco-font-base disco-text-end disco-flex-grow">
						Cart Total:
					</p>
					<p className="disco-font-base disco-font-bold disco-w-12 disco-text-end">
						$100
					</p>
				</div>
				<div className="disco-mt-4">
					<p className="disco-text-center">
						<span>⚡ Summer Sale - </span>
						<span className="disco-font-bold">Your Save 10%</span>
					</p>
				</div>
			</div>
		</div>
	);
};
export default CartTotal;
