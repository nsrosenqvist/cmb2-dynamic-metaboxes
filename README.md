CMB2 Dynamic Metaboxes
======================

Implements a switch with which you can enable or disable metaboxes.

```php
$my_metabox = new_cmb2_box(array(
    'id'            => 'my_metabox',
    'dynamic'       => true,
));
```

 You can then check the state of a metabox with the included helper function:

```php
if (get_metabox_state($post, 'my_metabox') {
    // Do this
}
```
