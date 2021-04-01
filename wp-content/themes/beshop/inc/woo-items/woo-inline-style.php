<?php
/**
 * Add inline css 
 *
 * 
 */
if ( ! function_exists( 'beshop_wooinline_css' ) ) :
function beshop_wooinline_css() {

  $style = '';


$beshop_resultcount = get_theme_mod('beshop_resultcount',1);
if(empty($beshop_resultcount)){
    $style .='p.woocommerce-result-count{display:none !important;}';
}
$beshop_porder = get_theme_mod('beshop_porder',1);
if(empty($beshop_porder)){
    $style .='.woocommerce .woocommerce-ordering{display:none !important;}';
}

$beshop_title_position = get_theme_mod('beshop_title_position','center');
if($beshop_title_position != 'left'){
    $style .='.woocommerce .page-title,.woocommerce .term-description{text-align:'.$beshop_title_position.' !important;}';
}
$beshop_titlecolor = get_theme_mod('beshop_titlecolor');
if($beshop_titlecolor){
    $style .='.woocommerce .page-title{color:'.$beshop_titlecolor.' !important;}';
}
$beshop_product_bgcolor = get_theme_mod('beshop_product_bgcolor');
if($beshop_product_bgcolor){
    $style .='.woocommerce ul.products li.product, .woocommerce-page ul.products li.product{background:'.$beshop_product_bgcolor.' !important;}';
}
$beshop_ptitle_color = get_theme_mod('beshop_ptitle_color');
if($beshop_ptitle_color){
    $style .='.woocommerce ul.products li.product .woocommerce-loop-product__title, .woocommerce ul.products li.product h3{color:'.$beshop_ptitle_color.' !important;}';
}
$beshop_prating_color = get_theme_mod('beshop_prating_color');
if($beshop_prating_color){
    $style .='.woocommerce .star-rating span:before{color:'.$beshop_prating_color.' !important;}';
}
$beshop_pprice_color = get_theme_mod('beshop_pprice_color');
if($beshop_pprice_color){
    $style .='.woocommerce ul.products li.product .price{color:'.$beshop_pprice_color.' !important;}';
}
$beshop_pbtn_bgcolor = get_theme_mod('beshop_pbtn_bgcolor');
$beshop_pbtn_color = get_theme_mod('beshop_pbtn_color');
if($beshop_pbtn_bgcolor || $beshop_pbtn_color){
    $style .='.woocommerce a.button, .woocommerce a.added_to_cart, .woocommerce button.button,.woocommerce span.onsale{background:'.$beshop_pbtn_bgcolor.' !important;color:'.$beshop_pbtn_color.' !important}';
}
$beshop_pbtn_hvbgcolor = get_theme_mod('beshop_pbtn_hvbgcolor');
$beshop_pbtn_hvcolor = get_theme_mod('beshop_pbtn_hvcolor');
if($beshop_pbtn_hvbgcolor){
    $style .='.woocommerce a.button:hover, .woocommerce a.added_to_cart:hover, .woocommerce button.button:hover, .woocommerce input.button:hover a.added_to_cart.wc-forward{background:'.$beshop_pbtn_hvbgcolor.' !important;color:'.$beshop_pbtn_hvcolor.' !important}';
}
$beshop_shopb_img = get_theme_mod( 'beshop_shopb_img' );

if($beshop_shopb_img){
    $beshop_shopb_img_url = wp_get_attachment_image_src( $beshop_shopb_img, 'full');
    $style .='.beshop-banner.bg-overlay{background:url('.$beshop_shopb_img_url[0].')}';
}
$beshop_bannertext_color = get_theme_mod( 'beshop_bannertext_color','#fff' );
if($beshop_bannertext_color != '#fff'){
    $style .='.beshop-banner .bbanner-text h1,.beshop-banner .bbanner-text h4,.beshop-banner .bbanner-text p{color:'.$beshop_bannertext_color.' !important}';
}
$beshop_bannerbtn_color = get_theme_mod( 'beshop_bannerbtn_color','#fff' );
if($beshop_bannerbtn_color != '#fff'){
    $style .='a.btn.bshop-btn{color:'.$beshop_bannerbtn_color.' !important}';
}
$beshop_bannerbtn_bgcolor = get_theme_mod( 'beshop_bannerbtn_bgcolor','#77A464' );
if($beshop_bannerbtn_bgcolor != '#77A464'){
    $style .='a.btn.bshop-btn{background:'.$beshop_bannerbtn_bgcolor.' !important}';
}
$beshop_products_pagination = get_theme_mod( 'beshop_products_pagination','center' );
if($beshop_products_pagination != 'center'){
    $style .='.woocommerce nav.woocommerce-pagination{text-align:'.$beshop_products_pagination.'}';
}
$beshop_ftwidget_color = get_theme_mod( 'beshop_ftwidget_color');
if($beshop_ftwidget_color ){
    $style .='.beshop-products-filter ul li, .beshop-products-filter ul li a{color:'.$beshop_ftwidget_color.'}';
}
$beshop_ftwidget_bgcolor = get_theme_mod( 'beshop_ftwidget_bgcolor');
if( $beshop_ftwidget_bgcolor ){
    $style .='.beshop-products-filter ul{background:'.$beshop_ftwidget_bgcolor.' !important}';
}
$beshop_ftwidget_hvcolor = get_theme_mod( 'beshop_ftwidget_hvcolor');
if($beshop_ftwidget_hvcolor){
    $style .='.beshop-products-filter ul li a:hover{color:'.$beshop_ftwidget_hvcolor.'}';
}
$beshop_breadcrump_color = get_theme_mod( 'beshop_breadcrump_color');
$beshop_breadcrump_bgcolor = get_theme_mod( 'beshop_breadcrump_bgcolor');
if($beshop_breadcrump_color){
    $style .='.woocommerce .woocommerce-breadcrumb, .woocommerce .woocommerce-breadcrumb a{color:'.$beshop_breadcrump_color.' !important}';
}
if($beshop_breadcrump_bgcolor){
    $style .='.beshop-wbreadcrump{background:'.$beshop_breadcrump_bgcolor.' !important}';
}
$beshop_pagitext_color = get_theme_mod( 'beshop_pagitext_color');
$beshop_pagibg_color = get_theme_mod( 'beshop_pagibg_color');
if($beshop_pagitext_color || $beshop_pagibg_color){
    $style .='.woocommerce nav.woocommerce-pagination ul li a:focus, .woocommerce nav.woocommerce-pagination ul li a:hover, .woocommerce nav.woocommerce-pagination ul li span.current{color:'.$beshop_pagibg_color.' !important;background:'.$beshop_pagitext_color.' !important}';
    $style .='.woocommerce nav.woocommerce-pagination ul li a, .woocommerce nav.woocommerce-pagination ul li span{background:'.$beshop_pagibg_color.' !important;color:'.$beshop_pagitext_color.' !important}';
}
/*Single page style*/
$beshop_sptitle_color = get_theme_mod( 'beshop_sptitle_color');
if($beshop_sptitle_color){
    $style .='.single-product .product_title{color:'.$beshop_sptitle_color.' !important}';
}
$beshop_ptitle_fsize = get_theme_mod( 'beshop_ptitle_fsize');
if($beshop_ptitle_fsize){
    $style .='.single-product .product_title{font-size:'.$beshop_ptitle_fsize.'px !important}';
}
$beshop_srating_show = get_theme_mod( 'beshop_srating_show','1');
if(empty($beshop_srating_show)){
    $style .='.single-product .woocommerce-product-rating{display:none}';
}
$beshop_sdesc_show = get_theme_mod( 'beshop_sdesc_show','1');
if(empty($beshop_sdesc_show)){
    $style .='.single-product .woocommerce-product-details__short-description{display:none}';
}
$beshop_sku_show = get_theme_mod( 'beshop_sku_show','1');
if(empty($beshop_sku_show)){
    $style .='.single-product .sku_wrapper{display:none}';
}
$beshop_spcat_show = get_theme_mod( 'beshop_spcat_show','1');
if(empty($beshop_spcat_show)){
    $style .='.single-product .posted_in{display:none}';
}
$beshop_sptag_show = get_theme_mod( 'beshop_sptag_show','1');
if(empty($beshop_sptag_show)){
    $style .='.single-product .tagged_as{display:none}';
}
$beshop_sptab_show = get_theme_mod( 'beshop_sptab_show','1');
if(empty($beshop_sptab_show)){
    $style .='.single-product .woocommerce-tabs{display:none}';
}
$beshop_sprelated_show = get_theme_mod( 'beshop_sprelated_show','1');
if(empty($beshop_sprelated_show)){
    $style .='.single-product .related.products{display:none}';
}





        wp_add_inline_style( 'beshop-main', $style );
}
add_action( 'wp_enqueue_scripts', 'beshop_wooinline_css' );
endif;
