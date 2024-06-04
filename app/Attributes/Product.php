<?php
/**
 * Product Attributes
 *
 * @package    Disco
 * @subpackage \App\Attributes
 */

namespace Disco\App\Attributes;

/**
 * Class Product
 *
 * This class provides methods for retrieving various attributes of a WooCommerce product.
 *
 * @package    Disco
 * @subpackage \App\Attributes
 * @author     Ohidul Islam <wahid0003@gmail.com>
 * @link       https://webappick.com
 * @license    https://opensource.org/licenses/gpl-license.php GNU Public License
 * @category   Attributes
 */
class Product {

	/**
	 * @var \WC_Product $product Product Object.
	 */
	private $product;

	/**
	 * @var \WC_Product $parent Parent Product Object.
	 */
	private $parent;

	/**
	 * ProductInfo constructor.
	 *
	 * @param \WC_Product $product Product Object.
	 */
	public function __construct( $product ) {
		$this->product = $product;
		$this->parent  = $product;

		if ( $product->is_type( 'variation' ) ) {
			return;
		}

		$parent = wc_get_product( $product->get_parent_id() );

		if ( ! ( $parent instanceof \WC_Product ) ) {
			return;
		}

		$this->parent = $parent;
	}

	/**
	 * Get the ID of the product.
	 *
	 * @return int
	 */
	public function id() {
		return $this->product->get_id();
	}

	/**
	 * Get the ID of the parent product.
	 *
	 * @return int
	 */
	public function parent_id() {
		return $this->parent->get_id();
	}

	/**
	 * Get the SKU of the product.
	 *
	 * @return string
	 */
	public function sku() {
		return $this->product->get_sku();
	}

	/**
	 * Get the SKU of the parent product.
	 *
	 * @return string
	 */
	public function parent_sku() {
		return $this->parent->get_sku();
	}

	/**
	 * Get the title or name of the product.
	 *
	 * @return string
	 */
	public function title() {
		return $this->product->get_name();
	}

	/**
	 * Get the title or name of the parent product.
	 *
	 * @return string
	 */
	public function parent_title() {
		return $this->parent->get_name();
	}

	/**
	 * Get the product's description.
	 *
	 * If the product is a variation and doesn't have its own description, retrieves the parent product's description.
	 *
	 * @return string
	 */
	public function description() {
		$description = $this->product->get_description();

		if ( empty( $description ) && $this->product->is_type( 'variation' ) ) {
			$description = $this->parent->get_description();
		}

		return $description;
	}

	/**
	 * Get the product's short description.
	 *
	 * @return string
	 */
	public function short_description() {
		return $this->product->get_short_description();
	}

	/**
	 * Retrieve the attributes of the product.
	 *
	 * @return array
	 */
	public function attributes() {
		$attributes = array_keys( $this->product->get_attributes() );

		if ( empty( $attributes ) && $this->product->is_type( 'variation' ) ) {
			$attributes = array();
		}

		return $attributes;
	}

	/**
	 * Retrieve the category IDs the product belongs to.
	 *
	 * @return array
	 */
	public function categories() {
		return $this->product->get_category_ids();
	}

	/**
	 * Retrieve the tag IDs the product is tagged with.
	 *
	 * @return array
	 */
	public function tags() {
		return $this->product->get_tag_ids();
	}

	/**
	 * Get the product's permalink.
	 *
	 * @return string
	 */
	public function link() {
		return $this->product->get_permalink();
	}

	/**
	 * Get the parent product's permalink.
	 *
	 * @return string
	 */
	public function parent_link() {
		return $this->parent->get_permalink();
	}

	/**
	 * Get the product's add to cart URL.
	 *
	 * @return string
	 */
	public function add_to_cart_link() {
		return $this->product->add_to_cart_url();
	}

	/**
	 * Get the product's availability.
	 *
	 * @return array
	 */
	public function availability() {
		return $this->product->get_availability();
	}

	/**
	 * Get the product's stock quantity.
	 *
	 * @return int|null
	 */
	public function quantity() {
		return $this->product->get_stock_quantity();
	}

	/**
	 * Get the product's stock status.
	 *
	 * @return string
	 */
	public function stock_status() {
		return $this->product->get_stock_status();
	}

	/**
	 * Get the product's weight.
	 *
	 * @return string|null
	 */
	public function weight() {
		return $this->product->get_weight();
	}

	/**
	 * Get the weight unit used in WooCommerce settings.
	 *
	 * @return string
	 */
	public function weight_unit() {
		$weight_unit = get_option( 'woocommerce_weight_unit' );// @phpstan-ignore-line

		if ( $weight_unit ) {
			return $weight_unit;
		}

		return 'kg';
	}

	/**
	 * Get the product's width.
	 *
	 * @return string|null
	 */
	public function width() {
		return $this->product->get_width();
	}

	/**
	 * Get the product's height.
	 *
	 * @return string|null
	 */
	public function height() {
		return $this->product->get_height();
	}

	/**
	 * Get the product's length.
	 *
	 * @return string|null
	 */
	public function length() {
		return $this->product->get_length();
	}

	/**
	 * Get the product type.
	 *
	 * @return string
	 */
	public function type() {
		return $this->product->get_type();
	}

	/**
	 * Get the product's catalog visibility.
	 *
	 * @return string
	 */
	public function visibility() {
		return $this->product->get_catalog_visibility();
	}

	/**
	 * Get the total rating count of the product.
	 *
	 * @return int
	 */
	public function rating_total() {
		return $this->product->get_rating_count();
	}

