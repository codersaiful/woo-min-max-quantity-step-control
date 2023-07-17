<?php
namespace WC_MMQ\Includes;

use WC_MMQ;
use WC_MMQ\Core\Base;

class Min_Max_Controller extends Base
{

    public $product_id;
    /**
     * It's need, only when cart page, otherwise it will null
     *
     * @var int
     */
    public $variation_id;

    //Important value
    public $min_value;
    public $max_value;
    public $step_value;

    //Important key
    public $min_quantity = WC_MMQ_PREFIX . 'min_quantity';
    public $default_quantity = WC_MMQ_PREFIX . 'default_quantity';
    public $max_quantity = WC_MMQ_PREFIX . 'max_quantity';
    public $step_quantity = WC_MMQ_PREFIX . 'step_quantity';

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

    public $input_args;
    public $options;
    public $product;


    public function __construct()
    {
        $this->is_pro = defined('WC_MMQ_PRO_VERSION');
        $this->options = WC_MMQ::getOptions();
        // return;
        add_action('woocommerce_loop_add_to_cart_args',[$this, 'set_input_args'], 9999, 2);
        add_action('woocommerce_quantity_input_args',[$this, 'set_input_args'], 9999, 2);
        add_action('woocommerce_available_variation',[$this, 'set_input_args'], 9999, 2);
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
     */
    protected function assignInputArg()
    {
        if( $this->is_args_organized) return true;
        if( ! $this->product) return;

        $this->is_args_organized = true;
        //First check from single product and if it on single page
        $this->min_value = $this->getMeta( $this->min_quantity );
        $this->max_value = $this->getMeta( $this->max_quantity );
        $this->step_value = $this->getMeta( $this->step_quantity );

        
    }

    public function set_input_args( $args, $product )
    {
        if( $product->is_sold_individually() ) return $args;

        if( ! $this->product){
            $this->product = $product;
        }
        $this->product_id = $this->product->get_id();
        //Need to set organize args
        $this->assignInputArg();


        // var_dump($this);
        var_dump($args);
        return $args;
    }


    private function getMeta($meta_key)
    {
        return get_post_meta($this->product_id,$meta_key,true);
    }
}