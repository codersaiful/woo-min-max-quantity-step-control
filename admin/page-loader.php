<?php 
namespace WC_MMQ\Admin;

use WC_MMQ\Core\Base;
use WC_MMQ\Modules\Module_Controller;
use WC_MMQ\Includes\Min_Max_Controller;;

class Page_Loader extends Base
{

    public $main_slug = 'wcmmq-min-max-control';
    public $page_folder_dir;
    public $topbar_file;
    public $topbar_sub_title;

    protected $is_pro;
    protected $pro_version;
    public $license;
    public $module_controller;

    public function __construct()
    {
        $this->is_pro = defined( 'WC_MMQ_PRO_VERSION' );
        if($this->is_pro){
            $this->pro_version = WC_MMQ_PRO_VERSION;
            $this->license = property_exists('\WC_MMQ_PRO','direct') ? \WC_MMQ_PRO::$direct : null;
            $this->handle_license_n_update();
        }
        $this->page_folder_dir = $this->base_dir . 'admin/page/';
        $this->topbar_file = $this->page_folder_dir . 'topbar.php';
        $this->topbar_sub_title = __("Manage and Settings", "wcmmq");

        $this->module_controller = new Module_Controller();
    }

    public function run()
    {
        add_action( 'admin_menu', [$this, 'admin_menu'] );
        add_action( 'admin_enqueue_scripts', [$this, 'admin_enqueue_scripts'] );
    }

    
    public function main_page_html()
    {
        
        $main_page_file = $this->page_folder_dir . 'main-page.php';
        if( ! is_file( $main_page_file ) ) return;
        include $main_page_file;
    }
    
    public function module_page_html()
    {
        
        $this->topbar_sub_title = __( 'Manage Module','wcmmq' );
        include $this->topbar_file;
        if( ! $this->is_pro ){
            include $this->page_folder_dir . 'main-page/premium-link-header.php';
        }
        include $this->module_controller->dir . '/module-page.php';
    }
    
    /**
     * Connectivity with Product Stock Sync with Google Sheet for WooCommerce
     *
     * 
     * @since 5.9.0
     * @return void
     * 
     * @author Saiful Islam <codersaiful@gmail.com>
     */
    public function product_quick_edit()
    {
        add_filter( 'pssg_products_columns', [$this,'handle_columns'] );
        $this->topbar_sub_title = __( 'Min Max Quick Edit','wcmmq' );
        include $this->topbar_file;
        include $this->page_folder_dir . '/product-quick-edit.php';
    }
    
    public function handle_columns( $columns )
    {
        if( ! class_exists('PSSG_Init') ) $columns;

        $new_columns = [];
        $new_columns['title'] = $columns['title'];
        

        $controller = Min_Max_Controller::init();
        $new_columns[$controller->min_quantity] = $columns[$controller->min_quantity];
        $new_columns[$controller->max_quantity] = $columns[$controller->max_quantity];
        $new_columns[$controller->product_step] = $columns[$controller->product_step];

        $new_columns['stock'] = $columns['stock'];
        return $new_columns;
    }

    public function browse_plugins_html()
    {
        add_filter( 'plugins_api_result', [$this, 'plugins_api_result'], 1, 3 );
        $this->topbar_sub_title = __( 'Browse our Plugins','wcmmq' );
        include $this->topbar_file;
        if( ! $this->is_pro ){
            include $this->page_folder_dir . 'main-page/premium-link-header.php';
        }
        include $this->page_folder_dir . 'browse-plugins.php';
    }

    public function addons_list_html()
    {
        add_filter( 'plugins_api_result', [$this, 'plugins_api_result'], 1, 3 );
        $this->topbar_sub_title = __( 'Addons','wcmmq' );
        include $this->topbar_file;
        if( ! $this->is_pro ){
            include $this->page_folder_dir . 'main-page/premium-link-header.php';
        }
        include $this->page_folder_dir . 'addons-list.php';
    }
    

