<?php

class FactoryHtmlHelpers {

    public static function radio($values, $options = array()) {
        $theme = isset( $options['theme'] ) ? $options['theme'] : 'standard';
        $selected = isset( $options['selected'] ) ? $options['selected'] : false;
        ?>
        <select 
            id="<?php echo $options['name'] ?>"
            name="<?php echo $options['name'] ?>" 
            class="onp-radio-<?php echo $theme ?> auto">
            <?php foreach($values as $value) { ?>
            <option 
                value="<?php echo $value['value'] ?>" 
                <?php if ( !isset( $value['disabled'] ) && isset( $value['icon'] ) ) { ?>data-icon="<?php echo $value['icon'] ?>"<?php } ?> 
                <?php if ( $selected == $value['value'] ) { ?>selected="selected"<?php } ?>
                <?php if ( isset( $value['disabled'] ) && $value['disabled'] ) { ?>disabled="disabled"<?php } ?>>
                <?php echo $value['title'] ?>
            </option>
            <?php } ?>
        </select>
        <?php
    }
}
?>
