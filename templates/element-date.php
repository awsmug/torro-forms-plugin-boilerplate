<?php
/**
 * Template: element-date.php
 *
 * Available data: $id, $container_id, $label, $sort, $type, $value, $input_attrs, $label_required, $label_attrs, $wrap_attrs, $description, $description_attrs, $errors, $errors_attrs, $before, $after, $date_fields_order, $date_choices, $date_labels, $date_values, $legend_attrs
 *
 * @package TorroFormsPluginBoilerplate
 * @since 1.0.0
 */

?>
<fieldset<?php echo torro()->template()->attrs( $wrap_attrs ); ?>>
	<?php if ( ! empty( $before ) ) : ?>
		<?php echo $before; ?>
	<?php endif; ?>

	<legend<?php echo torro()->template()->attrs( $legend_attrs ); ?>>
		<?php echo torro()->template()->esc_kses_basic( $label ); ?>
		<?php echo torro()->template()->esc_kses_basic( $label_required ); ?>
	</legend>

	<div>
		<div class="date-select-wrap">
			<?php foreach ( $date_fields_order as $field ) : ?>
				<?php
				$field_input_attrs = $input_attrs;
				$field_label_attrs = $label_attrs;

				$field_input_attrs['name'] = str_replace( '%index%', $field, $field_input_attrs['name'] );
				$field_input_attrs['id']   = str_replace( '%index%', $field, $field_input_attrs['id'] );
				$field_label_attrs['id']   = str_replace( '%index%', $field, $field_label_attrs['id'] );
				$field_label_attrs['for']  = str_replace( '%index%', $field, $field_label_attrs['for'] );

				$field_choices = $field . '_choices';
				$field_label   = $field . '_label';
				$field_value   = $field . '_value';
				?>
				<label<?php echo torro()->template()->attrs( $field_label_attrs ); ?>>
					<?php echo torro()->template()->esc_kses_basic( $date_labels[ $field ] ); ?>
				</label>
				<select<?php echo torro()->template()->attrs( $field_input_attrs ); ?>>
					<?php foreach ( $date_choices[ $field ] as $choice_value => $choice_label ) : ?>
						<option value="<?php echo torro()->template()->esc_attr( $choice_value ); ?>"<?php echo (int) $date_values[ $field ] === (int) $choice_value ? ' selected' : ''; ?>>
							<?php echo torro()->template()->esc_html( $choice_label ); ?>
						</option>
					<?php endforeach; ?>
				</select>
			<?php endforeach; ?>
		</div>

		<?php if ( ! empty( $description ) ) : ?>
			<div<?php echo torro()->template()->attrs( $description_attrs ); ?>>
				<?php echo torro()->template()->esc_kses_basic( $description ); ?>
			</div>
		<?php endif; ?>

		<?php if ( ! empty( $errors ) ) : ?>
			<ul<?php echo torro()->template()->attrs( $errors_attrs ); ?>>
				<?php foreach ( $errors as $error_code => $error_message ) : ?>
					<li><?php echo torro()->template()->esc_kses_basic( $error_message ); ?></li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</div>

	<?php if ( ! empty( $after ) ) : ?>
		<?php echo $after; ?>
	<?php endif; ?>
</fieldset>
