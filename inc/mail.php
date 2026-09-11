<?php
/**
 * Theme mail helpers (SMTP + local fallback logging).
 *
 * @package seahivez-theme
 */

/**
 * Mail configuration from wp-content/seahivez-secrets.php constants.
 *
 * @return array<string, mixed>
 */
function seahivez_get_mail_config() {
	$from_email = defined( 'SEAHIVEZ_MAIL_FROM' ) ? (string) SEAHIVEZ_MAIL_FROM : '';
	$from_name  = defined( 'SEAHIVEZ_MAIL_FROM_NAME' ) ? (string) SEAHIVEZ_MAIL_FROM_NAME : get_bloginfo( 'name' );

	if ( '' === $from_email || ! is_email( $from_email ) ) {
		$from_email = function_exists( 'seahivez_get_booking_notification_email' )
			? seahivez_get_booking_notification_email()
			: get_option( 'admin_email' );
	}

	return array(
		'smtp_host'  => defined( 'SEAHIVEZ_SMTP_HOST' ) ? (string) SEAHIVEZ_SMTP_HOST : '',
		'smtp_port'  => defined( 'SEAHIVEZ_SMTP_PORT' ) ? (int) SEAHIVEZ_SMTP_PORT : 587,
		'smtp_user'  => defined( 'SEAHIVEZ_SMTP_USER' ) ? (string) SEAHIVEZ_SMTP_USER : '',
		'smtp_pass'  => defined( 'SEAHIVEZ_SMTP_PASS' ) ? (string) SEAHIVEZ_SMTP_PASS : '',
		'from_email' => $from_email,
		'from_name'  => $from_name,
	);
}

/**
 * Whether SMTP delivery is configured.
 *
 * @return bool
 */
function seahivez_is_smtp_configured() {
	$config = seahivez_get_mail_config();

	return '' !== $config['smtp_host'] && '' !== $config['smtp_user'] && '' !== $config['smtp_pass'];
}

/**
 * Configure PHPMailer for SMTP when secrets are present.
 *
 * @param PHPMailer\PHPMailer\PHPMailer $phpmailer Mailer instance.
 */
function seahivez_configure_phpmailer( $phpmailer ) {
	if ( ! seahivez_is_smtp_configured() ) {
		return;
	}

	$config = seahivez_get_mail_config();

	$phpmailer->isSMTP();
	$phpmailer->Host       = $config['smtp_host'];
	$phpmailer->Port       = $config['smtp_port'];
	$phpmailer->SMTPAuth   = true;
	$phpmailer->Username   = $config['smtp_user'];
	$phpmailer->Password   = $config['smtp_pass'];
	$phpmailer->SMTPSecure = 465 === (int) $config['smtp_port'] ? 'ssl' : 'tls';
	$phpmailer->CharSet    = 'UTF-8';

	if ( is_email( $config['from_email'] ) ) {
		$phpmailer->setFrom( $config['from_email'], $config['from_name'], false );
	}
}
add_action( 'phpmailer_init', 'seahivez_configure_phpmailer' );

/**
 * @param string $email Default from email.
 * @return string
 */
function seahivez_filter_wp_mail_from( $email ) {
	$config = seahivez_get_mail_config();

	return is_email( $config['from_email'] ) ? $config['from_email'] : $email;
}
add_filter( 'wp_mail_from', 'seahivez_filter_wp_mail_from' );

/**
 * @param string $name Default from name.
 * @return string
 */
function seahivez_filter_wp_mail_from_name( $name ) {
	$config = seahivez_get_mail_config();

	return '' !== $config['from_name'] ? $config['from_name'] : $name;
}
add_filter( 'wp_mail_from_name', 'seahivez_filter_wp_mail_from_name' );

/**
 * Log mail transport errors.
 *
 * @param WP_Error $error Mail error.
 */
function seahivez_log_wp_mail_failed( $error ) {
	if ( ! $error instanceof WP_Error ) {
		return;
	}

	seahivez_write_mail_log(
		'',
		'mail-failed',
		$error->get_error_message() . "\n" . wp_json_encode( $error->get_error_data() )
	);
}
add_action( 'wp_mail_failed', 'seahivez_log_wp_mail_failed' );

/**
 * Write a mail payload to the uploads log (local fallback / debugging).
 *
 * @param string $to      Recipient.
 * @param string $subject Subject.
 * @param string $body    Body.
 * @return string Log file path or empty string.
 */
function seahivez_write_mail_log( $to, $subject, $body ) {
	$uploads = wp_upload_dir();

	if ( ! empty( $uploads['error'] ) ) {
		return '';
	}

	$dir = trailingslashit( $uploads['basedir'] ) . 'seahivez-mail-log';

	if ( ! wp_mkdir_p( $dir ) ) {
		return '';
	}

	$file = trailingslashit( $dir ) . 'booking-' . gmdate( 'Y-m-d' ) . '.log';
	$line = implode(
		"\n",
		array(
			'----- ' . gmdate( 'Y-m-d H:i:s' ) . ' UTC -----',
			'To: ' . $to,
			'Subject: ' . $subject,
			$body,
			'',
		)
	);

	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_system_operations_file_put_contents
	file_put_contents( $file, $line, FILE_APPEND | LOCK_EX );

	return $file;
}

/**
 * Send a theme email and record delivery status.
 *
 * @param string               $to      Recipient.
 * @param string               $subject Subject.
 * @param string               $body    Body.
 * @param array<int, string>   $headers Headers.
 * @param int                  $post_id Optional related post ID.
 * @return bool
 */
function seahivez_send_theme_mail( $to, $subject, $body, $headers = array(), $post_id = 0 ) {
	$sent = wp_mail( $to, $subject, $body, $headers );

	if ( $post_id > 0 ) {
		update_post_meta( $post_id, '_seahivez_email_sent', $sent ? '1' : '0' );
	}

	if ( ! $sent || ! seahivez_is_smtp_configured() ) {
		$log_file = seahivez_write_mail_log( $to, $subject, $body );

		if ( $post_id > 0 && $log_file ) {
			update_post_meta( $post_id, '_seahivez_email_log', $log_file );
		}
	}

	if ( ! $sent && $post_id > 0 ) {
		update_post_meta( $post_id, '_seahivez_email_error', 'wp_mail_failed' );
	}

	return (bool) $sent;
}
