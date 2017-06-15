# TablePress Extension: Column filters

Filters a given data table by searching comma-separated lists of terms in a given column.

## Dependencies

This plugin is an extension to [TablePress](https://wordpress.org/plugins/tablepress/).

TablePress needs to be installed and enabled. 

## Installation

Since this plugin is not available for automatic installation, please follow these [Manual Plugin Installation](https://codex.wordpress.org/Managing_Plugins#Manual_Plugin_Installation) steps.

## Modus Operandi

Unlike the default [search method](https://datatables.net/reference/api/search()), 
which does a full text search across all columns that are configured to be searchable, this plugin
adds keyword-matching search capabilities to one designated columns.  
All values in that column are treated as comma-separated list of keywords.  
Only rows matching _all_ given keywords are included in the search results.  
This allows for _filtering down_ of table data. 

## Usage

### 1. Add a keyword column to your table
 
Designate a column in your table to be the "keyword" column. 

Add your list of keywords, separated by commas, to this columns' cells.
 
Please note that case does not matter, nor does whitespace around these keywords.

### 2. Add your table to a page
  
Use the `[table /]` shortcode. Please check the [TablePress docs](https://tablepress.org/documentation/) for details.

### 3. Add column filters to your table

On the same page as your data table, add the `[tablepress-ext-column-filters][/tablepress-ext-column-filters]` shortcode.
 
The short code takes two mandatory attributes:

- `table-id` ... the ID of your data table.
- `column-id` ... the ID of the keyword column in your data table. Please note that column IDs are [zero-indexed](https://en.wikipedia.org/wiki/Zero-based_numbering), so the first column has an ID of `0`, the second column's index is `1` and so on.

The short code's body should contain HTML elements that interact as filters with the targeted data table.  
Any clickable elements will do.  
The only hard requirement is that the each element provides a `data-filter` attribute, 
its value being one of the keywords from your keyword column.  
Clicking on one of these elements will filter out any rows in your table that do not match the specified keyword.  

You may also provide a filter-reset element by adding any clickable element with a `data-filter` attribute with a blank value.  
Clicking on this element will remove any previously applied filters and reset the table to its original/unfiltered state.

### Examples

For the purpose of this example, we're assuming that you have a data table (ID = 1), 
and its third column is the designated keyword column. It contains any combination of the terms `foo`, `bar` and `baz`.
It was placed on a page using the `[table /]` shortcode.

```
[table id=1 /]
```

Now add a shortcode `[tablepress-ext-column-filters]` shortcode to the same page, targeting the table (table-id=1) and its keyword column (column-id=2).

```
[tablepress-ext-column-filters table-id=1 column-id=2]
[/tablepress-ext-column-filters]

[table id=1 /]
```

Then, provide filter elements to that shortcode for the keywords listed above, as well as a reset element. 

```
[tablepress-ext-column-filters table-id=1 column-id=2]
  <button data-filter="foo">Foo</button>
  <button data-filter="bar">Bar</button>
  <button data-filter="baz">Baz</button>
  <button data-filter="">Reset</button>
[/tablepress-ext-column-filters]

[table id=1 /]
```

## Styling

The shortcode renders into a `<div>`, set with the `tablepress-ext-column-filters` class.
Use this class selector to target the element or any contained elements with CSS.

```css
.tablepress-ext-column-filters {
  /* ... */
}

.tablepress-ext-column-filters button {
  /* ... */
}
```

You can get more specific my targeting the `data-table` attribute in your CSS selector.
For example, if your filters apply to a data table with id 6, 
you may provide a narrower scope with your CSS selectors like this:

```css
.tablepress-ext-column-filters[data-table="6"] {
  /* ... */
}
```

As stated earlier, filter elements are *must* have a `data-filter` attribute, 
which can be leveraged for styling purposes as well:

```css
.tablepress-ext-column-filters [data-filter] {
   /* ... */
}
```

Active filters elements will have the `active` class applied to them, and can be targeted with CSS.

```css
.tablepress-ext-column-filters [data-filter].active {
   /* ... */
}
```

## Other considerations

If you want to hide the search box and/or the keywords column, 
please check out the [TablePress Extension: Shortcode Attributes Plus](https://github.com/ucsf-ckm/tablepress-ext-shortcode-attrs-plus) 
companion plugin. 

## Copyright and License

Copyright (c) 2017 The Regents of the University of California

This is Open Source Software, published under the MIT license.


