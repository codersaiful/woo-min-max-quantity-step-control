<?php

/**
 * Generate and convert Message and replace right value on selected keyword.
 * Suppose user want to show min_quantity in message, now user able to customize message and where user want to
 * show min max quantity and product name, they just will use [min_quantity],[max_quantity],[product_name]
 *
 * [min_quantity],
 * [max_quantity],
 * [product_name]
 *
 *
 * ::CODE EXAMPLE::
 * $args = array(
'min_quantity' => 15,
'max_quantity' => 25,
'product_name'=> 'Hello World',
);
$message = 'this is a[product_name] message. this is a[max_quantity] message  with [min_quantity] and with other value.';
wcmmq_message_convert_replace( $message, $args );
var_dump(wcmmq_message_convert_replace($message, $args));
 *
 * @param String $message
 * @param Array $args
 * @return String
 */

function wcmmq_message_convert_replace( $message, $args, $product_id = null ){
    $message = __( $message, 'wcmmq' );
    $defaults = array(
        'min_quantity' => false,
        'max_quantity' => false,
        'product_name'=> false,
        'current_quantity'=> false,
    );
    $args = wp_parse_args( $args, $defaults );
    $args = apply_filters( 'wcmmq_message_replaced_shortcode_args', $args );
    $arr_keys = array_keys($args);
    $find_arr = array_map(function($val){
        return "[$val]";
    },$arr_keys);

    $reslt = str_replace($find_arr, $args, $message);
    $reslt = apply_filters('wcmmq_validation_message', $reslt, $product_id );
    return $reslt;
}

/**
 * Getting current quantity in cart of current product. I mean: we will check it by product ID.
 *
 * @global type $woocommerce We have used $woocommerce variable.
 * @param int $product_id Need Product_ID for check current quantity in cart
 * @return int
 */
function wcmmq_check_quantity_in_cart($product_id,$variation_id = 0) {
    global $woocommerce;
    if( ! is_object($woocommerce->cart)) return 0;
    if( ! method_exists($woocommerce->cart, 'get_cart')) return 0;
    // var_dump($woocommerce);
    foreach($woocommerce->cart->get_cart() as $key => $value ) {
        if( $product_id == $value['product_id'] && $variation_id == $value['variation_id'] ) {
            return $value['quantity'];
        }
    }
    return 0;
}

/**
 * Qty Validation based on Step
 * Added on Version: 1.8.3
 *
 * @param type quantity
 * @param type min_quantity
 * @param type step_quantity
 * @return boolean True for pass valid, false for Fail
 */
function wcmmq_qty_validation_by_step_modulous( $modulous, $product_id, $variation_id, $quantity, $min_quantity, $step_quantity ){
    
    //fixing min max value;
    $quantity = ! empty( $quantity ) ? $quantity : 0;
    $min_quantity = $min_quantity == '0' || ! empty( $min_quantity ) ? $min_quantity : 1;
    $step_quantity = ! empty( $step_quantity ) ? $step_quantity : 1;

    $modulous = false;
    if(!is_numeric($quantity) || !is_numeric($step_quantity)) { 
        $modulous = false; 
    }

    $consnt_value = 10000000000;
    $quantity_int = $quantity * $consnt_value;//intval($quantity * $consnt_value);
    $min_quantity_int = $min_quantity * $consnt_value;//intval($min_quantity * $consnt_value);
    $step_int = $step_quantity * $consnt_value;//intval($step_quantity * $consnt_value);
    $final_qty = $quantity_int - $min_quantity_int;//intval($quantity_int - $min_quantity_int);
    if (!empty($step_int)){
        $module = $final_qty % $step_int;
    }else{
        $module = 0;
    }

    if( $module == 0 ) { $modulous = true; }

    $should_min = $quantity > $min_quantity ? ($quantity - ($module/$consnt_value)) : $min_quantity;
    $should_next = $should_min + $step_quantity;
    $modulous = apply_filters( 'wcmmq_last_step_checker_filter', $modulous, $product_id, $variation_id, $quantity, $min_quantity, $step_quantity );
    $specific_msge = false;
    $other_data = [
        'should_min'    => $should_min,
        'should_next'   => $should_next,
        'product_id'    => $product_id,
        'variation_id'  => $variation_id,
        'quantity'      => $quantity,
        'min_quantity'  => $min_quantity,
        'step_quantity' => $step_quantity,
    ];
    wcmmq_step_error_message( $modulous, $other_data );

    return $modulous;
}

