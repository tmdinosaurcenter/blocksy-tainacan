<?php 

$prefix = blocksy_manager()->screen->get_prefix();

$page_hero_section_style = get_theme_mod($prefix . '_hero_section' , get_theme_mod($prefix . '_page_header_background_style', 'boxed'));

$page_container_classes = 'page type-page hentry singular';
$page_container_classes = $page_container_classes . ' has-filters-panel-style-' . get_theme_mod($prefix . '_filters_panel_background_style', 'boxed');
$page_container_classes = $page_container_classes . ' has-page-header-style-' . $page_hero_section_style;

if ( get_theme_mod( $prefix . '_hide_filters_area_header', 'no' ) === 'yes' )
    $page_container_classes .= ' has-filters-area-header-hidden';    

$filters_panel_size = get_theme_mod($prefix . '_filters_panel_size', '20%');
$page_container_style = '--tainacan-filter-menu-width-theme:' . $filters_panel_size . ';';

$filters_inline_size = get_theme_mod($prefix . '_filters_inline_size', '272px');
$page_container_style .= '--tainacan-filters-inline-width:' . $filters_inline_size . ';';

$background_color_palette = get_theme_mod($prefix . '_items_list_background_palette',
[
    'color1' => [ 'color' => 'var(--background-color, #f8f9fb)' ],
    'color2' => [ 'color' => 'var(--cardBackground, #ffffff)' ],
    'color3' => [ 'color' => 'var(--cardBackground, #ffffff)' ],
    'color4' => [ 'color' => 'var(--theme-form-field-background-initial-color, var(--form-field-initial-background, #ffffff))' ],
    'color5' => [ 'color' => 'var(--theme-form-field-border-initial-color, var(--form-field-border-initial-color, #e0e5eb))' ],
    'color6' => [ 'color' => 'var(--theme-form-field-border-initial-color, var(--form-field-border-initial-color, #e0e5eb))' ]
]);
$page_container_style .= '--tainacan-background-color:' . $background_color_palette['color1']['color'] . ';';
$page_container_style .= '--tainacan-item-background-color:' . $background_color_palette['color2']['color'] . ';';
$page_container_style .= '--tainacan-item-hover-background-color:' . $background_color_palette['color3']['color'] . ';';
$page_container_style .= '--tainacan-input-background-color:' . $background_color_palette['color4']['color'] . ';';
$page_container_style .= '--tainacan-primary:' . $background_color_palette['color5']['color'] . ';';

if ( isset( $background_color_palette['color6'] ) ) {
    $page_container_style .= '--tainacan-input-border-color:' . $background_color_palette['color6']['color'] . ';';
    $page_container_style .= '--theme-form-field-border-initial-color:' . $background_color_palette['color6']['color'] . ';';
}

$text_color_palette = get_theme_mod($prefix . '_items_list_text_palette',
[
    'color1' => [ 'color' => 'var(--theme-palette-color-1, var(--paletteColor1, #3eaf7c))' ],
    'color2' => [ 'color' => 'var(--theme-heading-color, var(--headingColor, rgba(44, 62, 80, 1)))' ],
    'color3' => [ 'color' => 'var(--theme-text-color, var(--color, #373839))' ],
    'color4' => [ 'color' => '#505253' ],
    'color5' => [ 'color' => 'var(--theme-form-text-initial-color, var(--formTextInitialColor, #373839))' ]
]);
$page_container_style .= '--tainacan-secondary:' . $text_color_palette['color1']['color'] . ';';
$page_container_style .= '--tainacan-heading-color:' . $text_color_palette['color2']['color'] . ';';
$page_container_style .= '--tainacan-label-color:' . $text_color_palette['color3']['color'] . ';';
$page_container_style .= '--tainacan-info-color:' . $text_color_palette['color4']['color'] . ';';
$page_container_style .= '--tainacan-input-color:' . $text_color_palette['color5']['color'] . ';';

$page_container_style .= 'background-color: var(--tainacan-background-color, #f8f9fb);';

global $post;