    public function admin_menu()
    {
        $capability = apply_filters( 'wcmmq_menu_capability', 'manage_woocommerce' );
    
        //This bellow line will removed //If we enable bellow line, we have a include set_menu_and_fac.php file
        // add_submenu_page( 'woocommerce', 'WC Min Max Step Quantity', 'Min Max Step Quantity', $capability, 'wcmmq_min_max_step', 'wcmmq_faq_page_details' );
        
        //from new class
        // add_submenu_page( 'woocommerce', 'WC Min Max Step Quantity', 'Min Max Step Quantity', $capability, 'wcmmq_min_max_step', [$this, 'main_page_html'] );
        

        //THAT TO BE ENABLE AT THE END
        add_submenu_page( 'woocommerce', 'WC Min Max Step Quantity', 'Min Max Step Quantity', $capability, 'wcmmq_min_max_step', [$this,'redirect_to_new_page'] );
        
        $proString = $this->is_pro ? esc_html__( ' Pro', 'wcmmq' ) : '';
        
        
        $min_max_img = $this->base_url . 'assets/images/min-max.png';
        $page_title = "Min Max and Step Control" . $proString;
        $menu_title = "Min Max Control"; 
        $menu_slug = $this->main_slug;
        $callback = [$this, 'main_page_html']; 
        $icon_url = $min_max_img;
        $position = 55.11;
        add_menu_page($page_title, $menu_title, $capability, $menu_slug, $callback, $icon_url, $position);

        //Module page adding
        add_submenu_page( $this->main_slug, $this->module_controller->menu_title . $proString, $this->module_controller->menu_title, $capability, 'wcmmq_modules', [$this, 'module_page_html'] );

        add_submenu_page( $this->main_slug, esc_html__( 'Min Max Bulk Edit', 'wcmmq' ) . $proString,  __( 'Min Max Bulk Edit', 'wcmmq' ), $capability, 'wcmmq-product-quick-edit', [$this, 'product_quick_edit'] );
        add_submenu_page( $this->main_slug, esc_html__( 'Pro Demo', 'wcmmq' ),  esc_html__( 'Pro Demo', 'wcmmq' ), 'read', 'https://wpprincipal.xyz/?site=wcmmq&utm=PluginDashboard' );

        
        add_submenu_page( $this->main_slug, esc_html__( 'Browse Plugins', 'wcmmq' ) . $proString,  __( 'Browse Plugins', 'wcmmq' ), 'read', 'wcmmq-browse-plugins', [$this, 'browse_plugins_html'] );
        add_submenu_page( $this->main_slug, esc_html__( 'Addons', 'wcmmq' ) . $proString,  __( 'Addons', 'wcmmq' ), 'read', 'wcmmq-addons-list', [$this, 'addons_list_html'] );

        add_submenu_page($this->main_slug, 'Documentation' . $proString, 'Documentation', 'read','https://codeastrology.com/min-max-quantity/documentation/');
        if($this->is_pro){
            add_submenu_page($this->main_slug, 'Support' . $proString, 'Support', 'read','https://codeastrology.com/my-support');
        }else{
            add_submenu_page($this->main_slug, 'Support & Buy', 'Support & Buy', 'read','https://codeastrology.com/min-max-quantity/pricing/');
        }
        

        //License Menu if pro version is getter or equal V2.0.8.4
        if( is_object( $this->license ) && version_compare($this->pro_version, '2.0.8.4', '>=')){
            add_submenu_page( $this->main_slug, __('Min Max Control License', 'wcmmq_pro'), __( 'License', 'wcmmq_pro' ), $capability, 'wcmmq-license', [$this->license, 'license_page'] );
        }
    }