/**
 * Mainly if need to show warning, that function will call.
 * otherwise, direct return null.
 *
 * @param boolean $bool
 * @param [String] $specific_msge
 * @param [int] $should_min
 * @param [int] $should_next
 * @return void
 */
function wcmmq_step_error_message( $bool = true, $other_data = [] ){
    if( $bool ) return;

    $product_id = $other_data['product_id'] ?? 0;
    $product_name = ! empty( $product_id ) ? get_the_title( $product_id ) : '';
    $variation_id = $other_data['variation_id'] ?? 0;
    $variation_name = ! empty( $variation_id ) ? get_the_title( $variation_id ) : '';

    
    $args = [
        'should_min'    => $other_data['should_min'] ?? '',
        'should_next'   => $other_data['should_next'] ?? '',
        'product_name'  => $product_name,
        'variation_name'=> $variation_name,
        'quantity'      => $other_data['quantity'] ?? '',
        'min_quantity'  => $other_data['min_quantity'] ?? '',
        'step_quantity' => $other_data['step_quantity'] ?? '',
    ];
    
    $should_min = $args['should_min'] ?? '';
    $should_next = $args['should_next'] ?? '';
    $message = sprintf( WC_MMQ::getOption( WC_MMQ_PREFIX . 'step_error_valiation' ), $should_min, $should_next );
    $message = wcmmq_message_convert_replace( $message, $args,$product_id );
    wc_add_notice( $message, 'error' );
}

add_filter( 'wcmmq_modulous_validation', 'wcmmq_qty_validation_by_step_modulous', 10, 6 );
add_filter( 'wcmmq_last_step_checker_filter', 'wcmmq_last_step_checker', 10, 6 );
function wcmmq_last_step_checker( $modulous, $product_id, $variation_id, $quantity, $min_quantity, $step_quantity ){

    //Only if true
    if( $modulous ) return $modulous;
    $product = wc_get_product( $product_id );
    //Only if stock manage
    if( ! $product->managing_stock() ) return $modulous;

    $stock_qty = $product->get_stock_quantity(); // 17
    //
    $last_step = $stock_qty % $step_quantity;
    $last_v_stock = $stock_qty - $last_step;
    if( $quantity < $last_v_stock) return $modulous;

    if( $quantity == ( $last_step + $last_v_stock ) ) return true;


    return $modulous;

}
/**
 * For replace
 *
 * @param type $string
 * @param array $key_arr
 * @param array $val_arr
 * @return type
 */
function wcmmq_replaced_msg( $text, $key_arr = array(), $val_arr = array() ){
    $string = str_replace( $key_arr, $val_arr, $text );

    return $string;
}
/**
 * Setting minimum and maximum quantity validation when product adding to cart.
 * We also used current quantity [$current_qty_inCart] of cart for checking limitation.
 *
 * @param Boolean $bool
 * @param Int $product_id post Id
 * @param Int $quantity Quantity when will add to cart
 * @param Int $variation_id for Variable product
 * @param Array|Boolean $variations Variations as Array
 * @return boolean True or false
 *
 * @link https://docs.woocommerce.com/wc-apidocs/source-class-WC_AJAX.html#365 Details
 * @since 1.0
 */