$hero_elements = blocksy_akg_or_customizer(
    'hero_elements',
    [ 'prefix' => $prefix ],
    [
        [
            'id' => 'custom_thumbnail',
            'enabled' => true,
        ],
        [
            'id' => 'custom_title',
            'enabled' => true,
            'heading_tag' => 'h1'
        ],
        [
            'id' => 'breadcrumbs',
            'enabled' => true
        ],
        [
            'id' => 'custom_description',
            'enabled' => true,
            'description_visibility' => [
                'desktop' => true,
                'tablet' => true,
                'mobile' => false,
            ]
        ]
    ]
);

$elements = [];

foreach ($hero_elements as $index => $single_hero_element) {
    
    if ($single_hero_element['id'] == 'custom_thumbnail' && $single_hero_element['enabled'] && has_post_thumbnail( tainacan_get_collection_id() )) {
        $thumbnail_id = get_post_thumbnail_id( $post->ID );
        $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);

        $elements[] = '
        <div class="collection-thumbnail">
            <img src="' . get_the_post_thumbnail_url( tainacan_get_collection_id() ) . '" alt="' . esc_attr($alt) . '">
        </div>
        ';

        add_filter( 'blocksy:hero:wrapper-attr', function($attrs) {
            $attrs['class'] .= ' has-thumbnail-enabled';
            return $attrs;
        });

    } else if ($single_hero_element['id'] == 'custom_title' && $single_hero_element['enabled']) {
        $title = '';

        $has_category_label = blocksy_akg(
            'has_category_label',
            $single_hero_element,
            'no'
        );

        if ( !empty(get_the_archive_title()) ) {
            $title = wp_strip_all_tags(get_the_archive_title());

            $divider_symbol = ':';

            if (strpos($title, '：') !== false) {
                $divider_symbol = '：';
            }

            if (strpos($title, $divider_symbol) !== false) {
                $title_pieces = explode($divider_symbol, $title, 2);

                $title = '<span class="ct-title-label">' . $title_pieces[0] . '</span>' . $title_pieces[1];

                if ($has_category_label !== 'yes') {
                    $title = $title_pieces[1];
                }
            }
        }

        if ( !empty($title) ) {
            $title = blocksy_html_tag(
                blocksy_akg('heading_tag', $single_hero_element, 'h1'),
                array_merge([
                    'class' => 'page-title',
                ], blocksy_schema_org_definitions('headline', [
                    'array' => true
                ])),
                $title
            );
        }

        ob_start();
        do_action('blocksy:hero:title:before');
        $before_hero_title = ob_get_clean();

        ob_start();
        do_action('blocksy:hero:title:after');
        $after_hero_title = ob_get_clean();
        
        $elements[] = $before_hero_title . $title . $after_hero_title;
        
    } else if ($single_hero_element['id'] == 'custom_description' && $single_hero_element['enabled'] && get_the_archive_description()) {
        $description_class = 'page-description';
        $description_class .= ' ' . blocksy_visibility_classes(
            blocksy_akg(
                'description_visibility',
                $single_hero_element,
                [
                    'desktop' => true,
                    'tablet' => true,
                    'mobile' => false,
                ]
            )
        );
        $elements[] = '<div class="' . $description_class . '">' . get_the_archive_description() . '</div>';
    } else if ($single_hero_element['id'] == 'breadcrumbs' && $single_hero_element['enabled']) {
        if ( class_exists('Blocksy_Breadcrumbs_Builder') )
            $breadcrumbs_builder = new Blocksy_Breadcrumbs_Builder();
        else
            $breadcrumbs_builder = new \Blocksy\BreadcrumbsBuilder();

        $elements[] = $breadcrumbs_builder->render();
    }
}

$html_elements = '';
foreach ($elements as $element) {

    $html_elements .= $element;
}

get_header();

add_filter('blocksy:hero:custom-source', function() use($prefix) {
    return [
        'strategy' => 'customizer',
        'prefix' => $prefix
    ];
});

