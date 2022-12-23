<?php
namespace WOPB\blocks;

defined('ABSPATH') || exit;

class Checkout_Billing{
    public function __construct() {
        add_action('init', array($this, 'register'));
    }
    public function get_attributes($default = false){

        $attributes = array(
            'blockId' => [
                'type' => 'string',
                'default' => '',
            ],
            //General
            'showTitle'=> [
                'type' => 'boolean',
                'default' => true,
            ],

            // Billing Title
            'billingTitle'=>[
                'type' => 'string',
                'default' => 'Billing Details',
            ],
            'billingTitleColor'=> [
                'type' => 'string',
                'default' => '#FFFFFF',
                'style' => [(object)['selector'=>'{{WOPB}} .wopb-product-wrapper .woocommerce-billing-fields .wopb-billing-title { color:{{billingTitleColor}}; }']],
            ],
            'billingTitleBgColor'=> [
                'type' => 'string',
                'default' => '#353535',
                'style' => [(object)['selector'=>'{{WOPB}} .wopb-product-wrapper .woocommerce-billing-fields .wopb-billing-title { background-color:{{billingTitleBgColor}}; }']],
            ],
            'headingAlign' => [
                'type' => 'string',
                'default' =>  'center',
                'style' => [(object)['selector' => '{{WOPB}} .wopb-product-wrapper .woocommerce-billing-fields .wopb-billing-title { text-align:{{headingAlign}}; }']]
            ],
            'billingTitleTypo'=> [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' => '20', 'unit' => 'px'], 'height' => (object)['lg' => '', 'unit' => 'px'],'decoration' => 'none', 'transform' => 'capitalize'],
                'style' => [(object)['selector'=>'{{WOPB}} .wopb-product-wrapper .woocommerce-billing-fields .wopb-billing-title ']]
            ],
            'billingRadius'=> [
                'type' => 'object',
                'default' => (object)['lg'=>(object)['top'=>4,'right'=>4,'bottom'=>4,'left'=>4, 'unit'=>'px']],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-product-wrapper .woocommerce-billing-fields .wopb-billing-title { border-radius:{{billingRadius}}; }'
                    ],
                ],
            ],
            'billingTitlePadding'=> [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => 10,'bottom' => 10,'left' => 0, 'right' => 0, 'unit' =>'px']],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-product-wrapper .woocommerce-billing-fields .wopb-billing-title {padding:{{billingTitlePadding}}; }'
                    ],
                ],
            ],
            'billingTitleSpace'=> [
                'type' => 'string',
                'default' => '5',
                'style' => [(object)['selector'=>'{{WOPB}} .wopb-product-wrapper .woocommerce-billing-fields .wopb-billing-title { margin-bottom:{{billingTitleSpace}}px;}']]
            ],

            

            // Label
            'labelColor'=> [
                'type' => 'string',
                'default' => '#696969',
                'style' => [(object)['selector'=>'{{WOPB}} .wopb-product-wrapper .woocommerce-billing-fields__field-wrapper label {color:{{labelColor}}; }']],
            ],
            'requiredColor'=> [
                'type' => 'string',
                'default' => '#ff4646',
                'style' => [(object)['selector'=>'{{WOPB}} .wopb-product-wrapper .woocommerce-billing-fields__field-wrapper .required {color:{{requiredColor}}; }']],
            ],
            'labelTypo'=> [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' => '14', 'unit' => 'px'], 'height' => (object)['lg' => '', 'unit' => 'px'],'decoration' => 'none', 'transform' => 'capitalize'],
                'style' => [(object)['selector'=>'{{WOPB}} .wopb-product-wrapper .woocommerce-billing-fields__field-wrapper label ']]
            ],
            'labelMargin'=> [
                'type' => 'object',
                'default' => (object)['lg'=>(object)['top'=>0,'right'=>0,'bottom'=>5,'left'=>0, 'unit'=>'px']],
                'style' => [
                    (object)[
                        
                        'selector'=>'{{WOPB}} .wopb-product-wrapper .woocommerce-billing-fields__field-wrapper label { margin:{{labelMargin}}; }'
                    ],
                ],
            ],

            

            // Input Fields
            
            'inputHeight'=> [
                'type' => 'string',
                'default' => '',
                'style' => [(object)['selector'=>'{{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields input, {{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields select , {{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields .select2-selection { min-height:{{inputHeight}}px;}']]
            ],
            'inputColor'=> [
                'type' => 'string',
                'default' => '#000',
                'style' => [(object)['selector'=>'{{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields input, {{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields select, {{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields .select2-selection__rendered {color:{{inputColor}}; }']],
            ],
            'inputBgColor'=> [
                'type' => 'string',
                'default' => '#fff',
                'style' => [(object)['selector'=>'{{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields input, {{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields select, {{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields .select2-selection {background:{{inputBgColor}}; }']],
            ],
            'inputFocusColor'=> [
                'type' => 'string',
                'default' => '#000',
                'style' => [(object)['selector'=>'{{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields input:focus {color:{{inputFocusColor}}; }']],
            ],
            'placeholderColor'=> [
                'type' => 'string',
                'default' => '#cbcbcb',
                'style' => [(object)['selector'=>'{{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields input::-webkit-input-placeholder {color:{{placeholderColor}}; }']],
            ],
            'placeholderFocusColor'=> [
                'type' => 'string',
                'default' => '#000',
                'style' => [(object)['selector'=>'{{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields input:focus::-webkit-input-placeholder {color:{{placeholderFocusColor}}; }']],
            ],
            'inputTypo'=> [
                'type' => 'object',
                'default' => (object)['openTypography' => 1,'size' => (object)['lg' => '16', 'unit' => 'px'], 'height' => (object)['lg' => '', 'unit' => 'px'],'decoration' => 'none', 'transform' => 'capitalize'],
                'style' => [(object)['selector'=>'{{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields input']]
            ],
            'inputBorder'=> [
                'type' => 'object',
                'default' => (object)['openBorder'=>1, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#e0e0e0','type' => 'solid' ],
                'style' => [
                    (object)['selector'=>'{{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields input , {{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields select, {{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields .select2-selection']],
            ],
            'inputRadius'=> [
                'type' => 'object',
                'default' => (object)['lg'=>(object)['top'=>4,'right'=>4,'bottom'=>4,'left'=>4, 'unit'=>'px']],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields input, {{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields select, {{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields .select2-selection { border-radius:{{inputRadius}}; }'
                    ],
                ],
            ],
            'inputMargin'=> [
                'type' => 'object',
                'default' => (object)['lg'=>(object)['top'=>0,'right'=>0,'bottom'=>10,'left'=>0, 'unit'=>'px']],
                'style' => [
                    (object)[
                        
                        'selector'=>'{{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields p { margin:{{inputMargin}}; }
                                    {{WOPB}} .wopb-checkout-billing-container .woocommerce-billing-fields p:last-child { margin-bottom: 0px; } '
                    ],
                ],
            ],

            // Field Container
            'fieldConBgColor'=> [
                'type' => 'string',
                'default' => '#efefef',
                'style' => [(object)['selector'=>'{{WOPB}} .wopb-checkout-billing-container {background-color:{{fieldConBgColor}}; }']],
            ],
            'fieldConBorder'=> [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#e0e0e0','type' => 'solid' ],
                'style' => [
                    (object)['selector'=>'{{WOPB}} .wopb-checkout-billing-container ']],
            ],
            'fieldConRadius'=> [
                'type' => 'object',
                'default' => (object)['lg'=>(object)['top'=>4,'right'=>4,'bottom'=>4,'left'=>4, 'unit'=>'px']],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-checkout-billing-container { border-radius:{{fieldConRadius}}; }'
                    ],
                ],
            ],
            'fieldConPadding'=> [
                'type' => 'object',
                'default' => (object)['lg'=>(object)['top'=>20,'right'=>20,'bottom'=>20,'left'=>20, 'unit'=>'px']],
                'style' => [
                    (object)[
                        
                        'selector'=>'{{WOPB}} .wopb-checkout-billing-container { padding:{{fieldConPadding}}; }'
                    ],
                ],
            ],

            //--------------------------
            //  Advanced
            //--------------------------
            'advanceId' => [
                'type' => 'string',
                'default' => '',
            ],
            'advanceZindex' => [
                'type' => 'string',
                'default' => '',
                'style' => [
                    (object)[
                        'selector' => '{{WOPB}} {z-index:{{advanceZindex}};}'
                    ],
                ],
            ],
            'wrapMargin' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '','bottom' => '', 'unit' =>'px']],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-product-wrapper{ margin:{{wrapMargin}}; }'
                    ],
                ],
            ],
            'wrapOuterPadding' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '','bottom' => '','left' => '', 'right' => '', 'unit' =>'px']],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-product-wrapper{padding:{{wrapOuterPadding}}; }'
                    ],
                ],
            ],
            'wrapBg' => [
                'type' => 'object',
                'default' => (object)['openColor' => 0, 'type' => 'color', 'color' => '#f5f5f5'],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-product-wrapper'
                    ],
                ],
            ],
            'wrapBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' =>(object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-product-wrapper'
                    ],
                ],
            ],
            'wrapShadow' => [
                'type' => 'object',
                'default' => (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-product-wrapper'
                    ],
                ],
            ],
            'wrapRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-product-wrapper{ border-radius:{{wrapRadius}}; }'
                    ],
                ],
            ],
            'wrapHoverBackground' => [
                'type' => 'object',
                'default' => (object)['openColor' => 0, 'type' => 'color', 'color' => '#ff5845'],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-product-wrapper:hover'
                    ],
                ],
            ],
            'wrapHoverBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-product-wrapper:hover'
                    ],
                ],
            ],
            'wrapHoverRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-product-wrapper:hover { border-radius:{{wrapHoverRadius}}; }'
                    ],
                ],
            ],
            'wrapHoverShadow' => [
                'type' => 'object',
                'default' => (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-product-wrapper:hover'
                    ],
                ],
            ],
            'wrapInnerPadding' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['unit' =>'px']],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-product-wrapper{ padding:{{wrapInnerPadding}}; }'
                    ],
                ],
            ],
            'hideExtraLarge' => [
                'type' => 'boolean',
                'default' => false,
                'style' => [
                    (object)[
                        'selector' => '{{WOPB}} {display:none;}'
                    ],
                ],
            ],
            'hideDesktop' => [
                'type' => 'boolean',
                'default' => false,
                'style' => [
                    (object)[
                        'selector' => '{{WOPB}} {display:none;}'
                    ],
                ],
            ],
            'hideTablet' => [
                'type' => 'boolean',
                'default' => false,
                'style' => [
                    (object)[
                        'selector' => '{{WOPB}} {display:none;}'
                    ],
                ],
            ],
            'hideMobile' => [
                'type' => 'boolean',
                'default' => false,
                'style' => [
                    (object)[
                        'selector' => '{{WOPB}} {display:none;}'
                    ],
                ],
            ],
            'advanceCss' => [
                'type' => 'string',
                'default' => '',
                'style' => [(object)['selector' => '']],
            ]
 
        );
        
        if( $default ){
            $temp = array();
            foreach ($attributes as $key => $value) {
                if( isset($value['default']) ){
                    $temp[$key] = $value['default'];
                }
            }
            return $temp;
        }else{
            return $attributes;
        }
    }

    public function register() {
        register_block_type( 'product-blocks/checkout-billing',
            array(
                'editor_script' => 'wopb-blocks-builder-script',
                'editor_style'  => 'wopb-blocks-editor-css',
                'title' => __('Billing Address', 'product-blocks'),
                'attributes' => $this->get_attributes(),
                'render_callback' => array($this, 'content')
            )
        );
    }

    
    public function content($attr, $noAjax = false)
    {
        if (is_checkout()) {
            $block_name = 'checkout-billing';
            $wraper_before = $wraper_after = $content = '';

            if (function_exists('WC')) {
                $wraper_before .= '<div id="' . ($attr['advanceId'] ? $attr['advanceId'] : '') . '"' . ' class="wp-block-product-blocks-' . $block_name . ' wopb-block-' . $attr["blockId"] . ' ' . (isset($attr["className"]) ? $attr["className"] : '') . '">';
                $wraper_before .= '<div class="wopb-product-wrapper">';
                if (!is_admin()) {
                    if (isset(WC()->customer)) {
                        ob_start();
                        require_once WOPB_PATH . 'addons/builder/blocks/checkout/billing/Template.php';
                        $content .= ob_get_clean();
                    }
                }
                $wraper_after .= '</div> ';
                $wraper_after .= '</div> ';
            }

            return $wraper_before . $content . $wraper_after;
        }
    }

}