function wcmmq_min_max_valitaion($bool,$product_id,$quantity,$variation_id = 0, $variations = false){ //Right two parameters added
    
    /**
     * If anytime, we want to remove the validation change, we have to
     * set falst the filter hook value for 'wcmmq_add_validation_check'
     * 
     * @since 4.3.0
     * @author Saiful Islam <codersaiful@gmail.com>
     */
    $validation_check = apply_filters('wcmmq_add_validation_check',true, $product_id, get_the_ID());
    if(!$validation_check) return $bool;

    $is_variable_support = defined('WC_MMQ_PRO_VERSION');
    $product = wc_get_product( $product_id );
    // if product is sold individually then we can immediately exit here
    if( method_exists( $product, 'is_sold_individually' ) && $product->is_sold_individually() ) return true;

    $min_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'min_quantity', true);
    $default_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'default_quantity', true);
    $default_quantity = $default_quantity == '0' || !empty( $default_quantity ) ? $default_quantity : $min_quantity;
    $max_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'max_quantity', true);
    $step_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'product_step', true); //Version 1.8.3
    if($variation_id && $is_variable_support ){
        $v_min_qty = get_post_meta( $variation_id, WC_MMQ_PREFIX . 'min_quantity', true );
        $v_max_qty = get_post_meta( $variation_id, WC_MMQ_PREFIX . 'max_quantity', true );
        $v_step_qty = get_post_meta( $variation_id, WC_MMQ_PREFIX . 'product_step', true );//Version 1.8.3

        if($v_min_qty == '0' || !empty($v_min_qty) || !empty($v_max_qty) || !empty($v_step_qty) ){
            $min_quantity = $v_min_qty ?? '';
            $max_quantity = $v_max_qty ?? '';
            $step_quantity = $v_step_qty ?? '';
        }
    }



    $terms_data = wcmmq_get_term_data_wpml();
    $_is_term_value_founded = false;
    $_is_single_value = $min_quantity == '0' || !empty( $min_quantity ) || !empty( $max_quantity ) || !empty( $step_quantity ) ;
    
    if( is_array($terms_data) && ! $_is_single_value ){
        foreach( $terms_data as $term_key => $values ){

            $product_term_list = wp_get_post_terms( $product_id, $term_key, array( 'fields' => 'ids' ));
            foreach ( $product_term_list as $product_term_id ){

                $my_term_value = isset( $values[$product_term_id] ) ? $values[$product_term_id] : false;
                if( is_array( $my_term_value ) ){
                    $_is_term_value_founded = true;
                    $min_quantity = $my_term_value['_min'] ?? '0';
                    $default_quantity = $my_term_value['_default'] ?? $min_quantity;
                    
                    $max_quantity = $my_term_value['_max'] ?? '';
                    $step_quantity = $my_term_value['_step'] ?? '';

                    break;
                }
            }

        }
    }
    

    if( ! $_is_term_value_founded && ! $_is_single_value ){
        $min_quantity = WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'min_quantity',$product_id );
        $default_quantity = WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'default_quantity',$product_id );
        $default_quantity = $default_quantity === '0' || !empty( $default_quantity ) ? $default_quantity : $min_quantity;
        $max_quantity = WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'max_quantity',$product_id );
        $step_quantity = WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'product_step',$product_id );
    }
    

    //Finalized 
    $min_quantity = $min_quantity === '0' || !empty( $min_quantity ) ? $min_quantity : '0';
    $default_quantity = $default_quantity === '0' || !empty( $default_quantity ) ? $default_quantity : $min_quantity;
    $step_quantity = !empty($step_quantity) ? $step_quantity : 1;
    
    /**
     * Getting current Quantity from Cart
     */
    $current_qty_inCart = wcmmq_check_quantity_in_cart( $product_id, $variation_id );
    $total_quantity = $current_qty_inCart + $quantity;
    $product_name = get_the_title( $product_id );
    $variation_name = ! empty( $variation_id ) ? get_the_title( $variation_id ) : '';

    $modulous = apply_filters( 'wcmmq_modulous_validation', false, $product_id, $variation_id, $quantity, $min_quantity, $step_quantity );
    
    $args = array(
        'min_quantity' => $min_quantity,
        'max_quantity' => $max_quantity,
        'step_quantity' => $step_quantity,
        'current_quantity' => $current_qty_inCart,
        'product_name'=> $product_name,
        'variation_name'=> $variation_name,
    );

    if( $total_quantity <= $max_quantity && $total_quantity >= $min_quantity && $modulous ){
        return $bool;
    }elseif($min_quantity && $total_quantity < $min_quantity ){
        $message = sprintf( wcmmq_get_message( 'msg_min_limit' ), $min_quantity, $product_name ); // __( 'Minimum quantity should %s of "%s"', 'wcmmq' ) //Control from main file
        $message = wcmmq_message_convert_replace( $message, $args, $product_id );
        wc_add_notice( $message, 'error' );
        return false;
    }elseif( $max_quantity && $total_quantity > $max_quantity ){
        $message = false;
        if( $current_qty_inCart > 0 ){
            $message .= sprintf( wcmmq_get_message( 'msg_max_limit_with_already' ), $current_qty_inCart, $product_name );
            $message .= " <br>";
        }
        $message .= sprintf( wcmmq_get_message( 'msg_max_limit' ), $max_quantity, $product_name ); // __( 'Minimum quantity should %s of "%s"', 'wcmmq' ) //Control from main file
        $message = wcmmq_message_convert_replace( $message, $args, $product_id );
        wc_add_notice( $message, 'error' );
        return false;
    }elseif( ! $modulous ){
        return false;
    }else{
        return $bool;
    }
}
// add_filter('woocommerce_add_to_cart_validation', 'wcmmq_min_max_valitaion', 10, 5); //When add to cart

