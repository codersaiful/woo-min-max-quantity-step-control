<?php
namespace WC_MMQ\Includes;

use WC_MMQ;
use WC_MMQ\Core\Base;

/**
 * Main Min_Max_Controller class
 * 
 * @todo ekhono variation onujayi ze kaj hobe seta kora hoyni.
 * @author Saiful Islam <codersaiful@gmail.com>
 */
class Min_Max_Controller extends Base
{

    public $product_id;
    public $product_name;
    /**
     * It's need, only when cart page, otherwise it will null
     *
     * @var int
     */
    public $variation_id;
    public $variation_name;

    //Important value
    public $min_value;
    public $max_value;
    public $step_value;
    public $stock_quantity;
    public $qty_inCart;

    //Important key
    public $min_quantity = WC_MMQ_PREFIX . 'min_quantity';
    public $default_quantity = WC_MMQ_PREFIX . 'default_quantity';
    public $max_quantity = WC_MMQ_PREFIX . 'max_quantity';
    public $product_step = WC_MMQ_PREFIX . 'product_step';
    public $key_prefix = WC_MMQ_PREFIX;

    /**
     * It's the property of where the args is final
     * Actually if found args on any product, 
     * it will be 'sinle'
     *
     * @var string it's can be single, taxonomy, global
     */
    protected $where_args_on = 'global';

    public $is_pro = false;
    public $is_args_organized = false;

    public $input_args = [];
    public $term_data;
    protected $options;
    protected $product;


    public function __construct()
    {
        $this->is_pro = defined('WC_MMQ_PRO_VERSION');
        $this->options = WC_MMQ::getOptions();
        $this->term_data = $this->options['terms'] ?? null;

        if( ! empty( $this->term_data ) ){
            $this->term_data = wcmmq_tems_based_wpml( $this->term_data );
        }
        
        //Input box args setup and manage
        add_filter('woocommerce_loop_add_to_cart_args',[$this, 'set_input_args'], 9999, 2);
        add_filter('woocommerce_quantity_input_args',[$this, 'set_input_args'], 9999, 2);
        add_filter('woocommerce_available_variation',[$this, 'set_input_args'], 9999, 2);

        //validation setup
        add_filter('woocommerce_add_to_cart_validation', [$this, 'add_to_cart_validation'], 10, 5);
        add_filter('woocommerce_update_cart_validation', [$this, 'update_cart_validation'], 10, 4);

    }

    /**
     * Specially for update_cart_validation() method and add_to_cart_validation() method of this class
     * 
     * ************************
     * IMPORTANT
     * ************************
     * dON'T CALL OTHER PLACE
     * only call after set $this->product property
     * **** sob jaygay use kora jabena. sudhu cart validation and add to cart validation er somoy
     * eta use kora jabe
     * 
     * *******************
     * WHAT USED
     * ********************
     * $this->product_id = $product_id;
        $this->variation_id = $variation_id;
        //Need this following line to dispay ['inputed_quantity'] in message, if used ['inputed_quantity'] on message box.
        $this->input_args['inputed_quantity'] = $quantity;

        $this->organizeAndFinalizeArgs();
     *
     * @param int|string $product_id
     * @param int|string $variation_id
     * @param int|string $quantity
     * @return void
     */
    public function OrganizeValidPropertyAndOrganize( $product_id, $variation_id, $quantity)
    {
        $this->product_id = $product_id;
        $this->variation_id = $variation_id;
        //Need this following line to dispay ['inputed_quantity'] in message, if used ['inputed_quantity'] on message box.
        $this->input_args['inputed_quantity'] = $quantity;

        $this->organizeAndFinalizeArgs();
    }
    public function update_cart_validation($bool, $cart_item_key, $values, $quantity)
    {

        /**
         * If anytime, we want to remove the validation change, we have to
         * set falst the filter hook value for 'wcmmq_cart_validation_check'
         * 
         * @since 4.3.0
         * @author Saiful Islam <codersaiful@gmail.com>
         */
        $validation_check = apply_filters( 'wcmmq_cart_validation_check', true, $values);
        if( ! $validation_check ) return $bool;
        $product_id = $values['product_id'] ?? 0;
        $variation_id = $values['variation_id'] ?? null;

        $this->product = wc_get_product( $product_id );
        if( method_exists( $this->product, 'is_sold_individually' ) && $this->product->is_sold_individually() ) return $bool;

        $this->OrganizeValidPropertyAndOrganize( $product_id, $variation_id, $quantity);

        //First Check modulous
        $modulous = $this->getModulous($quantity);
        if( ! $modulous ) return false;
        
        if($this->max_value > 0 && $quantity <= $this->max_value && $quantity >= $this->min_value) return true;
        if($this->max_value < 0 && $this->min_value <= $quantity) return true;

        if($this->max_value > 0 && $quantity > $this->max_value){
            $this->displayErrorMessage( 'msg_max_limit' );
            return false;
        }elseif($this->min_value && $quantity < $this->min_value){
            
            $this->displayErrorMessage( 'msg_min_limit' );
            return false;
        }


        return $bool;
    }
    
