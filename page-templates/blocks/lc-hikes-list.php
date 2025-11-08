<?php
/**
 * Hikes list block to display upcoming hikes and walks.
 *
 * @package lc-mjoa2024
 */

defined( 'ABSPATH' ) || exit;


$class  = $block['className'] ?? 'pb-5';
$cats   = get_field( 'category' );
$noun   = get_field( 'noun' ) ? get_field( 'noun' ) : 'hikes';
$limit  = get_field( 'limit' );
$output = lc_products_by_category( $cats, $noun, $limit ) ? lc_products_by_category( $cats, $noun, $limit ) : null;

// Prepare hike data for JS calendar.
$hikes_for_js = array();

$type_colours = array(
    'guided-walks'          => '#C75B39',
    'group-tours'           => '#287C7A',
    'multi-day-hikes'       => '#2A3D66',
    'challenge-events'      => '#B02E2E',
    'womens-wellness-walks' => '#A890C5',
    'community-events'      => '#FF6B4A',
);

$type_names = array(
    'guided-walks'          => 'Guided Walks',
    'group-tours'           => 'Group Tours',
    'multi-day-hikes'       => 'Multi-Day Hikes',
    'challenge-events'      => 'Challenge Events',
    'womens-wellness-walks' => 'Women\'s Wellness Walks',
    'community-events'      => 'Community Events',
);

if ( ! empty( $output ) ) {
    $today = new DateTime( 'today' );
    foreach ( $output as $h ) {
        if ( $h['start'] < $today ) {
            continue;
        }

        $hike_title = html_entity_decode( $h['title'], ENT_QUOTES | ENT_HTML5, 'UTF-8' );

        $hikes_for_js[] = array(
            'title' => $hike_title,
            'start' => $h['start']->format( 'Y-m-d' ),
            'url'   => $h['link'],
            'color' => $type_colours[ $h['slug'] ] ?? '#3788d8',
        );
    }
}

if ( ! is_front_page() ) {
    // phpcs:disable 
    ?>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" />
    <div class="hikes-list-toggle text-center mb-3">
        <button type="button" class="btn btn-outline-primary active" id="hikes-list-toggle-list">List View</button>
        &nbsp;
        <button type="button" class="btn btn-outline-primary" id="hikes-list-toggle-calendar">Calendar View</button>
    </div>
    <?php
    // phpcs:enable
}
?>
<div class="hikes-list mx-4 pb-5 <?= esc_attr( $class ); ?>" id="hikes-list-list-view">
    <?php
    if ( ! empty( $output ) ) {
        if ( is_front_page() ) {
            ?>
        <h2 class="h2 headline mb-4">Upcoming Hikes &amp; Walks</h2>
            <?php
        } elseif ( is_page( 'hikes' ) ) {
            ?>
        <h2 class="h2 headline mb-4">Upcoming Hikes &amp; Walks</h2>
            <?php
        } elseif ( ! is_page( 'all-hikes' ) ) {
            ?>
        <h2 class="h2 headline mb-4">Upcoming <?= esc_html( get_the_title( get_the_ID() ) ); ?></h2>
            <?php
        }
        $e = 0;
        usort(
            $output,
            function ( $a, $b ) {
                if ( $a['start'] === $b['start'] ) {
                    return 0;
                }
                return ( $a['start'] < $b['start'] ) ? -1 : 1;
            }
        );
        $today         = new DateTime( 'today' );
        $current_month = '';
        foreach ( $output as $h ) {
            if ( $h['start'] < $today ) {
                continue;
            }
            $event_month = $h['start']->format( 'F Y' );
            if ( $event_month !== $current_month ) {
                echo '<h3 class="hikes-list__month-title mb-3">' . esc_html( $event_month ) . '</h3>';
                $current_month = $event_month;
            }
            ++$e;
            $product        = wc_get_product( $h['product'] );
            $stock_quantity = $product->get_stock_quantity();

            $banner       = '';
            $banner_class = '';

            if ( is_numeric( $stock_quantity ) ) {
                if ( 0 === $stock_quantity ) {
                    $banner       = 'SOLD OUT';
                    $banner_class = 'sold-out';
                } elseif ( $stock_quantity < 3 ) {
                    $banner       = '*NEARLY FULL*';
                    $banner_class = 'last-few';
                }
            } else {
                $banner = 'Stock status: ' . $product->get_stock_status(); // Out of stock, on backorder, etc.
            }
            ?>
        <a class="hikes-list__row" style="border-left: 0.5rem solid <?= esc_attr( $type_colours[ $h['slug'] ] ?? '#3788d8' ); ?>;border-radius:0.25rem;"
            href="<?= esc_url( $h['link'] ); ?>">
            <?php
            if ( 'sold-out' === $banner_class ) {
                ?>
            <div class="hikes-list__banner--<?= esc_attr( $banner_class ); ?>">
                <?= esc_html( $banner ); ?>
            </div>
                <?php
            }
            ?>
            <img class="hikes-list__icon"
                src="<?= esc_url( get_stylesheet_directory_uri() . '/img/icons/icon--' . $h['slug'] . '.svg' ); ?>">
            <div class="hikes-list__meta">
                <div class="hikes-list__date">
                    <?= esc_html( $h['start']->format( 'D jS F, Y' ) ); ?>
                    <?php
                    if ( 'last-few' === $banner_class ) {
                        ?>
                    <span class="hikes-list__banner--<?= esc_attr( $banner_class ); ?>">
                        <?= wp_kses_post( $banner ); ?>
                    </span>
                        <?php
                    }
                    ?>
                </div>
                <div class="hikes-list__title">
                    <?= esc_html( $h['title'] ); ?>
                </div>
                <div class="hikes-list__price">
                    <?= wp_kses_post( $h['price'] ); ?>
                </div>
                <div class="hikes-list__desc">
                    <?= esc_html( $h['product']->get_short_description() ); ?>
                </div>
            </div>
        </a>
            <?php
        }
        if ( 0 === $e ) {
            ?>
        <div class="text-center h3 headline">
            No upcoming events found
        </div>
            <?php
        }
    } else {
        ?>
        <div class="text-center h3 headline">
            No upcoming events found
        </div>
        <?php
    }

    if ( ! is_page( 'all-hikes' ) ) {
        ?>
    <div class="pt-4 text-center">
        <a href="/hikes/all-hikes/" class="btn btn-primary">All hikes</a>
    </div>
        <?php
    }
    ?>
