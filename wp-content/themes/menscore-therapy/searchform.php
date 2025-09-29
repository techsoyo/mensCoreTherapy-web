<?php
/**
 * Search form template
 */
?>

<form role="search" method="get" class="search-form neomorphic" action="<?php echo esc_url(home_url('/')); ?>">
    <label for="search-field" class="screen-reader-text"><?php _e('Search for:', 'menscore-therapy'); ?></label>
    <input type="search" 
           id="search-field" 
           class="search-field neomorphic-inset" 
           placeholder="<?php echo esc_attr__('Search...', 'menscore-therapy'); ?>" 
           value="<?php echo get_search_query(); ?>" 
           name="s" />
    <button type="submit" class="search-submit neomorphic">
        <span class="screen-reader-text"><?php _e('Search', 'menscore-therapy'); ?></span>
        <span aria-hidden="true"><?php _e('Search', 'menscore-therapy'); ?></span>
    </button>
</form>