/**
 * Validation when you will update cart page of WooCommerce. Actually Minimum and maximum as well as step should be fixed
 * on cart page. So that we have used this function by using filter 'woocommerce_update_cart_validation'
 *
 * @param type $true
 * @param type $cart_item_key
 * @param type $values
 * @param type $quantity
 * @return boolean
 *
 * @link https://docs.woocommerce.com/wc-apidocs/source-class-WC_Form_Handler.html#568 Details
 * @since 1.0
 */
function wcmmq_update_cart_validation( $true, $cart_item_key, $values, $quantity ) {

    /**
     * If anytime, we want to remove the validation change, we have to
     * set falst the filter hook value for 'wcmmq_cart_validation_check'
     * 
     * @since 4.3.0
     * @author Saiful Islam <codersaiful@gmail.com>
     */
    $validation_check = apply_filters('wcmmq_cart_validation_check',true, $values);
    if(!$validation_check) return $true;

    $is_variable_support = defined('WC_MMQ_PRO_VERSION');
    $product_id = $values['product_id'];

    $min_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'min_quantity', true);
    $default_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'default_quantity', true);
    $default_quantity = $default_quantity == '0' || !empty( $default_quantity ) ? $default_quantity : $min_quantity;
    $max_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'max_quantity', true);
    $step_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'product_step', true);

    $variation_id = $values['variation_id'];
    if( $is_variable_support && !empty( $variation_id ) ){
        $v_min_qty = get_post_meta( $variation_id, WC_MMQ_PREFIX . 'min_quantity', true );
        $v_max_qty = get_post_meta( $variation_id, WC_MMQ_PREFIX . 'max_quantity', true );
        $v_step_qty = get_post_meta( $variation_id, WC_MMQ_PREFIX . 'product_step', true );//Version 1.8.3

        if($v_min_qty == '0' || !empty($v_min_qty) || !empty($v_max_qty) || !empty($v_step_qty) ){
            $min_quantity = $v_min_qty ?? '';
            $max_quantity = $v_max_qty ?? '';
            $step_quantity = $v_step_qty ?? '';
        }

    }

    $terms_data = wcmmq_get_term_data_wpml();
    $_is_term_value_founded = false;
    $_is_single_value = $min_quantity == '0' || !empty( $min_quantity ) || !empty( $max_quantity ) || !empty( $step_quantity ) ;
    
    if(is_array($terms_data) && ! $_is_single_value ){
        foreach( $terms_data as $term_key => $values ){

            $product_term_list = wp_get_post_terms( $product_id, $term_key, array( 'fields' => 'ids' ));
            foreach ( $product_term_list as $product_term_id ){

                $my_term_value = isset( $values[$product_term_id] ) ? $values[$product_term_id] : false;
                if( is_array( $my_term_value ) ){
                    $_is_term_value_founded = true;
                    $min_quantity = $my_term_value['_min'] ?? '0';
                    $default_quantity = $my_term_value['_default'] ?? $min_quantity;
                    
                    $max_quantity = $my_term_value['_max'] ?? '';
                    $step_quantity = $my_term_value['_step'] ?? '';

                    break;
                }
            }

        }
    }
    
    if( ! $_is_term_value_founded && ! $_is_single_value ){
        $min_quantity = WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'min_quantity',$product_id );
        $default_quantity = WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'default_quantity',$product_id );
        $default_quantity = $default_quantity === '0' || !empty( $default_quantity ) ? $default_quantity : $min_quantity;
        $max_quantity = WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'max_quantity',$product_id );
        $step_quantity = WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'product_step',$product_id );
    }
    

    //Finalized 
    $min_quantity = $min_quantity === '0' || !empty( $min_quantity ) ? $min_quantity : '0';
    $default_quantity = $default_quantity === '0' || !empty( $default_quantity ) ? $default_quantity : $min_quantity;
    $step_quantity = !empty($step_quantity) ? $step_quantity : 1;
    

    $product_name = get_the_title( $product_id );
    $variation_name = ! empty( $variation_id ) ? get_the_title( $variation_id ) : '';
    $error_msg = " : " . $product_name;
    // $modulous = wcmmq_qty_validation_by_step_modulous( $quantity, $min_quantity, $step_quantity, $error_msg );
    $modulous = apply_filters( 'wcmmq_modulous_validation', false, $product_id, $variation_id, $quantity, $min_quantity, $step_quantity );

    $args = array(
        'min_quantity' => $min_quantity,
        'max_quantity' => $max_quantity,
        'product_name'=> $product_name,
        'step_quantity' => $step_quantity,
        'product_name'=> $product_name,
        'variation_name'=> $variation_name,
    );

    if( ( !empty($max_quantity) && $max_quantity > 0 && $quantity <= $max_quantity) && $quantity >= $min_quantity && $modulous ){
        return true;
    }elseif( empty($max_quantity) && $quantity >= $min_quantity && $modulous ){
        return true;
    }elseif(!empty($max_quantity) && $max_quantity > 0 && $quantity > $max_quantity ){
        $message = sprintf( wcmmq_get_message( 'msg_max_limit' ), $max_quantity, $product_name ); // __( 'Minimum quantity should %s of "%s"', 'wcmmq' ) //Control from main file
        $message = wcmmq_message_convert_replace( $message, $args, $product_id );
        wc_add_notice( $message, 'error' );
        return;
    }elseif( $quantity < $min_quantity ){
        $message = sprintf( wcmmq_get_message( 'msg_min_limit' ), $min_quantity, $product_name ); // __( 'Minimum quantity should %s of "%s"', 'wcmmq' ) //Control from main file
        $message = wcmmq_message_convert_replace( $message, $args, $product_id );
        wc_add_notice( $message, 'error' );
        return;
    }elseif(!$modulous){
        return;
    }else{
        return true;
    }
}
// add_filter('woocommerce_update_cart_validation', 'wcmmq_update_cart_validation', 10, 4); //When Update cart


