/**
 * gc code
 * Modify filter for shop page when vendor is logged in to show only industry-related products.
 *
 * @param array $args The arguments for the filter instance.
 * @param object $filter_instance The filter instance object.
 * @return array The modified arguments.
 */
function gc_shop_modify_filter( $args, $filter_instance ) {
    // Check if user is logged in
    if ( is_user_logged_in() ) {
        $user_ID = get_current_user_id();
        $user = wp_get_current_user();
        $roles = $user->roles;
        $category_ids = array();
        
        // Check if user role is vendor or seller
        if ( in_array( 'vendor', $roles ) || in_array( 'seller', $roles ) ) {
            // Get industry ID from user meta
            $industry_id = get_user_meta( $user_ID, 'industry', true );
            
            if ( ! empty( $industry_id ) ) {
                // Include industry ID in category IDs
                $category_ids[] = $industry_id;
                
                // Check if options exist and is an array
                if ( isset( $args['options'] ) && is_array( $args['options'] ) ) {
                    // Loop through options
                    foreach ( $args['options'] as $key => $value ) {
                        // Get child categories
                        $child_category_ids = get_term_children( $industry_id, 'product_cat' );
                        
                        if ( ! is_wp_error( $child_category_ids ) ) {
                            // Add child category IDs to category IDs array
                            foreach ( $child_category_ids as $child_category_id ) {
                                $category_ids[] = $child_category_id; 
                            }
                        }
                        
                        // If term ID is not in category IDs, remove from options
                        if ( ! in_array( $value->term_id, $category_ids ) ) {
                           unset( $args['options'][$key] );
                        }   
                    }
                }
            } 
        }  
    }
    return $args;
}

// Add filter for modifying shop page filter
add_filter( 'jet-smart-filters/filter-instance/args', 'gc_shop_modify_filter', 20, 2 );



/**
 * Modify the main query based on the custom query variable only for vendor related to his industry.
 *
 * @param object $query The WP_Query object.
 * @return object The modified WP_Query object.
 */
function gc_modify_search_query($query) {
    // Check if WooCommerce is active and it's the shop page
    if ( function_exists( 'is_woocommerce' ) && is_shop() ) {
        // Check if user is logged in
        if ( is_user_logged_in() ) {
            $user = wp_get_current_user();
            $roles = $user->roles;
            
            // Check if user is vendor or seller
            if ( in_array( 'vendor', $roles ) || in_array( 'seller', $roles ) ) {
                $category_slugs = array();
                $user_ID = get_current_user_id();
                $industry_id = get_user_meta( $user_ID, 'industry', true );
                
                if ( ! empty( $industry_id ) ) {
                    // Get category and child categories slugs
                    $category = get_term( $industry_id, 'product_cat' );
                    $category_slugs[] = $category->slug;
                    
                    if ( ! is_wp_error( $category ) ) {
                        $child_category_ids = get_term_children( $industry_id, 'product_cat' );
                        
                        if ( ! is_wp_error( $child_category_ids ) ) {
                            foreach ( $child_category_ids as $child_category_id ) {
                                $child_category = get_term( $child_category_id, 'product_cat' );
                                $category_slugs[] = $child_category->slug;
                            }
                        } 
                    }
                    
                    // Set taxonomy query if category slugs are not empty
                    if ( ! empty( $category_slugs ) ) {
                        $query->set( 'post_type', array( 'product' ) );
                        $tax_query = array(
                            array(
                                'taxonomy' => 'product_cat',
                                'field'    => 'slug',
                                'terms'    => $category_slugs,
                            ),
                        );
                        $query->set( 'tax_query', $tax_query );
                    }
                }
            } 
        }
    }
    return $query;
}

// Add filter to modify search query
add_filter( 'pre_get_posts', 'gc_modify_search_query' );