    public function add_to_cart_validation( $bool,$product_id, $quantity, $variation_id = 0, $variations = false)
    {

        /**
         * If anytime, we want to remove the validation change, we have to
         * set falst the filter hook value for 'wcmmq_add_validation_check'
         * 
         * @since 4.3.0
         * @author Saiful Islam <codersaiful@gmail.com>
         * 
         * @todo Maybe get_the_ID() is not need here, we have to fix it
         */
        $validation_check = apply_filters('wcmmq_add_validation_check',true, $product_id, get_the_ID());
        if(!$validation_check) return $bool;

        $this->product = wc_get_product( $product_id );
        if( method_exists( $this->product, 'is_sold_individually' ) && $this->product->is_sold_individually() ) return $bool;

        $this->OrganizeValidPropertyAndOrganize( $product_id, $variation_id, $quantity);       

        //First Check modulous
        $modulous = $this->getModulous( $quantity );
        if( ! $modulous ) return false;

        $total_quantity = $this->qty_inCart + $quantity;

        
        if( $total_quantity <= $this->max_value && $total_quantity >= $this->min_value && $modulous ){
            return $bool;
        }elseif($this->min_value && $total_quantity < $this->min_value ){
            $this->displayErrorMessage( 'msg_min_limit' );
            return false;
        }elseif( $this->max_value > 0 && $total_quantity > $this->max_value ){
            if( $this->qty_inCart > 0 ){
                $this->displayErrorMessage( 'msg_max_limit_with_already' );
            }
            
            $this->displayErrorMessage( 'msg_max_limit' );
            return false;
        }

        
        return $bool;
    }

    /**
     * updated modulous 
     * Only we will check when our qty will getter then min and smaller then max
     *
     * @param int|string $quantity
     * @return bool default value is true, if in condition, then it will check using filter hook
     */
    protected function getModulous( $quantity )
    {
        if( $this->min_value <= $quantity && ( $this->max_value < 0 || ( $this->max_value > 0 && $this->max_value >= $quantity ) ) ){
            return apply_filters( 'wcmmq_modulous_validation', false, $this->product_id, $this->variation_id, $quantity, $this->min_value, $this->step_value );
        }
        return true;
    }
    
    /**
     * both method's use at one method
     * Used methods: $this->assignInputArg() AND $this->finalizeArgs() 
     * Obviously need to set $this->product and $this->product_id
     * 
     * **************************
     * USES:
     * **************************
     * $this->assignInputArg();
     * $this->finalizeArgs();
     * 
     * **************************
     * IMPORTANCE NOTICE:
     * **************************
     * Everywhere, where we will assign $this->assignInputArg() after that
     * I need $this->finalizeArgs() - finalizeArgs maintain original and final args value.
     * such: $this->min_value, $this->max_value, $this->step_value, $this->input_args etc. which is very important 
     * 
     *
     * @return void
     */
    protected function organizeAndFinalizeArgs(){
        $this->assignInputArg();
        $this->finalizeArgs();
    }

