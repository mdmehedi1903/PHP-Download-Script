<?php
/**
 * Enqueue script and styles for child theme
 */
function woodmart_child_enqueue_styles() {
	wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array( 'woodmart-style' ), woodmart_get_theme_info( 'Version' ) );
}
add_action( 'wp_enqueue_scripts', 'woodmart_child_enqueue_styles', 10010 );







function get_woocommerce_product_discount() {
    global $product;
    if (!$product) return '';

    $discount_percentage = 0;

    // Check if it's a variable product
    if ($product->is_type('variable')) {
        $variations = $product->get_children();

        foreach ($variations as $variation_id) {
            $variation = wc_get_product($variation_id);
            $regular_price = floatval($variation->get_regular_price());
            $sale_price = floatval($variation->get_sale_price());

            if ($sale_price && $regular_price > $sale_price) {
                $variation_discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                if ($variation_discount > $discount_percentage) {
                    $discount_percentage = $variation_discount;
                }
            }
        }
    } 
    // For simple products
    else {
        $regular_price = floatval($product->get_regular_price());
        $sale_price = floatval($product->get_sale_price());

        if ($sale_price && $regular_price > $sale_price) {
            $discount_percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
        }
    }

    if ($discount_percentage > 0) {
        return '<span class="discount-label">-' . $discount_percentage . '%</span>';
    }

    return '';
}
add_shortcode('product_discount', 'get_woocommerce_product_discount');









// WhatsApp Order Button Shortcode
function whatsapp_order_button() {
    if ( ! is_product() ) return ''; // Ensure it runs only on single product pages

    global $product;
    $product_name = $product->get_name();
    $product_price = $product->get_price();
    $product_url = get_permalink($product->get_id());

    $whatsapp_number = '8801830777723'; // Replace with your WhatsApp number
    $message = "হ্যালো! আমি এই পণ্যটি নিতে চাই *$product_name* (মূল্য: $$product_price). লিংক: $product_url";
    $whatsapp_link = 'https://wa.me/' . $whatsapp_number . '?text=' . urlencode($message);

    return $whatsapp_link;
}
add_shortcode('whatsapp_order', 'whatsapp_order_button');

add_action('woocommerce_after_add_to_cart_button', function() {
    echo do_shortcode('[elementor-template id="28038"]');
});



add_filter( 'woocommerce_order_button_text', 'wc_custom_order_button_text' ); 

function wc_custom_order_button_text() {
    return __( 'অর্ডার কনফার্ম করুন', 'woocommerce' ); 
}






function order_now_checkout_button() {
    // Get the current product ID
    global $product;
    if (!$product || !is_a($product, 'WC_Product')) {
        return ''; // Return empty if not a product
    }
    
    // Generate the direct checkout URL for the product
    $checkout_url = wc_get_checkout_url() . '?add-to-cart=' . $product->get_id();
    
    // Return the button HTML
    return $checkout_url;
}
add_shortcode('order-now-checkout', 'order_now_checkout_button');



function add_order_now_button_to_archive() {
    global $product;
    
    if ( $product && ( $product->is_type( 'simple' ) || $product->is_type( 'variable' ) ) ) { 
        $product_url = get_permalink( $product->get_id() );
        echo '<a href="' . esc_url( $product_url ) . '" class="button order-now-button" style="background:#ff6600; color:#fff; padding:10px 15px; border-radius:5px; text-decoration:none; display:inline-block; margin-top:10px;">অর্ডার করুন</a>';
    }
}
add_action( 'woocommerce_after_shop_loop_item', 'add_order_now_button_to_archive', 15 );












// Only One product checkout with multiple quantitly and it will clean old cart, keep both code script and function 


// <script>
// jQuery(document).ready(function($) {
//     $('button.buy-now').click(function(e) {
//         e.preventDefault();
//         var productId = $('input[name="add-to-cart"]').val(); // Get product ID
//         window.location.href = '?buy_now=true&add-to-cart=' + productId; // Redirect to checkout with buy_now query parameter
//     });
// });
// </script>

function custom_buy_now_redirect() {
    if ( isset( $_GET['buy_now'] ) && is_singular( 'product' ) ) {
        // Clear the cart
        WC()->cart->empty_cart();

        // Get the current product ID
        $product_id = get_the_ID();

        // Add the product to the cart
        WC()->cart->add_to_cart( $product_id );

        // Redirect to checkout
        wp_safe_redirect( wc_get_checkout_url() );
        exit;
    }
}
add_action( 'template_redirect', 'custom_buy_now_redirect' );
