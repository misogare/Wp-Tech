<?php
/**
 * Template for displaying student Public Profile
 *
 * @author        Themeum
 * @url https://themeum.com
 * @package       TutorLMS/Templates
 * @since         1.0.0
 * @version       1.4.3
 *
 * @theme-since   1.0.0
 * @theme-version 2.6.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

global $wp_query;

$dashboard_page_slug = '';
$dashboard_page_name = '';
if ( isset( $wp_query->query_vars['tutor_dashboard_page'] ) && $wp_query->query_vars['tutor_dashboard_page'] ) {
	$dashboard_page_slug = $wp_query->query_vars['tutor_dashboard_page'];
	$dashboard_page_name = $wp_query->query_vars['tutor_dashboard_page'];
}
/**
 * Getting dashboard sub pages
 */
if ( isset( $wp_query->query_vars['tutor_dashboard_sub_page'] ) && $wp_query->query_vars['tutor_dashboard_sub_page'] ) {
	$dashboard_page_name = $wp_query->query_vars['tutor_dashboard_sub_page'];
	if ( $dashboard_page_slug ) {
		$dashboard_page_name = $dashboard_page_slug . '/' . $dashboard_page_name;
	}
}

$user_id                   = get_current_user_id();
$user                      = get_user_by( 'ID', $user_id );
$enable_profile_completion = tutils()->get_option( 'enable_profile_completion' );
?>
	<div class="page-content">
		<div class="tutor-dashboard-header-wrap">
			<div class="container small-gutter">
				<div class="row">
					<div class="col-md-12">
						<div class="tutor-dashboard-header">
							<div class="dashboard-header-toggle-menu dashboard-header-menu-open-btn">
								<?php echo Edumall_Helper::get_file_contents( EDUMALL_THEME_SVG_DIR . '/icon-toggle-sidebar.svg' ); ?>
							</div>
							<div class="tutor-header-user-info">
								<div class="tutor-dashboard-header-avatar">
									<?php echo edumall_get_avatar( $user_id, 150 ); ?>
								</div>
								<div class="tutor-dashboard-header-info">
									<h4 class="tutor-dashboard-header-display-name">
										<span class="welcome-text"><?php esc_html_e( 'Howdy,', 'edumall' ); ?></span>
										<?php echo esc_html( $user->display_name ); ?>
									</h4>

									<?php if ( current_user_can( tutor()->instructor_role ) ) : ?>
										<?php
										$instructor_rating       = tutils()->get_instructor_ratings( $user->ID );
										$instructor_rating_count = sprintf(
											_n( '%s rating', '%s ratings', $instructor_rating->rating_count, 'edumall' ),
											number_format_i18n( $instructor_rating->rating_count )
										);
										?>
										<div class="tutor-dashboard-header-stats">
											<div class="tutor-dashboard-header-ratings">
												<?php Edumall_Templates::render_rating( $instructor_rating->rating_avg ) ?>
												<span
													class="rating-average"><?php echo esc_html( $instructor_rating->rating_avg ); ?></span>
												<span class="rating-count">
												<?php echo '(' . esc_html( $instructor_rating_count ) . ')'; ?>
											</span>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>

							<div class="tutor-dashboard-header-button">
								<?php
								if ( current_user_can( tutor()->instructor_role ) ) {
									$course_type       = tutor()->course_post_type;
									$create_course_url = apply_filters( 'frontend_course_create_url', admin_url( "post-new.php?post_type=" . tutor()->course_post_type ) );

									Edumall_Templates::render_button( [
										'link'        => [
											'url' => $create_course_url,
										],
										'text'        => esc_html__( 'Add A New Course ', 'edumall' ),
										'icon'        => 'edumi edumi-content-writing',
										'style'       => 'thick-border',
										'extra_class' => 'button-grey',
									] );
								} else {
									if ( tutils()->get_option( 'enable_become_instructor_btn' ) ) {
										Edumall_Templates::render_button( [
											'link'        => [
												'url' => esc_url( tutils()->instructor_register_url() ),
											],
											'text'        => esc_html__( 'Become an instructor', 'edumall' ),
											'icon'        => 'edumi edumi-user',
											'style'       => 'thick-border',
											'extra_class' => 'button-grey open-popup-instructor-register',
										] );
									}
								}
								?>
							</div>

							<?php Edumall_Header::instance()->print_open_canvas_menu_button(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="container small-gutter">
			<div class="row">
				<div class="page-main-content">
					<?php do_action( 'tutor_dashboard/notification_area' ); ?>

					<div class="tutor-dashboard-content">
						<?php
						if ( $dashboard_page_name ) {
							do_action( 'tutor_load_dashboard_template_before', $dashboard_page_name );
							tutor_load_template( "dashboard." . $dashboard_page_name );
							do_action( 'tutor_load_dashboard_template_before', $dashboard_page_name );
						} else {
							tutor_load_template( "dashboard.dashboard" );
						}
						?>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php
get_footer();
