<?php
/**
 * Template: element-type-date.php
 *
 * Available data: $element_id, $type, $id, $name, $classes, $description, $required, $answers, $response, $extra_attr, $response_year, $response_month, $response_day, $years, $months, $days
 */

?>
<div id="<?php echo esc_attr( $id ); ?>">

  <label for="<?php echo esc_attr( $id ); ?>-year" class="screen-reader-text"><?php _e( 'Year', 'torro-forms-plugin-boilerplate' ); ?></label>
  <select id="<?php echo esc_attr( $id ); ?>-year" name="<?php echo esc_attr( $name ); ?>[year]" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"<?php echo $extra_attr; ?>>
    <?php foreach ( $years as $year ) : ?>
      <option value="<?php echo esc_attr( $year['value'] ); ?>"<?php echo $year['value'] === $response_year ? ' selected' : ''; ?>><?php echo esc_html( $year['label'] ); ?></option>
    <?php endforeach; ?>
  </select>

  <label for="<?php echo esc_attr( $id ); ?>-month" class="screen-reader-text"><?php _e( 'Month', 'torro-forms-plugin-boilerplate' ); ?></label>
  <select id="<?php echo esc_attr( $id ); ?>-month" name="<?php echo esc_attr( $name ); ?>[month]" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"<?php echo $extra_attr; ?>>
    <?php foreach ( $months as $month ) : ?>
      <option value="<?php echo esc_attr( $month['value'] ); ?>"<?php echo $month['value'] === $response_month ? ' selected' : ''; ?>><?php echo esc_html( $month['label'] ); ?></option>
    <?php endforeach; ?>
  </select>

  <label for="<?php echo esc_attr( $id ); ?>-day" class="screen-reader-text"><?php _e( 'Day', 'torro-forms-plugin-boilerplate' ); ?></label>
  <select id="<?php echo esc_attr( $id ); ?>-day" name="<?php echo esc_attr( $name ); ?>[day]" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"<?php echo $extra_attr; ?>>
    <?php foreach ( $days as $day ) : ?>
      <option value="<?php echo esc_attr( $day['value'] ); ?>"<?php echo $day['value'] === $response_day ? ' selected' : ''; ?>><?php echo esc_html( $day['label'] ); ?></option>
    <?php endforeach; ?>
  </select>

</div>