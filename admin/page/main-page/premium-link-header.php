<?php
$coupon_Code = 'BLACKFRIDAY2024';

?>
<div class="wrap wcmmq_wrap wcmmq-content">
<h1 class="wp-heading "></h1>
<div class="fieldwrap">
<section class="wcmmq-section-panel wcmmq-premium-notice-panel no-background" style="box-shadow: 1px 1px 8px 0 rgba(170,157,137,0.14);">
	<div class="container-fluid">
		<div class="row" style="align-items: inherit;flex-wrap: wrap;margin:0;">
			<div class="wcmmq-pic-col col-lg-8">
				<div class="col-lg-2">
					<a href="https://codeastrology.com/min-max-quantity/?discount=<?php echo esc_attr( $coupon_Code ); ?>&utm_source=wordpress_org&amp;utm_campaign=organic&amp;utm_medium=organic&amp;utm_content=premium_explore_logo&amp;partner=wordpress_org" target="_blank">
						<i class="premium_adv"></i>
					</a>
				</div>
				<div class="col-lg-4">
					<h2>BLACK FRIDAY</h2>
					<p>Checkout our Offer.</p>
				</div>
				<div class="col-lg-6">
					<a href="https://codeastrology.com/coupons/?discount=<?php echo esc_attr( $coupon_Code ); ?>&campaign=<?php echo esc_attr( $coupon_Code ); ?>&ref=1&utm_source=Default_Offer_LINK&partner=wordpress_org" class="wcmmq-btn white round" style="color:black;background-color:white;" target="_blank">Get Offer and Bundle</a>
				</div>
			</div>
			<div class="wcmmq-sup-col col-lg-4">
				<div class="half-containers money-back">
					<a href="https://codeastrology.com/min-max-quantity/?discount=<?php echo esc_attr( $coupon_Code ); ?>&campaign=<?php echo esc_attr( $coupon_Code ); ?>&utm_source=wordpress_org&amp;utm_campaign=organic&amp;utm_medium=organic&amp;utm_content=money_back&amp;partner=wordpress_org" target="_blank">
						<span>Money Back Guarantee</span>
					</a>
					<p style="text-align: center;padding:15px;">100% No-Risk 30-Days Money Back Guarantee</p>
				</div>
			</div>
		</div>
	</div>
</section>
</div>
</div>
<?php 
$min_logo = WC_MMQ_BASE_URL . 'assets/images/large-logo.png';
$money_back = WC_MMQ_BASE_URL . 'assets/images/money-back.png';
$white_logo = WC_MMQ_BASE_URL . 'assets/images/brand/header-logo-white-x.png';
?>
<style>
      .row {
        display: flex;
        flex-direction: row;
        align-items: center;
    
    }
    .wcmmq-premium-notice-panel .wcmmq-pic-col {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        border: 5px solid #fff;
        text-align: center;
        background: #9C27B0;
        background: linear-gradient(28deg,#F9A825 0,#FF9800 100%);
        filter: progid:DXImageTransform.Microsoft.gradient(startColorstr="#df4c57",endColorstr="#f78c62",GradientType=1);
        padding: 25px 10px;
        color: #fff;
        flex-wrap: wrap;
        position: relative;
        overflow: hidden;
    }
    .wcmmq-premium-notice-panel .wcmmq-pic-col:after {
        content: "";
        height: 66px;
        width: 163px;
        position: absolute;
        bottom: 21px;
        right: 0px;
        background: transparent url(<?php echo esc_url( $white_logo ); ?>) no-repeat;
        transform: rotate(90deg);
        text-align: center;
        opacity: 0.6;
        z-index: 0;
    }
    .premium_adv {
        background: url("<?php echo esc_url( $min_logo ); ?>") no-repeat center;
        display: inline-block;
        margin: 0 auto;
        background-size: 80px auto;
        width: 107px;
        height: 106px;
    }
    .wcmmq-premium-notice-panel .wcmmq-sup-col {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        border: 5px solid #fff;
    }
    .wcmmq-premium-notice-panel .col-lg-4 .half-containers.money-back {
        background: #d0f3cb;
        color: #000000;
    }
    .wcmmq-premium-notice-panel .col-lg-4 .half-containers {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        overflow: hidden;
        position: relative;
    }
    .wcmmq-premium-notice-panel .col-lg-4 {
        padding: 0;
        -webkit-box-flex: 1;
        -ms-flex: 1 1 0;
        flex: 1 1 0;
    }
    .wcmmq-premium-notice-panel .col-lg-4 .half-containers.money-back a {
        display: block;
        position: relative;
        color: #fff;
        outline: 0;
        text-decoration: none;
        background: url("<?php echo esc_url( $money_back ); ?>") no-repeat 50% 0;
        float: left;
        width: 100%;
        height: 60%;
        margin: 15px 0;
        background-size: contain;
    }
    .wcmmq-premium-notice-panel .col-lg-4 .half-containers.money-back a span {
        display: none;
    }

    .wcmmq-premium-notice-panel h2 {
        font-size: 27px;
        text-transform: uppercase;
        letter-spacing: -0.025em;
        line-height: 1;
        color: #000;
    }
    .wcmmq-premium-notice-panel .wcmmq-pic-col p {
        font-size: 16px;
        padding-bottom: 1em;
        display: inline;
    }
</style>