/**
 * Getting quantity arguments for All,
 * This quantity arguments will work for type product ande page.
 * Single page of all type product such: simple,variable
 * Cart page , checkout page
 *
 * @param type $args Quantity arguments
 * @param type $product Product's Object to get ID of product
 * @return type Array
 *
 * @since 1.0
 * @link https://docs.woocommerce.com/wc-apidocs/source-function-woocommerce_quantity_input.html#1234 Details of filter 'woocommerce_quantity_input_args'
 */
function wcmmq_quantity_input_args( $args, $product){

    $is_variable_support = defined('WC_MMQ_PRO_VERSION');
    // if product is sold individually then we can immediately exit here
    if( $product->is_sold_individually() ) return $args;
    //if(is_cart() ){

    $variation_id = false;
    $product_id = $id = $product->get_id();
    if( is_cart() ){
        if( $product->get_type() == 'variation' ){
            $product_id = $product->get_parent_id();
            $variation_id = $product->get_id();
        }else{
            $product_id = $product->get_id();
        }
    }

    $min_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'min_quantity', true);
    $default_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'default_quantity', true);
    
    $default_quantity = $default_quantity == '0' || !empty( $default_quantity ) ? $default_quantity : '';
    $max_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'max_quantity', true);
    $step_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'product_step', true);
    
    
    if( $is_variable_support && ! empty( $variation_id )){
        $v_min_qty = get_post_meta( $variation_id, WC_MMQ_PREFIX . 'min_quantity', true );
        $v_max_qty = get_post_meta( $variation_id, WC_MMQ_PREFIX . 'max_quantity', true );
        $v_step_qty = get_post_meta( $variation_id, WC_MMQ_PREFIX . 'product_step', true );
        $v_default_qty = get_post_meta( $variation_id, WC_MMQ_PREFIX . 'default_quantity', true );
        if($v_min_qty == '0' || !empty($v_min_qty) || !empty($v_max_qty) || !empty($v_step_qty) || !empty($v_default_qty)){
            $min_quantity = $v_min_qty ?? '';
            $max_quantity = $v_max_qty ?? '';
            $step_quantity = $v_step_qty ?? '';
            $default_quantity = $v_default_qty ?? '';
        }
        
    }
    
    $terms_data = wcmmq_get_term_data_wpml();
    $_is_term_value_founded = false;
    $_is_single_value = $min_quantity == '0' || !empty( $min_quantity ) || !empty( $max_quantity ) || !empty( $step_quantity ) ;
    
    if(is_array($terms_data) && ! $_is_single_value ){
        foreach( $terms_data as $term_key => $values ){

            $product_term_list = wp_get_post_terms( $product_id, $term_key, array( 'fields' => 'ids' ));
            foreach ( $product_term_list as $product_term_id ){

                $my_term_value = isset( $values[$product_term_id] ) ? $values[$product_term_id] : false;
                if( is_array( $my_term_value ) ){
                    $_is_term_value_founded = true;
                    $min_quantity = $my_term_value['_min'] ?? '0';
                    $default_quantity = $my_term_value['_default'] ?? '';
                    
                    $max_quantity = $my_term_value['_max'] ?? '';
                    $step_quantity = $my_term_value['_step'] ?? '';

                    break;
                }
            }

        }
    }
    
    
    if( ! $_is_term_value_founded && ! $_is_single_value ){
        $min_quantity = WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'min_quantity',$product_id );
        $default_quantity = WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'default_quantity',$product_id );
        $default_quantity = $default_quantity === '0' || !empty( $default_quantity ) ? $default_quantity : '';
        $max_quantity = WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'max_quantity',$product_id );
        $step_quantity = WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'product_step',$product_id );
    }
    
    
    //Finalized 
    $min_quantity = $min_quantity === '0' || !empty( $min_quantity ) ? $min_quantity : '0';
    $default_quantity = !empty( $default_quantity ) ? $default_quantity : false;
    $step_quantity = !empty($step_quantity) ? $step_quantity : 1; 

    if( isset( $args['max_value'] ) && $args['max_value'] > -1){
        // stock quantity already set
        $args['max_value']  = $max_quantity && $max_quantity <= $args['max_value']   ? $max_quantity : $args['max_value'];

    }elseif( !empty( $args['max_qty'] ) ){
        // variable product
        // stock quantity already set
        $args['max_qty']    = $max_quantity && $max_quantity <= $args['max_qty']     ? $max_quantity : $args['max_qty'];
    }else{
        $args['max_value'] = $args['max_qty'] = $max_quantity;
    }
    
    $args['min_value'] = $args['min_qty'] = $min_quantity; // Min quantity (default = 0)
    /**
     * Our Customer has given me this solution and It's awesome.
     * Working property.
     * Really great it. Thanks to that user.
     * 
     */
    $default_qty = apply_filters( 'wcmmq_default_qty_option', false, get_the_ID() );
    if( $default_qty && $default_quantity !== $min_quantity && ! empty( $default_quantity ) && ! empty( $args['input_name'] ) && substr($args['input_name'],0,8) === 'quantity'){
        $args['input_value'] = $default_quantity;
    }

    if( ! empty($args['min_value']) && ! empty( $args['input_value'] ) && $args['input_value'] < $args['min_value'] ){
        $args['input_value'] = $args['min_value'];
    }

    // $args['input_value'] = $default_quantity;
    $args['step'] = $step_quantity; // Increment/decrement by this value (default = 1)
    $args['quantity'] = $default_quantity; // Increment/decrement by this value (default = 1)
    // var_dump($args);
    //}

    return apply_filters('wcmmq_single_product_min_max_condition', $args, $product);
}
// add_filter('woocommerce_loop_add_to_cart_args','wcmmq_quantity_input_args',999,2);
// add_filter('woocommerce_quantity_input_args','wcmmq_quantity_input_args',999,2);
// add_filter('woocommerce_available_variation','wcmmq_quantity_input_args',999,2); //For Variable product

