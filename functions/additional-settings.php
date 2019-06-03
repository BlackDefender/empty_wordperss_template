<?php


// подключение стилей
function add_settings_style($hook) {
	if ( 'toplevel_page_additional_settings' == $hook ) {
		wp_enqueue_style('additional-settings-assets', get_template_directory_uri() . '/functions/assets/additional-settings.css');
	}
}
add_action('admin_enqueue_scripts', 'add_settings_style');


function add_additional_settings(){
	function render_settings_page(){
		?>
		<div class="wrap">
			<h2><?php echo get_admin_page_title() ?></h2>
			<form action="options.php" method="POST" class="additional-settings-form">
				<?php
				settings_fields("additional_settings_group");     // скрытые защитные поля
				do_settings_sections("additional_settings_page"); // секции с настройками (опциями).
				submit_button();
				?>
			</form>
		</div>
		<?php
	}

	add_menu_page('Дополнительные настройки', 'Дополнительные настройки', 'manage_options', 'additional_settings', 'render_settings_page', 'dashicons-desktop');
}
add_action( 'admin_menu', 'add_additional_settings' );



/**
 * Регистрируем настройки.
 * Настройки будут храниться в массиве, а не одна настройка = одна опция.
 */
add_action('admin_init', 'register_additional_settings');
function register_additional_settings(){

	/* ОБЩИЕ НАСТРОЙКИ САЙТА */
	// параметры: $option_group, $option_name, $sanitize_callback
	register_setting( 'additional_settings_group', 'common_site_settings' );
	// параметры: $id, $title, $callback, $page
	add_settings_section( 'common_site_settings_section', 'Общие настройки сайта', '', 'additional_settings_page' );
	// параметры: $id, $title, $callback, $page, $section, $args
	add_settings_field('notification_email', 'Почта для отправки уведомлений через запятую', 'render_input_field', 'additional_settings_page', 'common_site_settings_section', array( 'option' => 'common_site_settings', 'id' => 'notification_email', 'type' => 'text') );

	/* КОНТАКТНЫЕ ДАННЫЕ */
	register_setting( 'additional_settings_group', 'contacts_data' );
	add_settings_section( 'contacts_data_section', 'Контактные данные', '', 'additional_settings_page' );

	add_settings_field('email', 'E-mail', 'render_input_field', 'additional_settings_page', 'contacts_data_section', array( 'option' => 'contacts_data', 'id' => 'email', 'type' => 'email') );

	add_settings_field('tel1', 'Телефон 1', 'render_input_field', 'additional_settings_page', 'contacts_data_section', array( 'option' => 'contacts_data', 'id' => 'tel1', 'type' => 'text') );
	add_settings_field('tel2', 'Телефон 2', 'render_input_field', 'additional_settings_page', 'contacts_data_section', array( 'option' => 'contacts_data', 'id' => 'tel2', 'type' => 'text') );
	add_settings_field('tel3', 'Телефон 3', 'render_input_field', 'additional_settings_page', 'contacts_data_section', array( 'option' => 'contacts_data', 'id' => 'tel3', 'type' => 'text') );
	
	add_settings_field('address', 'Адрес', 'render_input_field', 'additional_settings_page', 'contacts_data_section', array( 'option' => 'contacts_data', 'id' => 'address', 'type' => 'text') );

    add_settings_field('facebook', 'Facebook', 'render_input_field', 'additional_settings_page', 'contacts_data_section', array( 'option' => 'contacts_data', 'id' => 'facebook', 'type' => 'text') );
    add_settings_field('vk', 'VK', 'render_input_field', 'additional_settings_page', 'contacts_data_section', array( 'option' => 'contacts_data', 'id' => 'vk', 'type' => 'text') );
    add_settings_field('google_plus', 'Google +', 'render_input_field', 'additional_settings_page', 'contacts_data_section', array( 'option' => 'contacts_data', 'id' => 'google_plus', 'type' => 'text') );
    add_settings_field('linkedin', 'LinkedIn', 'render_input_field', 'additional_settings_page', 'contacts_data_section', array( 'option' => 'contacts_data', 'id' => 'linkedin', 'type' => 'text') );
    add_settings_field('twitter', 'Twitter', 'render_input_field', 'additional_settings_page', 'contacts_data_section', array( 'option' => 'contacts_data', 'id' => 'twitter', 'type' => 'text') );
    add_settings_field('instagram', 'Instagram', 'render_input_field', 'additional_settings_page', 'contacts_data_section', array( 'option' => 'contacts_data', 'id' => 'instagram', 'type' => 'text') );
    add_settings_field('medium', 'Medium', 'render_input_field', 'additional_settings_page', 'contacts_data_section', array( 'option' => 'contacts_data', 'id' => 'medium', 'type' => 'text') );
    add_settings_field('dribble', 'Dribble', 'render_input_field', 'additional_settings_page', 'contacts_data_section', array( 'option' => 'contacts_data', 'id' => 'dribble', 'type' => 'text') );
    add_settings_field('behance', 'Behance', 'render_input_field', 'additional_settings_page', 'contacts_data_section', array( 'option' => 'contacts_data', 'id' => 'behance', 'type' => 'text') );
    add_settings_field('pinterest', 'Pinterest', 'render_input_field', 'additional_settings_page', 'contacts_data_section', array( 'option' => 'contacts_data', 'id' => 'pinterest', 'type' => 'text') );
	
	
	/*add_settings_field('testParam', 'TestParam', 'render_select_field', 'additional_settings_page', 'contacts_data_section', array( 'option' => 'contacts_data', 'id' => 'testParam', 'selectOptions' => [
            'option-1' => 'Option 1',
            'option-2' => 'Option 2',
    ]));*/
	
	
}

function render_input_field($args){
	$val = get_option($args['option']);
	$val = $val[$args['id']];
	echo '<input type="', $args['type'] ,'" name="' , $args['option'] , '[' , $args['id'] , ']" value="', esc_attr( $val ), '" />';
}

function render_textarea_field($args){
	$val = get_option($args['option']);
	$val = $val[$args['id']];
	echo '<textarea name="' , $args['option'] , '[' , $args['id'] , ']" >' , esc_attr( $val ) , '</textarea>';
}

function render_select_field($args){
    $currentValue = get_option($args['option'])[$args['id']];
    ?>
    <select name="<?= $args['option'].'['.$args['id'].']'; ?>">
        <?php
        foreach ($args['selectOptions'] as $val => $title){
            $selected = $currentValue == $val ? 'selected' : '';
            ?>
            <option value="<?= $val; ?>" <?= $selected; ?>><?= $title; ?></option>
            <?php
        }
        ?>
    </select>
    <?php
}
