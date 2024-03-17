<?php

function lc_post_nav()
{
    ?>
<div class="d-flex justify-content-center justify-content-sm    -between flex-wrap w-100">
    <?php
    $prev_post_obj = get_adjacent_post('', '', true);
    if ($prev_post_obj) {
        $prev_post_ID   = isset($prev_post_obj->ID) ? $prev_post_obj->ID : '';
        $prev_post_link     = get_permalink($prev_post_ID);
        ?>
    <a href="<?php echo $prev_post_link; ?>" rel="next"
        class="btn btn-previous mb-4 mb-sm-0">Previous</a>
    <?php
    }

    $next_post_obj  = get_adjacent_post('', '', false);
    if ($next_post_obj) {
        $next_post_ID   = isset($next_post_obj->ID) ? $next_post_obj->ID : '';
        $next_post_link     = get_permalink($next_post_ID);
        ?>
    <a href="<?php echo $next_post_link; ?>" rel="next"
        class="btn btn-next mb-4 mb-sm-0">Next</a>
    <?php
    }
    ?>
</div>
<?php

}