/**
 * Specially for Order Page
 * Min max step set
 * 
 * asole order page a step min max kaj korto na. ekhon seta kaj korobe.
 * 
 * @author Saiful Islam <codersaiful@gmail.com>
 *
 * @param int|float|mixed $step
 * @param object $product
 * @return int|float|mixed
 * 
 * @since 4.1.0
 */
function wcmmq_admin_quantity_input_args( $step, $product ){
    if( ! is_object($product) ) return $step;
    
    $is_variable_support = defined('WC_MMQ_PRO_VERSION');
    // if product is sold individually then we can immediately exit here
    if( $product->is_sold_individually() ) return $step;

    $variation_id = false;
    $product_id = $id = $product->get_id();

    $step_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'product_step', true);
    
    
    if( $is_variable_support && ! empty( $variation_id )){
        $v_step_qty = get_post_meta( $variation_id, WC_MMQ_PREFIX . 'product_step', true );
        if( !empty($v_step_qty) ){
            $step_quantity = $v_step_qty ?? '';
        }
        
    }
    
    $terms_data = wcmmq_get_term_data_wpml();
    $_is_term_value_founded = false;
    $_is_single_value = !empty( $step_quantity ) ;
    
    if(is_array($terms_data) && ! $_is_single_value ){
        foreach( $terms_data as $term_key => $values ){

            $product_term_list = wp_get_post_terms( $product_id, $term_key, array( 'fields' => 'ids' ));
            foreach ( $product_term_list as $product_term_id ){

                $my_term_value = isset( $values[$product_term_id] ) ? $values[$product_term_id] : false;
                if( is_array( $my_term_value ) ){
                    $_is_term_value_founded = true;
                    $step_quantity = $my_term_value['_step'] ?? '';

                    break;
                }
            }

        }
    }
    
    
    if( ! $_is_term_value_founded && ! $_is_single_value ){
        $step_quantity = WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'product_step',$product_id );
    }
    
    
    $step_quantity = !empty($step_quantity) ? $step_quantity : 1;

   return $step_quantity;
}

