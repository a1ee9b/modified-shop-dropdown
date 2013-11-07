modified-shop-dropdown
======================

An easy to customize dropdown menu for the modified:shop

## Needs testing


# Installation
- Copy the files, according to the folder structure, to your template
- include the box wherever you want (probably index.html)

```
<div id="menu-main">
	{$box_DROPDOWN}
</div>
```

# Customization
Edit smarty/dropdown.tpl to your liking. A new UL will be created for every level.

The file menu.less holds the basic statements to build a horizontal menu.

You can exclude specific categories by adding their ID to the array source/boxes/dropdown.php:$cat_exceptions()