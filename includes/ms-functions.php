<?php 

 
function monthly_post_type() {
 
    
        $labels = array(
            'name'                => _x( 'Monthly Specials', 'Post Type General Name'),
            'singular_name'       => _x( 'Monthly Special', 'Post Type Singular Name'),
            'menu_name'           => __( 'Monthly Specials'),
            'parent_item_colon'   => __( 'Parent Item'),
            'all_items'           => __( 'All Items'),
            'view_item'           => __( 'View Item'),
            'add_new_item'        => __( 'Add New Item'),
            'add_new'             => __( 'Add New Item'),
            'edit_item'           => __( 'Edit Item'),
            'update_item'         => __( 'Update Item'),
            'search_items'        => __( 'Search Item'),
            'not_found'           => __( 'Not Found'),
            'not_found_in_trash'  => __( 'Not found in Trash'),
        );
         
        $args = array(
            'label'               => __( 'Monthly Specials'),
            'description'         => __( 'Item news and reviews'),
            'labels'              => $labels,
            'supports'            => array( 'title', 'thumbnail', 'revisions', 'custom-fields'),
            'hierarchical'        => false,
            'public'              => true,
            'register_meta_box_cb' => 'monthly_specials_meta_box',
            'show_ui'             => true,
            'menu_icon'           => 'dashicons-money',
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
            'show_in_rest' => true,
     
        );
         
        register_post_type( 'monthlyspecials', $args );
     
    }
     
add_action( 'init', 'monthly_post_type', 0 );

function monthly_specials_meta_box() {
    add_meta_box(
        'price',
        __( 'Price', 'directlabs' ),
        'price_meta_box_callback'
    );
    add_meta_box(
        'url',
        __( 'URL', 'directlabs' ),
        'url_meta_box_callback'
    );
    add_meta_box(
        'regular-price',
        __( 'Regular Price', 'directlabs' ),
        'regular_price_meta_box_callback'
    );
}

function price_meta_box_callback( $post ) {
    wp_nonce_field( 'price_nonce', 'price_nonce' );
    $value = get_post_meta( $post->ID, '_price', true );
    echo '<input type="number" style="width:100%" id="price" name="price" value="'. esc_attr( $value ) .'">';
}

function url_meta_box_callback( $post ) {
    wp_nonce_field( 'url_nonce', 'url_nonce' );
    $value = get_post_meta( $post->ID, '_url', true );
    echo '<input type="url" placeholder="example: http://bmgcreative.com/" style="width:100%" id="url" name="url" value="'. esc_attr( $value ) .'">';
}

function regular_price_meta_box_callback( $post ) {
    wp_nonce_field( 'regular_price_nonce', 'regular_price_nonce' );
    $value = get_post_meta( $post->ID, '_regular_price', true );
    echo '<input type="number" style="width:100%" id="regular_price" name="regular_price" value="'. esc_attr( $value ) .'">';
}

function save_price_meta_box_data( $post_id ) {

    if ( ! isset( $_POST['price_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['price_nonce'], 'price_nonce' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }
    }
    else {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }
    if ( ! isset( $_POST['price'] ) ) {
        return;
    }
    $my_data = sanitize_text_field( $_POST['price'] );

    update_post_meta( $post_id, '_price', $my_data );
}

function save_url_meta_box_data( $post_id ) {

    if ( ! isset( $_POST['url_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['url_nonce'], 'url_nonce' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }
    }
    else {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }
    if ( ! isset( $_POST['url'] ) ) {
        return;
    }
    $my_data = sanitize_text_field( $_POST['url'] );

    update_post_meta( $post_id, '_url', $my_data );
}

function save_regular_price_meta_box_data( $post_id ) {

    if ( ! isset( $_POST['url_nonce'] ) ) {
        return;
    }
    if ( ! wp_verify_nonce( $_POST['regular_price_nonce'], 'regular_price_nonce' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

        if ( ! current_user_can( 'edit_page', $post_id ) ) {
            return;
        }
    }
    else {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }
    if ( ! isset( $_POST['regular_price'] ) ) {
        return;
    }
    $my_data = sanitize_text_field( $_POST['regular_price'] );

    update_post_meta( $post_id, '_regular_price', $my_data );
}

add_action( 'save_post', 'save_price_meta_box_data' );
add_action( 'save_post', 'save_url_meta_box_data' );
add_action( 'save_post', 'save_regular_price_meta_box_data' );

function monthly_shortcode() { 
    
    $args = array(
        'post_type' => 'monthlyspecials',
        'post_per_page' => -1
    );

    $items = new WP_Query($args);
    
    if($items->have_posts()) : ?>
        <div id="myCarousel" class="carousel slide" data-ride="carousel">
        <div class="carousel-inner">
        <?php $i = 0; $count = 0; ?>
        <?php while($items->have_posts()) : $items->the_post();
        
        $id = $items->post->ID;
        $price = get_post_meta( $id, '_price', true );
        $url = get_post_meta( $id, '_url', true );
        $reguler = get_post_meta( $id, '_regular_price', true ); ?>
        
        <?php $count++ ?>
        <?php if($count <= 4): ?>
        <div class="carousel-item <?php echo (++$i ==  1) ? 'active' : '' ?>">
            <div class="specials-box">
                
                <p class="p1-home-specials"><?= the_title(); ?></p>
                <p class="p2-home-specials">Only $<?= $price ?></p>
                <button class="btn-general-health" onclick="location.href='<?= $url ?>';">Add to Cart &gt;&gt;</button>
                <p class="p3-home-specials">Reguler Price $<?= $reguler ?></p>
                
            </div>
        </div>
        <?php endif; ?>
        
        

        <?php endwhile; ?>
        </div>
        </div>
        <?php $slider_to = 0; $j = 0; $l = 1; $m = 1; ?>
        <ol class="carousel-linked-nav pagination" style="float:right">
            <?php while($items->have_posts()) : $items->the_post(); ?>

            <?php $j++ ?> 
            <?php if($j <= 4): ?>    
            <li data-target="#myCarousel" data-slide-to="<?= $slider_to++ ?>" class="pagination-monthly"><a href="#<?= $l++ ?>"><?= $m++ ?></a></li>
            <?php endif; ?>

            <?php endwhile; ?>
        </ol>
        </div>
        <script>
            const pagination = document.getElementsByClassName('pagination-monthly');
            for (let i = 0; i < pagination.length; i++) {
                
                pagination[i].addEventListener('click', function(){
                    pagination[i].classList.toggle('active');
                })

            }
        </script>
    <?php else:
        return false;
    endif;     

    } 
    
add_shortcode('monthly_items', 'monthly_shortcode'); 
    