    /**
     * In this method, I will System and manage
     * input's args, I mean: min max and step value
     * Based on products or from cate or from
     * Global settings.
     * 
     * ******************
     * PROTECTION
     * ******************
     * * IF ALREADY ARGS ORGANIZED, IF ALREADY ORGANIZED, NO NEED AGAIN ORGANIZE
     *
     * @return void
     * 
     * @todo cart page hole ba variation hole ki hobe ta thik kora hoyni
     * @todo location: line: 472 file: set_max_min_quantity.php clue: if( is_cart() ){
     */
    protected function assignInputArg()
    {
        // if( $this->is_args_organized) return true;
        if( ! $this->product) return;

        //Some important data assing on property
        $this->product_name = get_the_title( $this->product_id );
        $this->variation_name = $this->variation_id ? get_the_title( $this->variation_id ) : '';
        $this->qty_inCart = wcmmq_check_quantity_in_cart( $this->product_id, $this->variation_id );
        // var_dump($this->product);
        $this->is_args_organized = true;
        $this->stock_quantity = $this->product->get_stock_quantity();
        //First check from single product and if it on single page
        $this->min_value = $this->getMeta( $this->min_quantity );
        $this->max_value = $this->getMeta( $this->max_quantity );
        $this->step_value = $this->getMeta( $this->product_step );

        //Return here if found in single
        if( ! empty( $this->min_value ) || ! empty( $this->min_value ) || ! empty( $this->min_value ) ){
            $this->where_args_on = 'single';
            return true;
        }elseif( empty( $this->term_data ) ){
            $this->setGlobalArgs();
            return true;
        }elseif( ! empty( $this->term_data ) && is_array( $this->term_data ) ){
            $this->setTermwiseArgs();
            return true;
        }
        
        
        
        //At the end, if we get here, we will set again global setting.
        $this->setGlobalArgs();
        return true;
    }

    /**
     * this method will set global args to 
     * $this->min_value 
     * $this->max_value 
     * $this->step_value 
     *
     * @return void
     */
    protected function setGlobalArgs()
    {
        $this->where_args_on = 'global';
        // $this->min_value = $this->max_value = $this->min_value = null;
        $this->min_value = $this->options[$this->min_quantity] ?? 1;
        $this->max_value = $this->options[$this->max_quantity] ?? -1;
        $this->step_value = $this->options[$this->product_step] ?? 1;
        
        // var_dump($this->options);
    }


    protected function setTermwiseArgs()
    {
        if( empty( $this->term_data ) || ! is_array( $this->term_data ) ) return;

        $termwise_args = false;

        foreach( $this->term_data as $term_key => $values ){
            //thats keys of this term, already in database, jeta setting theke fix/thk kora ache
            $db_term_ids = array_keys($values);
            $product_term_ids = wp_get_post_terms( $this->product_id, $term_key, array( 'fields' => 'ids' ));
            $common_term_ids = array_intersect($db_term_ids, $product_term_ids);
            if( empty( $common_term_ids ) ) continue;

            $common_term_id = end($common_term_ids);
            $termwise_args = $values[$common_term_id];
            break;

        }
        if( is_array( $termwise_args ) && ! empty( $termwise_args ) ){
            $this->where_args_on = 'termwise';
            $this->min_value = $this->max_value = $this->step_value = null;

            $this->min_value = $termwise_args['_min'] ?? 1;
            $this->max_value = $termwise_args['_max'] ?? 1;
            $this->step_value = $termwise_args['_step'] ?? 1;
            return;
        }else{
            $this->setGlobalArgs();
        }

        
    }

