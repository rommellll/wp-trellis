<?php
namespace WOPB\blocks;

defined('ABSPATH') || exit;

class Filter {
    public function __construct() {
        add_action('init', array($this, 'register'));
    }
    public function get_attributes($default = false){
        $attributes = array(
            'blockId' => [
                'type' => 'string',
                'default' => '',
            ],

            //--------------------------
            //      Price Filter Setting/Style
            //--------------------------
            'initialBlockTargetSelect' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'repeatableFilter' => [
                'type'=> 'array',
                'fields' => [
                    'type' => [
                        'type' => 'string',
                        'default' => 'price',
                    ],
                    'label' => [
                        'type' => 'string',
                        'default' => 'Filter By Price',
                    ]
                ],
                'default' => [
                    [
                        'type' => 'search',
                        'label' => 'Filter By Search',
                    ],
                    [
                        'type' => 'price',
                        'label' => 'Filter By Price',
                    ],
                    [
                        'type' => 'category',
                        'label' => 'Filter By Category',
                    ],
                    [
                        'type' => 'status',
                        'label' => 'Filter By Status',
                    ],
                    [
                        'type' => 'rating',
                        'label' => 'Filter By Rating',
                    ],
                ],
            ],
            
            'sortingItems' => [
                'default' =>[
                    (object)[
                        "label" => "Default Sorting",
                        "value" => "default",
                    ],
                    (object)[
                        "label" => "Sort by popularity",
                        "value" => "popular",
                    ],
                    (object)[
                        "label" => "Sort by latest",
                        "value" => "latest",
                    ],
                    (object)[
                        "label" => "Sort by average rating",
                        "value" => "rating",
                    ],
                    (object)[
                        "label" => "Sort by price: low to high",
                        "value" => "price_low",
                    ],
                    (object)[
                        "label" => "Sort by price: high to low",
                        "value" => "price_high",
                    ],
                ]
            ],

            'blockTarget' => [
                'type' => 'string',
                'default' => '',
            ],
            'clearFilter' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'filterHeading' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'productCount' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'labelColor' => [
                'type' => 'string',
                'default' => '#474747',
                'style' => [(object)['selector'=>'{{WOPB}} .wopb-filter-block .wopb-filter-header .wopb-filter-label { color:{{labelColor}}; }']],
            ],
            'labelTypo' => [
                'type' => 'object',
                'default' =>  (object)['openTypography' => 1,'size' => (object)['lg' => '', 'unit' => 'px'], 'height' => (object)['lg' => '', 'unit' => 'px'],'decoration' => 'none', 'transform' => '', 'family'=>'','weight'=>''],
                'style' => [(object)['selector' => '{{WOPB}} .wopb-filter-block .wopb-filter-header .wopb-filter-label']]
            ],
            'togglePlusMinus' => [
                'type' => 'boolean',
                'default' => true,
            ],
            'togglePlusMinusInitialOpen' => [
                'type' => 'boolean',
                'default' => true,
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'togglePlusMinus','condition'=>'==','value'=> true],
                        ],
                    ],
                ],
            ],
            'togglePlusMinusSize' => [
                'type' => 'object',
                'default' => (object)['lg' =>'17', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'togglePlusMinus','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{WOPB}} .wopb-filter-block .wopb-filter-header .wopb-filter-toggle .dashicons { font-size: {{togglePlusMinusSize}}; }'
                    ],
                ],
            ],
            'togglePlusMinusColor' => [
                'type' => 'string',
                'default' => '#373737',
                'style' => [
                    (object)[
                        'depends' => [
                            (object)['key'=>'togglePlusMinus','condition'=>'==','value'=> true],
                        ],
                        'selector'=>'{{WOPB}} .wopb-filter-block .wopb-filter-header .wopb-filter-toggle .dashicons { color:{{togglePlusMinusColor}}; }']
                    ],
            ],
            'searchBoxColor' => [
                'type' => 'string',
                'default' => '#3b3b3b',
                'style' => [(object)['selector'=>'{{WOPB}} .wopb-filter-block .wopb-search-filter-body input { color:{{searchBoxColor}}; }']],
            ],
            'searchBoxBackgroundColor' => [
                'type' => 'string',
                'default' => '#fff',
                'style' => [
                    (object)[
                        'selector'=>'{{WOPB}} .wopb-filter-block .wopb-search-filter-body input { background:{{searchBoxBackgroundColor}}; }'
                    ],
                ],
            ],
            'searchBoxBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>1, 'width' => (object)[ 'top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#9c9c9c','type' => 'solid' ],
                'style' => [
                    (object)[
                        'selector'=>'{{WOPB}} .wopb-filter-block .wopb-search-filter-body input'
                    ],
                ],
            ],
            'searchBoxRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'4', 'unit' =>'px'],
                'style' => [
                    (object)[
                        'selector'=>'{{WOPB}} .wopb-filter-block .wopb-search-filter-body input { border-radius:{{searchBoxRadius}}; }'
                    ],
                ],
            ],
            'searchBoxPadding' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '6','bottom' => '6','left' => '12','right' => '12', 'unit' =>'px']],
                'style' => [
                    (object)[
                        'selector'=>'{{WOPB}} .wopb-filter-block .wopb-search-filter-body input { padding:{{searchBoxPadding}}; }'
                    ],
                ],
            ],

            //--------------------------
            //  Wrapper Style
            //--------------------------
            'wrapBg' => [
                'type' => 'object',
                'default' => (object)['openColor' => 0, 'type' => 'color', 'color' => '#f5f5f5'],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-block-wrapper'
                    ],
                ],
            ],
            'wrapBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' =>(object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-block-wrapper'
                    ],
                ],
            ],
            'wrapShadow' => [
                'type' => 'object',
                'default' => (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-block-wrapper'
                    ],
                ],
            ],
            'wrapRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-block-wrapper { border-radius:{{wrapRadius}}; }'
                    ],
                ],
            ],
            'wrapHoverBackground' => [
                'type' => 'object',
                'default' => (object)['openColor' => 0, 'type' => 'color', 'color' => '#ff5845'],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-block-wrapper:hover'
                    ],
                ],
            ],
            'wrapHoverBorder' => [
                'type' => 'object',
                'default' => (object)['openBorder'=>0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4','type' => 'solid'],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-block-wrapper:hover'
                    ],
                ],
            ],
            'wrapHoverRadius' => [
                'type' => 'object',
                'default' => (object)['lg' =>'', 'unit' =>'px'],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-block-wrapper:hover { border-radius:{{wrapHoverRadius}}; }'
                    ],
                ],
            ],
            'wrapHoverShadow' => [
                'type' => 'object',
                'default' => (object)['openShadow' => 0, 'width' => (object)['top' => 1, 'right' => 1, 'bottom' => 1, 'left' => 1],'color' => '#009fd4'],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-block-wrapper:hover'
                    ],
                ],
            ],
            'wrapMargin' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '','bottom' => '', 'unit' =>'px']],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-block-wrapper { margin:{{wrapMargin}}; }'
                    ],
                ],
            ],
            'wrapOuterPadding' => [
                'type' => 'object',
                'default' => (object)['lg' =>(object)['top' => '','bottom' => '','left' => '', 'right' => '', 'unit' =>'px']],
                'style' => [
                     (object)[
                        'selector'=>'{{WOPB}} .wopb-block-wrapper { padding:{{wrapOuterPadding}}; }'
                    ],
                ],
            ],
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
        
        if ($default) {
            $temp = array();
            foreach ($attributes as $key => $value) {
                if (isset($value['default'])) {
                    $temp[$key] = $value['default'];
                }
            }
            return $temp;
        } else {
            return $attributes;
        }
    }

    public function register() {
        register_block_type( 'product-blocks/filter',
            array(
                'editor_script' => 'wopb-blocks-editor-script',
                'editor_style'  => 'wopb-blocks-editor-css',
                'title' => __('Filter by Price', 'product-blocks'),
                'attributes' => $this->get_attributes(),
                'render_callback' =>  array($this, 'content')
            )
        );
    }

    /**
     * This
     * @return terminal
     */
    public function content($attr, $noAjax = false) {
        if (wopb_function()->is_lc_active()) {
            $wraper_before = '';
            $block_name = 'filter';
            $page_post_id = wopb_function()->get_ID();
            $attr['headingShow'] = true;
            $active_filters = $attr['repeatableFilter'];
            $wrapper_class = '';
            $wrapper_class .= 'wopb-filter-block-front-end ';
            $wrapper_class .= ($attr['togglePlusMinus'] && $attr['togglePlusMinusInitialOpen']) ? 'wopb-filter-toggle-initial-open ' : '';
            $wrapper_class .= ($attr['togglePlusMinus'] && !$attr['togglePlusMinusInitialOpen']) ? 'wopb-filter-toggle-initial-close ' : '';
            $html = '';

            $wraper_before .= '<div ' . ($attr['advanceId'] ? 'id="' . esc_attr($attr['advanceId']) . '" ' : '') . ' class="wp-block-product-blocks-' . esc_attr($block_name) . ' wopb-block-' . esc_attr($attr["blockId"]) . ' ' . (isset($attr["class"]) ? esc_attr($attr["class"]) : '') . '">';
            $wraper_before .= '<div class="wopb-product-wrapper wopb-filter-block ' . $wrapper_class . '" data-postid = "' . $page_post_id . '" data-block-target = "' . $attr['blockTarget'] . '" data-current-url="' . get_pagenum_link() . '">';

            if ($attr['clearFilter']) {
                ob_start();
                $this->removeFilterItem();
                $html .= ob_get_clean();
            }

            if ($attr['filterHeading']) {
                $html .= '<div class="wopb-filter-title-section">';
                $html .= '<span class="wopb-filter-title">Filter</span>';
                $html .= '<span class="dashicons dashicons-filter wopb-filter-icon"></span>';
                $html .= '</div>';
            }

            foreach ($active_filters as $active_filter) {
                $params = [
                    'headerLabel' => $active_filter['label']
                ];
                switch ($active_filter['type']) {
                    case 'search':
                        ob_start();
                        $this->search_filter($attr, $params);
                        $html .= ob_get_clean();
                        break;
                    case 'price':
                        ob_start();
                        $this->price_filter($attr, $params);
                        $html .= ob_get_clean();
                        break;
                    case 'status':
                        ob_start();
                        $this->status_filter($attr, $params);
                        $html .= ob_get_clean();
                        break;
                    case 'rating':
                        ob_start();
                        $this->rating_filter($attr, $params);
                        $html .= ob_get_clean();
                        break;
                    case 'sort_by':
                        ob_start();
                        $this->sorting_filter($attr, $params);
                        $html .= ob_get_clean();
                        break;

                    default:
                        foreach (wopb_function()->get_product_taxonomies() as $item) {
                            if ($item->name === $active_filter['type']) {
                                $params['taxonomy'] = $item;
                                ob_start();
                                    $this->product_taxonomy_filter($attr, $params);
                                $html .= ob_get_clean();
                            }
                        }
                }
            }

            ob_start();
            $this->reset_filter($attr);
            $html .= ob_get_clean();

            $wraper_after = '</div>';
            $wraper_after .= '</div>';

            return $wraper_before . $html . $wraper_after;
        }
    }

    public function removeFilterItem() {
?>
        <div class="wopb-filter-remove-section">
            <span class="wopb-filter-active-item-list">
            </span>
            <span class="wopb-filter-remove-all">
                <?php _e('Clear All', 'product-blocks') ?> <span class="dashicons dashicons-no-alt wopb-filter-remove-icon">
            </span>
        </div>
<?php
    }

    public function filter_header_content ($attr, $params) {
?>
        <div class="wopb-filter-header">
            <span class="wopb-filter-label">
                <?php esc_html_e($params['headerLabel']) ?>
            </span>
            <?php if($attr['togglePlusMinus']) { ?>
                <div class="wopb-filter-toggle">
                    <span class="dashicons dashicons-plus-alt2 wopb-filter-plus"></span>
                    <span class="dashicons dashicons-minus wopb-filter-minus"></span>
                </div>
            <?php } ?>
        </div>
<?php
    }

    public function search_filter($attr, $params) {
?>
        <div class="wopb-filter-section">
            <?php $this->filter_header_content($attr, $params); ?>
            <div class="wopb-filter-body">
                <div class="wopb-search-filter-body">
                    <input type="hidden" class="wopb-filter-slug" value="search">
                    <input type="search" class="wopb-filter-search-input" placeholder="Search Products..."/>
                    <span class="wopb-search-icon">
                        <img src="<?php echo WOPB_URL ?>/assets/img/blocks/search.svg" alt="<?php __('Image', 'product-blocks')?>" />
                    </span>
                </div>
            </div>
        </div>
<?php
    }

    public function price_filter($attr, $params) {
        $highest_price = $this->get_highest_price();
?>
        <div class="wopb-filter-section">
            <?php $this->filter_header_content($attr, $params); ?>

            <div class="wopb-filter-body wopb-price-range-slider">
                <input type="hidden" class="wopb-filter-slug" value="price">
                <div class="wopb-price-range">
                    <span class="wopb-price-range-bar"></span>
                    <input type="range" class="wopb-price-range-input wopb-price-range-input-min" min="0" max="<?php esc_attr_e($highest_price) ?>" value="0" step="1">
                    <input type="range" class="wopb-price-range-input wopb-price-range-input-max" min="0" max="<?php esc_attr_e($highest_price) ?>" value="<?php esc_attr_e($highest_price) ?>" step="1">
                </div>
                <span>
                    Price: <span class="wopb-price-range-value"><?php echo wc_price(0) ?> - <?php echo wc_price($highest_price) ?></span>
                </span>
            </div>
        </div>
<?php
    }

    public function status_filter($attr, $params) {
        $stock_params = [];
        $queried_object = get_queried_object();
            if(is_product_category()) {
                $stock_params['taxonomy'] = 'product_cat';
                $stock_params['taxonomy_term_id'] = $queried_object->term_id;;
            }
        $status_list = wopb_function()->get_stock_status_data($stock_params);
?>
        <div class="wopb-filter-section">
            <?php $this->filter_header_content($attr, $params); ?>

            <div class="wopb-filter-body">
                <input type="hidden" class="wopb-filter-slug" value="status">
                <div class="wopb-filter-check-list">
                    <?php foreach ($status_list as $status) { ?>
                        <div class="wopb-filter-check-item-section">
                            <div class="wopb-filter-check-item">
                                <label for="status_<?php esc_attr_e($status['key']) ?>">
                                    <input type="checkbox" class="wopb-filter-status-input" id="status_<?php esc_attr_e($status['key']) ?>" value="<?php esc_attr_e($status['key']) ?>"/>
                                    <?php esc_html_e($status['name']) ?> <?php $attr['productCount'] ? esc_html_e('(' . $status['count'] .')') : '' ?>
                                </label>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
<?php
    }

    public function rating_filter($attr, $params) {
?>
        <div class="wopb-filter-section">
            <?php $this->filter_header_content($attr, $params); ?>

            <div class="wopb-filter-body">
                <input type="hidden" class="wopb-filter-slug" value="rating">
                <div class="wopb-filter-check-list wopb-filter-ratings">
                    <?php for ($row = 5; $row > 0; $row--) { ?>
                        <div class="wopb-filter-check-item-section">
                            <div class="wopb-filter-check-item">
                                <label for="filter-rating-<?php esc_attr_e($row) ?>">
                                    <input type="checkbox" class="wopb-filter-rating-input" value="<?php esc_attr_e($row) ?>" id="filter-rating-<?php esc_attr_e($row) ?>">
                                    <?php for ($filledStar = $row; $filledStar > 0; $filledStar--) { ?>
                                        <span class="dashicons dashicons-star-filled"></span>
                                    <?php } ?>
                                    <?php for ($emptyStar = 0; $emptyStar < 5- $row; $emptyStar++) { ?>
                                        <span class="dashicons dashicons-star-empty"></span>
                                    <?php } ?>
                                </label>
                            </div>
                        </div>
                   <?php } ?>
                </div>
            </div>
        </div>
<?php
    }

     public function product_taxonomy_filter($attr, $params) {
        $params['term_view_limit'] = 8;
?>
        <div class="wopb-filter-section">
            <?php $this->filter_header_content($attr, $params); ?>

            <div class="wopb-filter-body">
                <input
                    type="hidden"
                    class="wopb-filter-slug"
                    value="product_taxonomy"
                    data-taxonomy="<?php esc_attr_e($params['taxonomy']->name) ?>"
                />
                <?php
                    !empty($params['taxonomy']) ? $this->product_taxonomy_terms($attr, $params) : '';
                    if( count($params['taxonomy']->terms) > $params['term_view_limit']) {
                ?>
                        <a href="javascript:" class="wopb-filter-extend-control wopb-filter-show-more">Show More</a>
                        <a href="javascript:" class="wopb-filter-extend-control wopb-filter-show-less">Show Less</a>
                <?php } ?>
            </div>
        </div>
<?php
    }



    public function product_taxonomy_terms($attr, $params) {
        $taxonomy = $params['taxonomy'];
        $view_limit = $params['term_view_limit'];
?>
        <div class="wopb-filter-check-list <?php isset($params['term_type']) && $params['term_type'] === 'child' ? esc_attr_e('wopb-filter-child-check-list') : '' ?>">
            <?php
                $key = 0;
                foreach ($taxonomy->terms as $term) {
                    $key++;
                    $extended_item_class = (!empty($view_limit) && $key > $view_limit) ? 'wopb-filter-extended-item' : '';
            ?>
                <div class="wopb-filter-check-item-section">
                    <div class="wopb-filter-check-item <?php esc_attr_e($extended_item_class) ?>">
                        <label for="tax_term_<?php esc_attr_e($term->name . '_' . $term->term_id) ?>">
                            <input
                                type="checkbox"
                                class="wopb-filter-tax-term-input"
                                id="tax_term_<?php esc_attr_e($term->name . '_' . $term->term_id) ?>"
                                value="<?php esc_attr_e($term->term_id) ?>"
                                data-label="<?php esc_attr_e($term->name) ?>"
                            />
                            <?php
                                if(isset($taxonomy->attribute) && $taxonomy->attribute->attribute_type === 'color') {
                                    $color_code = get_term_meta($term->term_id, $taxonomy->attribute->attribute_type, true);
                                    $color_html = $color_code ? "<span class='wopb-filter-tax-color' style='background-color: " . esc_attr($color_code) . "'></span>" : '';
                                    echo $color_html;
                                }
                            ?>
                           <span><?php esc_html_e($term->name) ?> <?php $attr['productCount'] ? esc_html_e('(' . $term->count .')') : '' ?></span>
                        </label>
                        <?php
                            if(isset($term->child_terms)) {
                                $params['taxonomy']->terms = $term->child_terms;
                                $params['term_type'] = 'child';
                                $this->product_taxonomy_terms($attr, $params);
                            }
                        ?>
                    </div>
                    <?php if(isset($term->child_terms)) { ?>
                        <div class="wopb-filter-child-toggle">
                            <span class="dashicons dashicons-arrow-right-alt2 wopb-filter-right-toggle"></span>
                            <span class="dashicons dashicons-arrow-down-alt2 wopb-filter-down-toggle"></span>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
<?php

    }

    public function sorting_filter($attr, $params) {
?>
        <div class="wopb-filter-section">
            <?php $this->filter_header_content($attr, $params); ?>

            <div class="wopb-filter-body">
                <input type="hidden" class="wopb-filter-slug" value="sorting">
                <select name="sortBy" class="select wopb-filter-sorting-input">
                    <?php foreach ($attr['sortingItems'] as $item) { ?>
                        <option value="<?php esc_attr_e($item->value)?>" ><?php esc_html_e($item->label)?></option>
                   <?php } ?>
                </select>
            </div>
        </div>
<?php
    }

    public function reset_filter($attr) {
        $queried_object = get_queried_object();
        $current_page = '';
        $current_page_value = '';
        if(is_product_category()) {
            $current_page = 'category';
            $current_page_value = $queried_object->term_id;
        }
?>
        <div class="wopb-filter-section">
            <div class="wopb-filter-body">
                <input type="hidden" class="wopb-filter-slug wopb-filter-slug-reset wopb-d-none" value="reset">
                <?php if(isset($current_page)) { ?>
                    <input type="hidden" class="wopb-filter-current-page wopb-d-none" value="<?php esc_attr_e($current_page_value); ?>" data-slug="<?php esc_attr_e($current_page); ?>">
                <?php } ?>
            </div>
        </div>
<?php
    }

    public function get_highest_price() {
        global $wpdb;
        $sql = "SELECT MAX(max_price) as max_price from {$wpdb->prefix}wc_product_meta_lookup";
        $result = $wpdb->get_row($sql);
        $max_price = $result->max_price;
        if(isset(wopb_function()->currency_switcher_data($max_price)['value'])) {
            $max_price = wopb_function()->currency_switcher_data($max_price)['value'];
        }
        return $max_price;
    }
}