</div>
    <?php
    if ( ! is_front_page() ) {
        ?>
<div id="hikes-list-calendar-view" class="mx-4 pb-5" style="display:none;">
        <?php
        if ( is_page( 'all-hikes' ) ) {
            echo '<span id="key-trigger" type="button"data-bs-toggle="collapse" data-bs-target="#collapseKey" aria-expanded="false" aria-controls="collapseKey" class="mb-2"><i class="fa-solid fa-key"></i> <strong>Key</strong></span>';
            echo '<div class="collapse" id="collapseKey">';
            echo '<div class="d-flex flex-wrap">';
            // output colour key.
            foreach ( $type_names as $slug => $name ) {
                ?>
        <div class="hikes-list__color-key d-flex align-items-center gap-2">
            <span class="hikes-list__color-key-indicator" style="display:inline-block;width:1rem;height:1rem;background-color:<?= esc_attr( $type_colours[ $slug ] ?? '#000000' ); ?>;border-radius: 100%;"></span>
            <span class="hikes-list__color-key-label"><?= esc_html( $name ); ?></span>
        </div>
                <?php
            }
            echo '</div>';
            echo '</div>';
        }
        ?>
    <div class="mt-4" id="hikes-calendar"></div>
</div>
<?php // phpcs:disable ?>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<?php // phpcs:enable ?>
<script>
// Hike data for calendar
const hikesEvents = <?php echo wp_json_encode( $hikes_for_js ); ?>;

// Toggle logic
document.addEventListener('DOMContentLoaded', function() {
    const btnList = document.getElementById('hikes-list-toggle-list');
    const btnCal = document.getElementById('hikes-list-toggle-calendar');
    const listView = document.getElementById('hikes-list-list-view');
    const calView = document.getElementById('hikes-list-calendar-view');

    btnList.addEventListener('click', function() {
        btnList.classList.add('active');
        btnCal.classList.remove('active');
        listView.style.display = '';
        calView.style.display = 'none';
    });
    btnCal.addEventListener('click', function() {
        btnCal.classList.add('active');
        btnList.classList.remove('active');
        listView.style.display = 'none';
        calView.style.display = '';
        // Initialize calendar if not already
        if (!window.hikesCalendarInitialized) {
            const calendarEl = document.getElementById('hikes-calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: hikesEvents,
                eventClick: function(info) {
                    if (info.event.url) {
                        // do nothing
                    }
                },
                height: 'auto',
            });
            calendar.render();
            window.hikesCalendarInitialized = true;
        }
    });
});
</script>
        <?php
    }