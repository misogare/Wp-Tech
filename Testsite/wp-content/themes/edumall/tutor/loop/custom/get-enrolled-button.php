<?php
/**
 * Course enroll button
 *
 * @since   1.0.0
 * @author  ThemeMove
 * @url https://thememove.com
 *
 * @package Edumall/TutorLMS/Templates
 * @version 1.0.0
 */

defined( 'ABSPATH' ) || exit;

global $edumall_course;

$tutor_course_sell_by = apply_filters( 'tutor_course_sell_by', null );

$enroll_btn = Edumall_Templates::render_button( [
	'echo' => false,
	'link' => [
		'url' => get_the_permalink(),
	],
	'text' => esc_html__( 'Get Enrolled', 'edumall' ),
	'icon' => 'far fa-shopping-cart',
] );

if ( $tutor_course_sell_by ) {
	switch ( $tutor_course_sell_by ) {
		case 'woocommerce' :
		case 'edd' :
			if ( $edumall_course->is_purchasable() ) {
				$enroll_btn = tutor_course_loop_add_to_cart( false );
			}
			break;
	}
}

$notification_settings = [
	'image' => '',
	'title' => get_the_title(),
];

if ( has_post_thumbnail() ) {
	$thumbnail_id = get_post_thumbnail_id();

	$notification_settings['image'] = Edumall_Image::get_attachment_url_by_id( [
		'id'   => $thumbnail_id,
		'size' => '80x80',
	] );
}

echo '<div class="course-loop-enrolled-button cart-notification" data-notification="' . esc_attr( wp_json_encode( $notification_settings ) ) . '">' . $enroll_btn . '</div>';
