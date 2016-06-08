<?php
/**
 * Template: element-iframe.php
 *
 * Available data: $element_id, $label, $id, $classes, $errors, $description, $required, $type
 */
?>
<div class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
  <?php do_action( 'torro_element_start', $element_id ); ?>

  <div class="iframe-container">
    <div class="iframe-wrap">
      <iframe src="<?php echo esc_url( $type['iframe_src'] ); ?>"></iframe>
    </div>
  </div>

  <?php do_action( 'torro_element_end', $element_id ); ?>
</div>