    /**
     * This is specially for WPML page
     * 
     * Redirects the user to the default language version of the current URL if the 'lang' parameter is not set or is different from the default language.
     *
     * @return void
     */
    public function redirect_wpml() {
        $default_lang = apply_filters('wpml_default_language', NULL);
        if ( empty( $default_lang ) ) return;
        // Get the current URL
        $current_url = $_SERVER['REQUEST_URI'];
        
        // Parse the URL to get its components
        $parsed_url = parse_url($current_url);
        
        // Parse the query string into an associative array
        $query_params = [];
        if (isset($parsed_url['query'])) {
            parse_str($parsed_url['query'], $query_params);
        }
        
        // Check if the 'lang' parameter is set
        if (!isset($query_params['lang']) || ( isset($query_params['lang'] ) && $query_params['lang'] != $default_lang ) ) {
            // If not set, add the 'lang' parameter with 'en' as its value
            $query_params['lang'] = $default_lang;
            
            // Build the new query string
            $new_query_string = http_build_query($query_params);
            
            // Construct the new URL
            $new_url = $parsed_url['path'] . '?' . $new_query_string;
            
            // Redirect to the new URL
            wp_redirect($new_url);
            exit;
        }
        return;
    }
    public function admin_enqueue_scripts()
    {
        global $current_screen;
        
        /**
         * Select2 CSS file including. 
         * 
         * @since 1.0.3
         */    
        wp_enqueue_style( 'select2-css', $this->base_url . 'assets/css/select2.min.css' );

        /**
         * Select2 jQuery Plugin file including. 
         * Here added min version. But also available regular version in same directory
         * 
         * @since 1.9
         */
        wp_enqueue_script( 'select2', $this->base_url . 'assets/js/select2.full.min.js', array( 'jquery' ), '4.0.5', true );

        
        wp_register_script( $this->plugin_prefix . '-admin-script', $this->base_url . 'assets/js/admin.js', array( 'jquery','select2' ), $this->dev_version, true );
        wp_enqueue_script( $this->plugin_prefix . '-admin-script' );

        
        $ajax_url = admin_url( 'admin-ajax.php' );
        $WCMMQ_ADMIN_DATA = array( 
            'ajax_url'       => $ajax_url,
            'site_url'       => site_url(),
            'cart_url'       => wc_get_cart_url(),
            'priceFormat'    => get_woocommerce_price_format(),
            'decimal_separator'=> '.',
            'default_decimal_separator'=> wc_get_price_decimal_separator(),
            'decimal_count'=> wc_get_price_decimals(),
            );
        wp_localize_script( $this->plugin_prefix . '-admin-script', 'WCMMQ_ADMIN_DATA', $WCMMQ_ADMIN_DATA );
        
        wp_register_style( 'ultraaddons-common-css', $this->base_url . 'assets/css/admin-common.css', false, $this->dev_version );
        wp_enqueue_style( 'ultraaddons-common-css' );

        wp_register_style( $this->plugin_prefix . 'wcmmq_css', $this->base_url . 'assets/css/admin.css', false, $this->dev_version );
        wp_enqueue_style( $this->plugin_prefix . 'wcmmq_css' );

        $s_id = isset( $current_screen->id ) ? $current_screen->id : '';
        if( strpos( $s_id, $this->plugin_prefix ) !== false ){
            add_filter('admin_footer_text',[$this, 'admin_footer_text']);
            
            wp_register_style( $this->plugin_prefix . '-icon-font', $this->base_url . 'assets/fontello/css/wcmmq-icon.css', false, $this->dev_version );
            wp_enqueue_style( $this->plugin_prefix . '-icon-font' );

            
            wp_register_style( $this->plugin_prefix . '-icon-animation', $this->base_url . 'assets/fontello/css/animation.css', false, $this->dev_version );
            wp_enqueue_style( $this->plugin_prefix . '-icon-animation' );




            wp_register_style( $this->plugin_prefix . '-new-admin', $this->base_url . 'assets/css/new-admin.css', false, $this->dev_version );
            wp_enqueue_style( $this->plugin_prefix . '-new-admin' );

        }

        
    }

    /**
     * Old menu page will redirect to new menu page.
     * 
     * 
     *
     * @return void
     */
    public function redirect_to_new_page()
    {
        wp_redirect(admin_url('admin.php?page=' . $this->main_slug));
    }

    public function admin_footer_text($text)
    {
        $rev_link = 'https://wordpress.org/support/plugin/woo-min-max-quantity-step-control-single/reviews/#new-post';
        $text = sprintf(
			__( 'Thank you for using Min Max Control. <a href="%s" target="_blank">%sPlease review us</a>.' ),
			$rev_link,
            '<i class="wcmmq_icon-star-filled"></i><i class="wcmmq_icon-star-filled"></i><i class="wcmmq_icon-star-filled"></i><i class="wcmmq_icon-star-filled"></i><i class="wcmmq_icon-star-filled"></i>'
		);
        return '<span id="footer-thankyou" class="wcmmq-footer-thankyou">' . $text . '</span>';
    }
    public function plugins_api_result( $res, $action, $args )
    {
        if ( $action !== 'query_plugins' ) {
            return $res;
        }
        
        if( isset( $_GET['page'] ) && $_GET['page'] == 'wcmmq-browse-plugins' ){
            //Will Continue to bottom actually
        }else{
            return $res;
        }
        $browse_plugins = get_transient( 'codersaiful_browse_plugins' );
        
        
        if( $browse_plugins ){
            return $browse_plugins;//As $res
        }
        
        
        
        $wp_version = get_bloginfo( 'version', 'display' );
        $action = 'query_plugins';
        $args = array(
            'page' => 1,
            'wp_version' => $wp_version
        );
        $args['author']          = 'codersaiful';
        $url = 'http://api.wordpress.org/plugins/info/1.2/';
        $url = add_query_arg(
                array(
                        'action'  => $action,
                        'request' => $args,
                ),
                $url
        );

        $http_url = $url;
        $ssl      = wp_http_supports( array( 'ssl' ) );
        if ( $ssl ) {
                $url = set_url_scheme( $url, 'https' );
        }

        $http_args = array(
                'timeout'    => 15,
                'user-agent' => 'WordPress/' . $wp_version . '; ' . home_url( '/' ),
        );
        $request   = wp_remote_get( $url, $http_args );

        if ( $ssl && is_wp_error( $request ) ) {
                if ( ! wp_is_json_request() ) {
                        trigger_error(
                                sprintf(
                                        /* translators: %s: Support forums URL. */
                                        __( 'An unexpected error occurred. Something may be wrong with WordPress.org or this server&#8217;s configuration. If you continue to have problems, please try the <a href="%s">support forums</a>.' ),
                                        __( 'https://wordpress.org/support/forums/' )
                                ) . ' ' . __( '(WordPress could not establish a secure connection to WordPress.org. Please contact your server administrator.)' ),
                                headers_sent() || WP_DEBUG ? E_USER_WARNING : E_USER_NOTICE
                        );
                }

                $request = wp_remote_get( $http_url, $http_args );
        }


        $res = json_decode( wp_remote_retrieve_body( $request ), true );
        if ( is_array( $res ) ) {
                // Object casting is required in order to match the info/1.0 format.
                $res = (object) $res;
                set_transient( 'codersaiful_browse_plugins' , $res, 32000);
        }
        
        return $res;
    }

