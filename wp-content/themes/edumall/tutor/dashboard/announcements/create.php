<?php
/**
 * Add New Announcements Modal
 *
 * @theme-since   2.3.0
 * @theme-version 2.6.1
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="tutor-modal-wrap tutor-announcements-modal-wrap tutor-accouncement-create-modal">
	<div class="tutor-modal-content tutor-announcement-modal-content">
		<div class="modal-header">
			<div class="modal-title">
				<h1><?php esc_html_e( 'Create New Announcement', 'edumall' ); ?></h1>
			</div>
			<div class="tutor-announcements-modal-close-wrap">
				<a href="#" class="tutor-announcement-close-btn">
					<i class="tutor-icon-line-cross"></i>
				</a>
			</div>
		</div>
		<div class="modal-container">
			<form action="" class="tutor-announcements-form">
				<?php tutor_nonce_field(); ?>
				<div class="tutor-form-group">
					<label>
						<?php esc_html_e( 'Select Course', 'edumall' ); ?>
					</label>
					<select class="ignore-nice-select" name="tutor_announcement_course" id="" required>
						<?php if ( $courses ) : ?>
							<?php foreach ( $courses as $course ) : ?>
								<option value="<?php echo esc_attr( $course->ID ) ?>">
									<?php echo $course->post_title; ?>
								</option>
							<?php endforeach; ?>
						<?php else : ?>
							<option value=""><?php esc_html_e( 'No course found', 'edumall' ); ?></option>
						<?php endif; ?>
					</select>
				</div>
				<div class="tutor-form-group">
					<label>
						<?php esc_html_e( 'Announcement Title', 'edumall' ); ?>
					</label>
					<input type="text" name="tutor_announcement_title" value=""
					       placeholder="<?php esc_html_e( 'Announcement title', 'edumall' ); ?>" required>
				</div>
				<div class="tutor-form-group">
					<label for="tutor_announcement_course">
						<?php esc_html_e( 'Summary', 'edumall' ); ?>
					</label>
					<textarea rows="6" name="tutor_announcement_summary"
					          placeholder="<?php esc_html_e( 'Summary...', 'edumall' ); ?>" required></textarea>
				</div>
				<?php if ( $notify_checked ) : ?>
					<div class="tutor-form-group">
						<label for="notify_student_create">
							<input type="checkbox" name="tutor_notify_students" id="notify_student_create" checked>
							<?php esc_html_e( 'Notify to all students of this course.', 'edumall' ); ?>
						</label>
					</div>
				<?php endif; ?>
				<div class="tutor-form-group">
					<div class="tutor-announcements-create-alert"></div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="tutor-btn"><?php esc_html_e( 'Publish', 'edumall' ) ?></button>
					<button type="button"
					        class="quiz-modal-tab-navigation-btn quiz-modal-btn-cancel tutor-announcement-close-btn tutor-announcement-cancel-btn"><?php esc_html_e( 'Cancel', 'edumall' ) ?></button>
				</div>
			</form>
		</div>
	</div>
</div>