	/**
	 * Get the average rating of the product.
	 *
	 * @return float
	 */
	public function rating_average() {
		return $this->product->get_average_rating();
	}

	/**
	 * Get the author's name of the product.
	 *
	 * @return string
	 */
	public function author_name() {
		$post = get_post( $this->product->get_id() );

		if ( ! ( $post instanceof \WP_Post ) ) {
			return '';
		}

		return get_the_author_meta( 'display_name', (int) $post->post_author );
	}

	/**
	 * Get the email of the author of the product.
	 *
	 * @return string
	 */
	public function author_email() {
		$post = get_post( $this->product->get_id() );

		if ( ! ( $post instanceof \WP_Post ) ) {
			return '';
		}

		return get_the_author_meta( 'user_email', (int) $post->post_author );
	}

	/**
	 * Get the date when the product was created.
	 *
	 * @return string
	 */
	public function date_created() {
		$date_created = $this->product->get_date_created();

		if ( $date_created === null && $this->product->is_type( 'variation' ) ) {
			$date_created = $this->parent->get_date_created();
		}

		if ( ! ( $date_created instanceof \WC_DateTime ) ) {
			return '';
		}

		return $date_created->date_i18n();
	}

	/**
	 * Get the date when the product was last updated.
	 *
	 * @return string
	 */
	public function date_updated() {
		$date_created = $this->product->get_date_modified();

		if ( $date_created === null && $this->product->is_type( 'variation' ) ) {
			$date_created = $this->parent->get_date_modified();
		}

		if ( ! ( $date_created instanceof \WC_DateTime ) ) {
			return '';
		}

		return $date_created->date_i18n();
	}

	/**
	 * Get the current status of the product.
	 *
	 * @return string
	 */
	public function product_status() {
		return $this->product->get_status();
	}

	/**
	 * Determine if the product is featured.
	 *
	 * @return bool
	 */
	public function featured_status() {
		return $this->product->is_featured();
	}

	/**
	 * Get the currency used for the product.
	 *
	 * @return string
	 */
	public function currency() {
		return get_woocommerce_currency();
	}

	/**
	 * Get the product's regular price.
	 *
	 * @return string
	 */
	public function regular_price() {
		return $this->product->get_regular_price();
	}

	/**
	 * Get the product's regular price.
	 *
	 * @return string
	 */
	public function price() {
		return $this->product->get_price();
	}

	/**
	 * Get the product's sale price.
	 *
	 * @return string
	 */
	public function sale_price() {
		return $this->product->get_sale_price();
	}

	/**
	 * Get the product's regular price including tax.
	 *
	 * @return float|string
	 */
	public function regular_price_with_tax() {
		return wc_get_price_including_tax(
			$this->product,
			array(
				'price' => $this->product->get_regular_price(),
				'qty'   => 1,
			)
		);
	}

	/**
	 * Get the product's price including tax.
	 *
	 * @return float|string
	 */
	public function price_with_tax() {
		return wc_get_price_including_tax(
			$this->product,
			array(
				'price' => $this->product->get_price(),
				'qty'   => 1,
			)
		);
	}

	/**
	 * Get the product's sale price including tax.
	 *
	 * @return float|string
	 */
	public function sale_price_with_tax() {
		return wc_get_price_including_tax(
			$this->product,
			array(
				'price' => $this->product->get_sale_price(),
				'qty'   => 1,
			)
		);
	}

	/**
	 * Get the starting date of the product's sale.
	 *
	 * @return string
	 */
	public function sale_price_sdate() {
		$sdate = $this->product->get_date_on_sale_from();

		if ( ! ( $sdate instanceof \WC_DateTime ) ) {
			return '';
		}

		return $sdate->date_i18n();
	}

	/**
	 * Get the ending date of the product's sale.
	 *
	 * @return string|false
	 */
	public function sale_price_edate() {
		$edate = $this->product->get_date_on_sale_to();

		if ( ! ( $edate instanceof \WC_DateTime ) ) {
			return '';
		}

		return $edate->date_i18n();
	}

	/**
	 * Get the tax class of the product.
	 *
	 * @return string
	 */
	public function tax_class() {
		$tax_class = $this->product->get_tax_class();

		if ( empty( $tax_class ) ) {
			$tax_class = 'standard';
		}

		return $tax_class;
	}

	/**
	 * Get the tax status of the product.
	 *
	 * @return string
	 */
	public function tax_status() {
		return $this->product->get_tax_status();
	}

	/**
	 * Get the shipping class of the product.
	 *
	 * @return string
	 */
	public function shipping_class() {
		return $this->product->get_shipping_class();
	}

	/**
	 * Get a specific global attribute of the product.
	 *
	 * @param string $attribute Name of the attribute to retrieve.
	 * @return string
	 */
	public function get_attribute( $attribute = '' ) {
		return $this->product->get_attribute( $attribute );
	}

	/**
	 * Get a custom attribute or meta of the product.
	 *
	 * If the product is a variation and doesn't have the specified meta, retrieves it from the parent product.
	 *
	 * @param string $meta Name of the meta to retrieve.
	 * @return mixed
	 */
	public function get_product_meta( $meta = '' ) {
		// Remove the underscore prefix from the meta-key.
		$first = substr( $meta, 0, 1 );

		if ( $first === '_' ) {
			$meta = substr( $meta, 1 );
		}

		$value = $this->product->get_meta( $meta, true );

		if ( $value === '' && $this->product->is_type( 'variation' ) ) {
			$value = $this->parent->get_meta( $meta, true );
		}

		return $value;
	}
	// ... End of Class ...

}