add_filter('woocommerce_quantity_input_step_admin', 'wcmmq_admin_quantity_input_args', 10, 2);

/**
 * Specially for Order Page
 * Min max step set
 * 
 * asole order page a step min max kaj korto na. ekhon seta kaj korobe.
 * 
 * @author Fazle Bari <fazlebarisn@gmail.com>
 *
 * @param int|float|mixed $step
 * @param object $product
 * @return int|float|mixed
 * 
 * @since 4.2.0
 */
function wcmmq_quantity_input_min_admin( $min_quantity, $product ){
    if( ! is_object($product) ) return $min_quantity;
    $is_variable_support = defined('WC_MMQ_PRO_VERSION');
    // if product is sold individually then we can immediately exit here

    $variation_id = false;
    $product_id = $id = $product->get_id();
    if( is_cart() ){
        if( $product->get_type() == 'variation' ){
            $product_id = $product->get_parent_id();
            $variation_id = $product->get_id();
        }else{
            $product_id = $product->get_id();
        }
    }

    $min_quantity = get_post_meta($product_id, WC_MMQ_PREFIX . 'min_quantity', true);

    
    if( $is_variable_support && ! empty( $variation_id )){
        $v_min_qty = get_post_meta( $variation_id, WC_MMQ_PREFIX . 'min_quantity', true );
        if($v_min_qty == '0' || !empty($v_min_qty)){
            $min_quantity = $v_min_qty ?? '';
        }
        
    }
    
    $terms_data = wcmmq_get_term_data_wpml();
    $_is_term_value_founded = false;
    $_is_single_value = $min_quantity == '0' || !empty( $min_quantity ) ;
    
    if(is_array($terms_data) && ! $_is_single_value ){
        foreach( $terms_data as $term_key => $values ){

            $product_term_list = wp_get_post_terms( $product_id, $term_key, array( 'fields' => 'ids' ));
            foreach ( $product_term_list as $product_term_id ){

                $my_term_value = isset( $values[$product_term_id] ) ? $values[$product_term_id] : false;
                if( is_array( $my_term_value ) ){
                    $_is_term_value_founded = true;
                    $min_quantity = $my_term_value['_min'] ?? '0';
                    break;
                }
            }

        }
    }
     
    if( ! $_is_term_value_founded && ! $_is_single_value ){
        $min_quantity = WC_MMQ::minMaxStep( WC_MMQ_PREFIX . 'min_quantity',$product_id );
    }
    
    //Finalized 
    $min_quantity = $min_quantity === '0' || !empty( $min_quantity ) ? $min_quantity : '0';

    return $min_quantity;
}
add_filter( 'woocommerce_quantity_input_min_admin', 'wcmmq_quantity_input_min_admin', 10, 2 );


/**
 * This function will override min quantity which is set from plugin and return min qty of 0
 * so customer can edit and enter any min quantity
 * 
 * @author Fazle Bari <fazlebarisn@gmail.com>
 *
 * @return int|float|mixed $min_qty
 * 
 * @since 4.2.0
 */
function wcmmq_admin_qty_min(){
    return 0;
}

/**
 * This function will override step quantity which is set from plugin and return step qty of 0.001
 * so customer can edit and enter any step quantity
 * 
 * @author Fazle Bari <fazlebarisn@gmail.com>
 *
 * @return int|float|mixed $step
 * 
 * @since 4.2.0
 */
function wcmmq_admin_qty_step(){
    return 0.001;
}

/**
 *  wcmmq_admin_qty_min()  and wcmmq_admin_qty_step() is for a special situation
 *  By default both function is desible 
 * 
 *  If any user want to change order quantity from deshboard in any number then we have to use this two function
 * 
 */

$options = WC_MMQ::getOptions();

$disable_order_page = isset( $options['disable_order_page'] ) ? true : false;

// in the old version field name is _wcmmq_s_disable_order_page so we need to consider that also 
$disable_order_page_old = isset( $options['_wcmmq_s_disable_order_page'] ) ? true : false;

