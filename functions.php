/*
* This code is the CMB2 box of the 'food_menu_items' custom field.
* Add it in the wordpress file 'functions.php'
*/


/**
* Importing the file with the 'food_menu_items' field definitions
*/
include_once('cmb2-field-group-multiple-input.php');

function cmb2_fields_food_menu_items()
{
    $cmb = new_cmb2_box([
        'id' => 'home_box',
        'title' => 'Menu - CMB2 Group And Repeater With Several Values In a Field :D',
        'object_types' => ['page'],
        'show_on' => [
            'key' => 'page-template',
            'value' => 'page-home.php', // page-home is the page where the field is displayed in the front end
        ],
    ]);
    /* Defining the group field */
    $group_field_id = $cmb->add_field(array(
        'id'          => 'food_menu_items_group',
        'type'        => 'group',
        'description' => __('Add sets of menu items below.', 'food_menu_items_cmb2'),
        'options'     => array(
            'group_title'   => __('Menu items group {#}', 'cmb'),
            'add_button'    => __('Add new menu items group ', 'food_menu_items_cmb2'),
            'remove_button' => __('Remove menu items group', 'food_menu_items_cmb2'),
            'sortable'      => true, // beta
        ),
    ));
    /* Adding the name field to the group field */
    $cmb->add_group_field($group_field_id, [
        'name' => 'Group name:',
        'id' => 'food_menu_items_name',
        'type' => 'text',
    ]);
    /* Adding the repeatable 'food_menu_items' field to the group field */
    $cmb->add_group_field($group_field_id, array(
        'name'       => __('Items:', 'food_menu_itemss_cmb2'),
        'id'         => 'food_menu_items_item',
        'type'       => 'food_menu_items',
        'repeatable' => true,
        'options'    => array(
            'title'   => __('Item {#}:', 'cmb'),
            'add_row_text'  => __('Add item', 'food_menu_items_cmb2'),
            'remove_button' => __('Remove item', 'food_menu_items_cmb2'),
            //'sortable'      => true, // beta
        ),
    ));
}

add_action('cmb2_admin_init', 'cmb2_fields_food_menu_items');

/**
* Additional functions to get the value of the field in the front-end
*/
function get_field_cmb2($key, $page_id = 0)
{
    $id = $page_id !== 0 ? $page_id : get_the_ID();
    return get_post_meta($id, $key, true);
}

function the_field_cmb2($key, $page_id = 0)
{
    echo get_field_cmb2($key, $page_id);
}