    /**
     * If will work, when only found pro version
     * 
     * @since 5.4.0
     * @author Saiful Islam <codersaiful@gmail.com>
     *
     * @return void
     */
    public function handle_license_n_update()
    {
        
        $this->license_key = get_option( 'wcmmq_license_key' );
        if(empty($this->license_key)) return;
        $this->license_data_key = 'wcmmq_license_data';
        $this->license_status_key = 'wcmmq_license_status';
        $this->license_status = get_option( $this->license_status_key );
        $this->license_data = get_option($this->license_data_key);
        
        /**
         * Actually if not found lisen data, we will return null here
         * 
         * @since 5.4.0
         * @author Saiful Islam <codersaiful@gmail.com>
         */
        if( empty( $this->license_status ) || empty( $this->license_data ) ) return;

        $expires = isset($this->license_data->expires) ? $this->license_data->expires : '';
        $this->item_id = isset($this->license_data->item_id) ? $this->license_data->item_id : '';

        if('lifetime' == $expires) return;
        $exp_timestamp = strtotime($expires);
        /**
         * keno ami ei timestamp niyechi.
         * asole expire a zodi faka ase, tahole ta 1 jan, 1970 as strtotime er output.
         * 
         * ar jehetu amora 2010 er por kaj suru korechi. tai sei expire date ba ager date asar kOnO karonoi nai.
         * tai zodi 2012 er kom timestamp ase amora return null kore debo.
         * za already diyechi: if( $exp_timestamp < $year2010_timestamp ) return; by this line. niche follow korun.
         */
        $year2010_timestamp = strtotime('2023-09-08 23:59:59');
        if( $exp_timestamp < $year2010_timestamp ) return;

        //ekhon amora bortoman date er sathe tulona korbo
        if($exp_timestamp < time()){

            $this->exp_timestamp = $exp_timestamp;
            // var_dump($this->license_data);
            if($this->license_status == 'valid'){
                $this->invalid_status = 'invalid';
                $this->license_data->license = $this->invalid_status;
                update_option( $this->license_status_key, $this->invalid_status );
                update_option( $this->license_data_key, $this->license_data );

                
            }
            add_action( 'admin_notices', [$this, 'renew_license_notice'] );
        }
        

    }

    public function renew_license_notice()
    {

        if(empty($this->item_id)) return;
        $wpt_logo = WC_MMQ_BASE_URL . 'assets/images/brand/social/min-max.png';
        $expired_date = date( 'd M, Y', $this->exp_timestamp );
        $link_label = __( 'Renew License', 'wpt_pro' );
        $link = "https://codeastrology.com/checkout/?edd_license_key={$this->license_key}&download_id={$this->item_id}";
		$message = esc_html__( ' Renew it to get latest update.', 'wpt_pro' ) . '</strong>';
        ob_start();
        ?>
        <div class="error wcmmq-renew-license-notice">
            <div class="wcmmq-license-notice-inside">
            <img src="<?php echo esc_url( $wpt_logo ); ?>" class="wcmmq-license-brand-logo">
                Your License of <strong>Min Max Control pro</strong> has been expired at <span style="color: #d00;font-weight:bold;"><?php echo esc_html( $expired_date ); ?></span>
                %1$s <a href="%2$s" target="_blank">%3$s</a>
            </div>
        </div>
        <?php
        $full_message = ob_get_clean();
        printf( $full_message, $message, $link, $link_label );
    }
}