if ( $page_hero_section_style === 'type-2' ) {
    if ( blocksy_akg_or_customizer('page_title_bg_type', blocksy_get_page_title_source(), 'featured_image') === 'featured_image' ) {
        add_filter( 'blocksy:hero:type-2:image:attachment_id', function() {
            $collection = tainacan_get_collection();
            return $collection->get_header_image_id();
        }, 10 );
    }

    echo blocksy_output_hero_section([
        'type' => $page_hero_section_style,
        'source' => false,
        'elements' => $html_elements
    ]);
}
?>                    
    <article class="<?php echo esc_attr($page_container_classes) ?>" style="<?php echo esc_attr($page_container_style) ?>">

    <?php
        if ( $page_hero_section_style === 'type-1' ) {
            echo blocksy_output_hero_section([
                'type' => $page_hero_section_style,
                'source' => false,
                'elements' => $html_elements
            ]);
        }
        if ( get_theme_mod($prefix . '_hero_enabled', 'yes') === 'yes' && $page_hero_section_style !== 'type-1' && $page_hero_section_style !== 'type-2' ): ?>
            <header 
                class="tainacan-collection-header entry-header" 
                style="background-image: 
                    <?php if ( get_header_image() ) 
                        echo('linear-gradient(to bottom, rgba(255, 255, 255, ' . ($page_hero_section_style == 'boxed' ? '0.3' : '0.8') . '), var(--tainacan-background-color, var(--background-color, #f8f9fb))), url(' . esc_url(get_header_image()) . ')'); 
                    else
                        echo 'none'; 
                    ?>"
            >
                <div class="tainacan-collection-header__box">  
                    <?php echo $html_elements; ?>
                </div>
            </header>
        <?php endif; ?>

        <div class="entry-content <?php echo get_theme_mod($prefix . '_container-width', 'fluid') !== 'fluid' ? 'ct-container' : ''; ?>">
            <?php 
                tainacan_the_faceted_search([
                    'hide_filters' => get_theme_mod($prefix . '_display_filters_panel', 'yes') == 'no',
                    'start_with_filters_hidden' => get_theme_mod($prefix . '_start_with_filters_hidden', 'no') == 'yes',
                    'hide_hide_filters_button' => get_theme_mod($prefix . '_show_hide_filters_button', 'yes') == 'no',
                    'show_filters_button_inside_search_control' => get_theme_mod($prefix . '_show_filters_button_inside_search_control', 'yes') == 'yes',
                    'filters_as_modal' => get_theme_mod($prefix . '_filters_as_modal', 'no') == 'yes',
                    'hide_search' => get_theme_mod($prefix . '_show_search', 'yes') == 'no',
                    'hide_advanced_search' => get_theme_mod($prefix . '_show_advanced_search', 'yes') == 'no',
                    'hide_sorting_area' => get_theme_mod($prefix . '_show_sorting_area', 'yes') == 'no',
                    'hide_sort_by_button' => get_theme_mod($prefix . '_show_sort_by_button', 'yes') == 'no',
                    'hide_displayed_metadata_button' => get_theme_mod($prefix . '_show_displayed_metadata_dropdown', 'yes') == 'no',
                    'show_inline_view_mode_options' => get_theme_mod($prefix . '_show_inline_view_mode_options', 'no') == 'yes',
                    'show_fullscreen_with_view_modes' => get_theme_mod($prefix . '_show_fullscreen_with_view_modes', 'no') == 'yes',
                    'hide_exposers_button' => get_theme_mod($prefix . '_show_exposers_button', 'yes') == 'no',
                    'hide_pagination_area' => get_theme_mod($prefix . '_has_pagination', 'yes') == 'no',
                    'hide_items_per_page_button' => get_theme_mod($prefix . '_show_items_per_page_button', 'yes') == 'no',
                    'hide_go_to_page_button' => get_theme_mod($prefix . '_show_go_to_page_button', 'yes') == 'no',
                    'should_not_hide_filters_on_mobile' => get_theme_mod($prefix . '_should_not_hide_filters_on_mobile', 'no') == 'yes',
                    'display_filters_horizontally' => get_theme_mod($prefix . '_display_filters_horizontally', 'no') == 'yes',
                    'hide_filter_collapses' => get_theme_mod($prefix . '_hide_filter_collapses', 'no') == 'yes',
                ]); 
            ?>
        </div>

    </article>
    
<?php get_footer(); ?>