    /**
     * Need before set input's args
     *
     * @return void
     */
    protected function finalizeArgs()
    {
        if(empty($this->max_value)){
            $this->max_value = ! empty( $this->stock_quantity ) ? $this->stock_quantity : -1;
        }
        
        if(empty($this->step_value)){
            $this->step_value = 1;
        }

        if( $this->stock_quantity && $this->max_value > $this->stock_quantity){
            $this->max_value = $this->stock_quantity;
        }

        if( ! is_array( $this->input_args ) ){
            $this->input_args = [];
        }

        $this->input_args = array_merge( array(
            'min_quantity' => $this->min_value,
            'max_quantity' => $this->max_value,
            'step_quantity' => $this->step_value,
            'current_quantity' => $this->qty_inCart,
            'product_name'=> $this->product_name,
            'variation_name'=> $this->variation_name,
        ), $this->input_args );
        
        return $this;
    }

    public function set_input_args( $args, $product )
    {
        if( $product->is_sold_individually() ) return $args;
        $this->product = $product;
        // if( ! $this->product){
        //     $this->product = $product;
        // }
        $this->product_id = $this->product->get_id();

        //Need to set organize args and need to finalize
        $this->organizeAndFinalizeArgs();


        $args['min_value'] = $this->min_value;
        $args['max_value'] = $this->max_value;
        $args['step'] = $this->step_value;

        // var_dump($this->input_args);
        return $args;
    }

    /**
     * To display Error message,
     * We will use this method.
     * 
     * specially for validation method when add to cart
     * It shows Error type notice.
     * 
     * @uses wcmmq_get_message() we will remove it asap
     * @uses wcmmq_message_convert_replace() we will remove it asap
     * @uses wc_add_notice()
     *
     * @param string $message_key
     * @return void
     */
    public function displayErrorMessage( $message_key )
    {
        $message = $this->getRawMsg( $message_key );
        $message = $this->messageReplace($message);
        wc_add_notice( $message, 'error' );
    }

    /**
     * retrive message from $this->options 
     * here we will use array_key as parametter.
     * such: msg_min_limit or msg_max_limit
     * This way, there are so many message available.
     * * msg_min_limit
     * * msg_max_limit
     * * msg_max_limit_with_already
     * 
     * IMPORTANT NOTICE:
     * actually in old version, we used '_wcmmq_s_' at the beggining of all key
     * but in new version we have fixed it and remove it.
     * for security, i used here: $this->key_prefix = WC_MMQ_PREFIX
     * 
     * So no need to use prefix when call getRawMsg() method
     *
     * @param String $keyword such: msg_min_limit or msg_max_limit
     * @return String
     */
    public function getRawMsg( $keyword )
    {
        $keyword = $this->key_prefix . $keyword;
        return $this->options[$keyword] ?? '';
    }

    /**
     * Message will replace with provided shortcode.
     * we will use $this->input_args for replace value.
     * if use [max_quantity] in your message, this method will replace with $this->input_args['max_quantity']
     *
     * @param string $message
     * @return string
     */
    public function messageReplace( $message )
    {
        $message = __( $message, 'wcmmq' );
        $defaults = array(
            'min_quantity' => false,
            'max_quantity' => false,
            'product_name'=> false,
            'current_quantity'=> false,
        );
        $args = wp_parse_args( $this->input_args, $defaults );
        $args = apply_filters( 'wcmmq_message_replaced_shortcode_args', $args );
        $arr_keys = array_keys($args);
        $find_arr = array_map(function($val){
            return "[$val]";
        },$arr_keys);

        $reslt = str_replace($find_arr, $args, $message);
        $reslt = apply_filters('wcmmq_validation_message', $reslt, $this->product_id );
        return $reslt;
    }

    /**
     * Get post meta using wp function get_post_meta()
     * post_id already generated as $this->product_id.
     * actually this method will only work properly after call $this->assignInputArg()
     * where we set $this->product_id otherwise, it will return empty -> ''
     *
     * @param int $meta_key
     * @return mixed
     */
    private function getMeta($meta_key)
    {
        $value = get_post_meta($this->product_id,$meta_key,true);
        if( is_numeric( $value ) ) return $value;
        return '';
    }

    /**
     * Actually term_data set at __construct() method
     * $this->term_data assign already at constructor method
     *
     * @return array|null
     */
    public function getTermData()
    {
        return $this->term_data;
    }
    
}