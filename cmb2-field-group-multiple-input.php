<?php
/**
 * Template tag for displaying an address from the CMB2 address field type (on the front-end)
 */
function jt_cmb2_food_menu_items_field($metakey, $post_id = 0)
{
    echo jt_cmb2_get_food_menu_items_field($metakey, $post_id);
}

/**
 * Template tag for returning an address from the CMB2 address field type (on the front-end)
 */
function jt_cmb2_get_food_menu_items_field($metakey, $post_id = 0)
{
    $post_id = $post_id ? $post_id : get_the_ID();
    $item = get_post_meta($post_id, $metakey, 1);
    // Set default values for each address key
    $item = wp_parse_args($item, array(
        'item_name' => '',
        'item_description' => '',
        'item_price' => '',
    ));
    $output = '<div class="cmb2-menu">';
    $output .= '<p><strong>Name:</strong> ' . esc_html($item['item_name']) . '</p>';
    $output .= '<p><strong>Description:</strong> ' . esc_html($item['item_description']) . '</p>';
    $output .= '<p><strong>Price:</strong> ' . esc_html($item['item_price']) . '</p>';
    $output = '</div><!-- .cmb2-menu -->';
    return apply_filters('jt_cmb2_get_food_menu_items_field', $output);
}
/**
* Render the custom field food_menu_items on the custon field section on admin pages setup
*/
function jt_cmb2_render_food_menu_items_field_callback($field, $value, $object_id, $object_type, $field_type_object)
{
    // make sure we specify each part of the value we need.
    $value = wp_parse_args($value, array(
        'item_name' => '',
        'item_description' => '',
        'item_price' => '',
    ));

    ?>
    <div>
        <p><label for="<?php echo $field_type_object->_id('_item_name'); ?>"><?php echo esc_html($field_type_object->_text('food_menu_items_name_text', 'Name')); ?></label></p>
        <?php echo $field_type_object->input(array(
            'name'  => $field_type_object->_name('[item_name]'),
            'id'    => $field_type_object->_id('_item_name'),
            'value' => $value['item_name'],
        )); ?>
        <p><label for="<?php echo $field_type_object->_id('_item_desription'); ?>"><?php echo esc_html($field_type_object->_text('food_menu_items_description_text', 'Description')); ?></label></p>
        <?php echo $field_type_object->input(array( // working with both, input and textarea fields. Change as needed.
            'name'  => $field_type_object->_name('[item_description]'),
            'id'    => $field_type_object->_id('_item_description'),
            'value' => $value['item_description'],
        )); ?>
        <p><label for="<?php echo $field_type_object->_id('_item_price'); ?>"><?php echo esc_html($field_type_object->_text('food_menu_items_price_text', 'Price')); ?></label></p>
        <?php echo $field_type_object->input(array(
            'class' => 'cmb_text_small',
            'name'  => $field_type_object->_name('[item_price]'),
            'id'    => $field_type_object->_id('_item_price'),
            'value' => $value['item_price'],
        )); ?>
    </div>
    
<?php
    echo $field_type_object->_desc(true);
}
add_filter('cmb2_render_food_menu_items', 'jt_cmb2_render_food_menu_items_field_callback', 10, 5);
/**
* Optionally save the Address values into separate fields
*/
function cmb2_split_food_menu_items_values($override_value, $value, $object_id, $field_args)
{
    if (!isset($field_args['split_values']) || !$field_args['split_values']) {
        // Don't do the override
        return $override_value;
    }
    $food_menu_item_keys = array('item_name', 'item_description', 'item_price');
    foreach ($food_menu_item_keys as $key) {
        if (!empty($value[$key])) {
            update_post_meta($object_id, $field_args['id'] . 'fmitem_' . $key, $value[$key]);
        }
    }
    // Tell CMB2 we already did the update
    return true;
}
add_filter('cmb2_sanitize_food_menu_items', 'cmb2_split_food_menu_items_values', 12, 4);
/**
* The following snippets are required for allowing the address field
* to work as a repeatable field, or in a repeatable group
*/
function cmb2_sanitize_food_menu_items_field($check, $meta_value, $object_id, $field_args, $sanitize_object)
{
    // if not repeatable, bail out.
    if (!is_array($meta_value) || !$field_args['repeatable']) {
        return $check;
    }
    
    foreach ($meta_value as $key => $val) {
        $meta_value[$key] = array_map('sanitize_text_field', $val);
    }
    //return array();
    return $meta_value;
}
add_filter('cmb2_sanitize_food_menu_items', 'cmb2_sanitize_food_menu_items_field', 10, 5);


function cmb2_types_esc_food_menu_items_field($check, $meta_value, $field_args, $field_object)
{
    // if not repeatable, bail out.
    if (!is_array($meta_value) || !$field_args['repeatable']) {
        return $check;
    }
    foreach ($meta_value as $key => $val) {
        $meta_value[$key] = array_map('esc_attr', $val);
    }
    return $meta_value;
}
add_filter('cmb2_types_esc_food_menu_items', 'cmb2_types_esc_food_menu_items_field', 10, 4);
?>
