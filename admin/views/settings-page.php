<?php
/**
 * Admin settings view — fields mirror app.aidasales.ir chatbox settings.
 *
 * @package Aida_Chatbox
 *
 * @var array  $settings
 * @var string $questions_text
 * @var bool   $is_rtl
 * @var string $logo_url
 * @var string $face_url
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

settings_errors( 'aida_chatbox' );

$wrap_class = 'wrap aida-chatbox-admin';
if ( ! empty( $is_rtl ) ) {
	$wrap_class .= ' aida-chatbox-rtl';
}

$offset_options = range( 0, 5 );
?>
<div class="<?php echo esc_attr( $wrap_class ); ?>" <?php echo ! empty( $is_rtl ) ? 'dir="rtl" lang="fa"' : 'dir="ltr" lang="en"'; ?>>
	<div class="aida-chatbox-hero">
		<div class="aida-chatbox-hero__brand">
			<img
				src="<?php echo esc_url( $logo_url ); ?>"
				alt="<?php echo esc_attr__( 'Aida', 'aida-chatbox' ); ?>"
				class="aida-chatbox-hero__logo"
			/>
			<img
				src="<?php echo esc_url( $face_url ); ?>"
				alt=""
				class="aida-chatbox-hero__face"
				width="72"
				height="72"
			/>
		</div>
		<div class="aida-chatbox-hero__text">
			<h1><?php echo esc_html__( 'Aida Chatbox Settings', 'aida-chatbox' ); ?></h1>
			<p class="aida-chatbox-lead">
				<?php echo esc_html__( 'Configure the website chatbox here instead of editing a script tag on every page.', 'aida-chatbox' ); ?>
			</p>
		</div>
	</div>

	<section class="aida-chatbox-about" aria-labelledby="aida-about-heading">
		<h2 id="aida-about-heading"><?php echo esc_html__( 'What is Aida?', 'aida-chatbox' ); ?></h2>
		<p>
			<?php echo esc_html__( 'Aida is an AI sales assistant that talks to your customers on your website, social channels, and messengers — 24/7. She answers questions, understands needs, helps persuade toward a purchase, and records leads in your CRM so your team can focus on important conversations.', 'aida-chatbox' ); ?>
		</p>
		<p>
			<?php echo esc_html__( 'This plugin connects your WordPress site to the Aida chatbox service. You need an Aida account and API key; chat replies are provided by Aida’s cloud service.', 'aida-chatbox' ); ?>
		</p>
		<p class="aida-chatbox-about__links">
			<a href="https://aidasales.ir" target="_blank" rel="noopener noreferrer"><?php echo esc_html__( 'Aida website', 'aida-chatbox' ); ?></a>
			<span aria-hidden="true"> · </span>
			<a href="https://aidasales.ir/i" target="_blank" rel="noopener noreferrer"><?php echo esc_html__( 'Live demos', 'aida-chatbox' ); ?></a>
			<span aria-hidden="true"> · </span>
			<a href="https://app.aidasales.ir" target="_blank" rel="noopener noreferrer"><?php echo esc_html__( 'Aida app', 'aida-chatbox' ); ?></a>
		</p>
	</section>

	<form method="post" action="" id="aida-chatbox-settings-form">
		<?php wp_nonce_field( 'aida_chatbox_save_settings', 'aida_chatbox_nonce' ); ?>

		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php echo esc_html__( 'Enable chatbox', 'aida-chatbox' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="aida_chatbox[enabled]" value="1" <?php checked( ! empty( $settings['enabled'] ) ); ?> />
						<?php echo esc_html__( 'Show Aida Chatbox on the public site', 'aida-chatbox' ); ?>
					</label>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="aida_api_key"><?php echo esc_html__( 'API key', 'aida-chatbox' ); ?></label>
				</th>
				<td>
					<input
						type="text"
						class="regular-text code"
						id="aida_api_key"
						name="aida_chatbox[api_key]"
						value="<?php echo esc_attr( $settings['api_key'] ); ?>"
						autocomplete="off"
						required
					/>
					<p class="description">
						<?php echo esc_html__( 'From app.aidasales.ir → Channel Settings. Required.', 'aida-chatbox' ); ?>
					</p>
				</td>
			</tr>
		</table>

		<h2 class="title"><?php echo esc_html__( 'Chatbox settings', 'aida-chatbox' ); ?></h2>
		<table class="form-table" role="presentation">
			<tr>
				<th scope="row"><?php echo esc_html__( 'Position', 'aida-chatbox' ); ?></th>
				<td>
					<select name="aida_chatbox[position]" id="aida_position">
						<option value="right" <?php selected( $settings['position'], 'right' ); ?>><?php echo esc_html__( 'Right', 'aida-chatbox' ); ?></option>
						<option value="left" <?php selected( $settings['position'], 'left' ); ?>><?php echo esc_html__( 'Left', 'aida-chatbox' ); ?></option>
					</select>
					<p class="description"><?php echo esc_html__( 'Choose which corner of the page the chat button appears in.', 'aida-chatbox' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php echo esc_html__( 'Default state', 'aida-chatbox' ); ?></th>
				<td>
					<select name="aida_chatbox[initial_state]" id="aida_initial_state">
						<option value="closed" <?php selected( $settings['initial_state'], 'closed' ); ?>><?php echo esc_html__( 'Closed', 'aida-chatbox' ); ?></option>
						<option value="open" <?php selected( $settings['initial_state'], 'open' ); ?>><?php echo esc_html__( 'Open', 'aida-chatbox' ); ?></option>
					</select>
					<p class="description"><?php echo esc_html__( 'Whether the chat window starts open or closed when the page loads.', 'aida-chatbox' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php echo esc_html__( 'Distance from bottom', 'aida-chatbox' ); ?></th>
				<td class="aida-chatbox-offset">
					<label>
						<span><?php echo esc_html__( 'Desktop', 'aida-chatbox' ); ?></span>
						<select name="aida_chatbox[bottom]" id="aida_bottom">
							<?php foreach ( $offset_options as $n ) : ?>
								<option value="<?php echo esc_attr( (string) $n ); ?>" <?php selected( (int) $settings['bottom'], $n ); ?>><?php echo esc_html( (string) $n ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
					<label>
						<span><?php echo esc_html__( 'Mobile', 'aida-chatbox' ); ?></span>
						<select name="aida_chatbox[bottom_mobile]" id="aida_bottom_mobile">
							<?php foreach ( $offset_options as $n ) : ?>
								<option value="<?php echo esc_attr( (string) $n ); ?>" <?php selected( (int) $settings['bottom_mobile'], $n ); ?>><?php echo esc_html( (string) $n ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
					<p class="description"><?php echo esc_html__( 'Move the chat button up from the bottom so it does not cover a fixed bottom menu or navbar. Higher values mean more space. Use 0–5.', 'aida-chatbox' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php echo esc_html__( 'Distance from the side', 'aida-chatbox' ); ?></th>
				<td class="aida-chatbox-offset">
					<label>
						<span><?php echo esc_html__( 'Desktop', 'aida-chatbox' ); ?></span>
						<select name="aida_chatbox[side]" id="aida_side">
							<?php foreach ( $offset_options as $n ) : ?>
								<option value="<?php echo esc_attr( (string) $n ); ?>" <?php selected( (int) $settings['side'], $n ); ?>><?php echo esc_html( (string) $n ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
					<label>
						<span><?php echo esc_html__( 'Mobile', 'aida-chatbox' ); ?></span>
						<select name="aida_chatbox[side_mobile]" id="aida_side_mobile">
							<?php foreach ( $offset_options as $n ) : ?>
								<option value="<?php echo esc_attr( (string) $n ); ?>" <?php selected( (int) $settings['side_mobile'], $n ); ?>><?php echo esc_html( (string) $n ); ?></option>
							<?php endforeach; ?>
						</select>
					</label>
					<p class="description"><?php echo esc_html__( 'Move the chat button inward from the side edge so it does not overlap a scrollbar or side menu. Higher values mean more space. Use 0–5.', 'aida-chatbox' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php echo esc_html__( 'Chatbox open animation delay', 'aida-chatbox' ); ?></th>
				<td>
					<label>
						<input
							type="checkbox"
							name="aida_chatbox[time_open_enabled]"
							id="aida_time_open_enabled"
							value="1"
							<?php checked( ! empty( $settings['time_open_enabled'] ) ); ?>
							data-aida-toggle="aida-time-open-fields"
						/>
						<?php echo esc_html__( 'On', 'aida-chatbox' ); ?>
					</label>
					<div
						id="aida-time-open-fields"
						class="aida-time-open-fields aida-dependent-fields<?php echo empty( $settings['time_open_enabled'] ) ? ' is-hidden' : ''; ?>"
						<?php echo empty( $settings['time_open_enabled'] ) ? ' hidden' : ''; ?>
					>
						<label class="aida-time-open-seconds">
							<span><?php echo esc_html__( 'seconds', 'aida-chatbox' ); ?></span>
							<input
								type="number"
								id="aida_time_open_seconds"
								name="aida_chatbox[time_open_seconds]"
								value="<?php echo esc_attr( (string) max( 1, (int) $settings['time_open_seconds'] ) ); ?>"
								min="1"
								step="1"
								class="small-text"
							/>
						</label>
						<label class="aida-time-open-once">
							<input type="checkbox" name="aida_chatbox[time_open_once]" value="1" <?php checked( ! empty( $settings['time_open_once'] ) ); ?> />
							<?php echo esc_html__( 'Open once', 'aida-chatbox' ); ?>
						</label>
					</div>
					<p class="description"><?php echo esc_html__( 'When enabled, the chat opens automatically after the chosen number of seconds. “Open once” means it only auto-opens the first time for each visitor.', 'aida-chatbox' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php echo esc_html__( "Aida's thinking state while responding", 'aida-chatbox' ); ?></th>
				<td>
					<label>
						<input type="checkbox" name="aida_chatbox[processing]" value="1" <?php checked( ! empty( $settings['processing'] ) ); ?> />
						<?php echo esc_html__( 'On', 'aida-chatbox' ); ?>
					</label>
					<p class="description"><?php echo esc_html__( 'When on, visitors see Aida’s detailed thinking steps while a reply is prepared. When off, they only see a simple typing indicator.', 'aida-chatbox' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="aida_color"><?php echo esc_html__( 'Chatbox color', 'aida-chatbox' ); ?></label>
				</th>
				<td class="aida-chatbox-color-row">
					<input type="color" id="aida_color_picker" value="#<?php echo esc_attr( $settings['color'] ); ?>" data-sync="aida_color" />
					<input
						type="text"
						class="regular-text code"
						id="aida_color"
						name="aida_chatbox[color]"
						value="<?php echo esc_attr( $settings['color'] ); ?>"
						pattern="[0-9A-Fa-f]{3,6}"
						maxlength="6"
					/>
					<p class="description"><?php echo esc_html__( 'Main brand color for the chat button, header, and visitor messages. Pick a color or enter a 6-digit hex code.', 'aida-chatbox' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="aida_color_ai"><?php echo esc_html__( 'AI message background', 'aida-chatbox' ); ?></label>
				</th>
				<td class="aida-chatbox-color-row">
					<input type="color" id="aida_color_ai_picker" value="#<?php echo esc_attr( $settings['color_ai'] ); ?>" data-sync="aida_color_ai" />
					<input
						type="text"
						class="regular-text code"
						id="aida_color_ai"
						name="aida_chatbox[color_ai]"
						value="<?php echo esc_attr( $settings['color_ai'] ); ?>"
						pattern="[0-9A-Fa-f]{3,6}"
						maxlength="6"
					/>
					<p class="description"><?php echo esc_html__( 'Background color of Aida’s reply bubbles. Choose a light color for better readability.', 'aida-chatbox' ); ?></p>
				</td>
			</tr>

			<tr>
				<th scope="row">
					<label for="aida_questions"><?php echo esc_html__( 'FAQ questions', 'aida-chatbox' ); ?></label>
				</th>
				<td>
					<textarea
						id="aida_questions"
						name="aida_chatbox[questions]"
						rows="4"
						class="large-text"
						placeholder="<?php echo esc_attr__( "Question 1\nQuestion 2\nQuestion 3", 'aida-chatbox' ); ?>"
					><?php echo esc_textarea( $questions_text ); ?></textarea>
					<p class="description">
						<?php echo esc_html__( 'Add up to 3 suggested questions shown at the start of the chat. Write one question per line. Visitors can tap a question instead of typing.', 'aida-chatbox' ); ?>
					</p>
				</td>
			</tr>

			<tr>
				<th scope="row"><?php echo esc_html__( 'Collect information before chat', 'aida-chatbox' ); ?></th>
				<td>
					<label>
						<input
							type="checkbox"
							name="aida_chatbox[get_info]"
							value="1"
							id="aida_get_info"
							<?php checked( ! empty( $settings['get_info'] ) ); ?>
							data-aida-toggle="aida-get-info-text-wrap"
						/>
						<?php echo esc_html__( 'On', 'aida-chatbox' ); ?>
					</label>
					<p class="description">
						<?php echo esc_html__( 'Ask for the visitor’s full name and phone number before the conversation starts.', 'aida-chatbox' ); ?>
					</p>
					<div
						id="aida-get-info-text-wrap"
						class="aida-get-info-text-wrap aida-dependent-fields<?php echo empty( $settings['get_info'] ) ? ' is-hidden' : ''; ?>"
						<?php echo empty( $settings['get_info'] ) ? ' hidden' : ''; ?>
					>
						<label for="aida_get_info_text"><strong><?php echo esc_html__( 'Form text', 'aida-chatbox' ); ?></strong></label>
						<textarea
							id="aida_get_info_text"
							name="aida_chatbox[get_info_text]"
							rows="3"
							class="large-text"
							placeholder="<?php echo esc_attr( Aida_Chatbox_Settings::DEFAULT_GET_INFO_TEXT ); ?>"
						><?php echo esc_textarea( $settings['get_info_text'] ); ?></textarea>
						<span class="description"><?php echo esc_html__( 'Message shown above the info form. Leave empty to use Aida’s default welcome text.', 'aida-chatbox' ); ?></span>
					</div>
				</td>
			</tr>
		</table>

		<?php submit_button( __( 'Save settings', 'aida-chatbox' ), 'primary', 'aida_chatbox_save' ); ?>
	</form>

	<p class="description aida-chatbox-note">
		<?php echo esc_html__( 'Remove any old hard-coded Aida script tag from your theme or page builders. This plugin injects the chatbox for you.', 'aida-chatbox' ); ?>
	</p>
</div>