if ( $disable_order_page || $disable_order_page_old ){
    add_filter( 'woocommerce_quantity_input_min_admin', 'wcmmq_admin_qty_min',999 );
    add_filter('woocommerce_quantity_input_step_admin', 'wcmmq_admin_qty_step',999);
}

/**
 * Set limit on Single product page for Minimum Quantity of Product
 * @since 1.0
 */
function wcmmq_s_set_min_for_single( $quantity, $product ){
    if( is_object( $product ) &&  method_exists( $product, 'get_id' ) ) {
        $min_quantity = get_post_meta($product->get_id(), WC_MMQ_PREFIX . 'min_quantity', true);
        $min_quantity = !empty($min_quantity) ? $min_quantity : WC_MMQ::getOption(WC_MMQ_PREFIX . 'min_quantity'); //Regenerate from Default
        if ( method_exists( $product, 'is_sold_individually' ) && ! $product->is_sold_individually() && (!empty($min_quantity) || !$min_quantity) && is_numeric($min_quantity)) {
            return $min_quantity;
        }
    }
    return 1;
}
// add_filter('woocommerce_quantity_input_min','wcmmq_s_set_min_for_single', 99, 2 );

/**
 * Set limit on Single product page for Maximum Quantity of Product
 * @since 1.0
 */
function wcmmq_s_set_max_for_single( $quantity, $product ){
    if( is_object( $product ) &&  method_exists( $product, 'get_id' ) ) {
        $max_quantity = get_post_meta($product->get_id(), WC_MMQ_PREFIX . 'max_quantity', true);
        $max_quantity = !empty($max_quantity) ? $max_quantity : WC_MMQ::getOption(WC_MMQ_PREFIX . 'max_quantity'); //Regenerate from Default
        if ( method_exists( $product, 'is_sold_individually' ) && ! $product->is_sold_individually() && (!empty($max_quantity) || !$max_quantity) && is_numeric($max_quantity)) {
            return $max_quantity;
        }
    }
    return 1;
}
//add_filter('woocommerce_quantity_input_max','wcmmq_s_set_max_for_single', 99, 2 );

/**
 * For Order Status update
 *
 * @param type $pp Post ID, Not using now
 */
function wcmmq_step_set_for_order_status_update($pp){
    if( is_admin() )
        return 0.01;
}
//add_filter('woocommerce_quantity_input_step','wcmmq_step_set_for_order_status_update',888,1);

/**
 * Set limit on Single product page for Step Quantity of Product
 * @since 1.0
 */
function wcmmq_step_set_step_quantity( $quantity, $product ){
    if( is_object( $product ) &&  method_exists( $product, 'get_id' ) && 'product'==get_post_type() ){

        $product_step = get_post_meta( $product->get_id(), WC_MMQ_PREFIX . 'product_step', true);
        $product_step = !empty( $product_step ) ? $product_step : WC_MMQ::getOption( WC_MMQ_PREFIX . 'product_step' ); //Regenerate from Default
        if( method_exists( $product, 'is_sold_individually' ) && ! $product->is_sold_individually() && ( !empty( $product_step ) || ! $product_step ) && is_numeric( $product_step ) ){
            return $product_step;
        }
    }
    return 1;
}
// add_filter('woocommerce_quantity_input_step','wcmmq_step_set_step_quantity', 99, 2);


/**
 * Set text to Before Quanity Input box
 *
 * @since 1.4
 *
 * @return String
 */
function wcmmq_set_prefix_quanity(){
    $prefix = WC_MMQ::getOption( WC_MMQ_PREFIX . 'prefix_quantity' );
    echo !empty( $prefix ) ? "<span class='wcmmq_sufix_prefix wcmmq_prefix'>$prefix</span>" : false;
}
add_action( 'woocommerce_before_add_to_cart_quantity','wcmmq_set_prefix_quanity' );


/**
 * Set text to After Quanity Input box
 *
 * @since 1.4
 *
 * @return String
 */
function wcmmq_set_sufix_quanity(){
    $sufix = WC_MMQ::getOption( WC_MMQ_PREFIX . 'sufix_quantity' );
    echo !empty( $sufix ) ? "<span class='wcmmq_sufix_prefix wcmmq_sufix'>$sufix</span>" : false;//$sufix;
}
add_action( 'woocommerce_after_add_to_cart_quantity','wcmmq_set_sufix_quanity',0 );

function wcmmq_add_custom_css(){
    echo <<<EOF
<style type="text/css">
span.wcmmq_prefix {
    float: left;
    padding: 10px;
    margin: 0;
}
</style>
EOF;
}
add_action('wp_head','wcmmq_add_custom_css');

