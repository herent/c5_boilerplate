concrete5 Boilerplate
=====================

A basic starting point for [concrete5](http://www.concrete5.org/r/-/654) packages, 
with samples of code you might use. This is not intended for production, it does 
not do anything useful by itself.

But if you want to learn about how packages work, and save time when you develop 
your own sites, this is something that can help you out a lot. 

Subjects Covered 
----------------
### Package Controller

1. Screen to customize options or give more info before installation
1. Showing a success / more details screen after installation
1. How creating private functions for different tasks makes the controller easier to understand
1. Using on_start()
	* Add your own javascript / css on all pages
	* Hooking into a custom page type's on_page_add event
1. Installing Page Attributes
	* Samples of each attribute type
	* Assigning to a set
1. Installing a Block
1. Installing a Theme
1. Installing Single Pages
1. Updating the database table for a custom attribute that you installed with the package when upgrading (not run by default)
1. Hooking into the pre-upgrade function to reject if a package isn't installed
1. Creating a custom user group
1. Adding a new page type with all options explained
1. Adding a new page from that page type
1. Assigning view / edit permisisons to admins, installing user, and the custom group
	* All top-level options for editing / viewing
	* How to configure which sub-pages are allowed, and how permissions for sub-pages are inherited

### Dashboard Ajax 

The single page at /dashboard/boilerplate/output_stuff is set up with a basic example
of how to use the page controller, a tools file, and an element to update the content.
It's kept as basic as possible to keep it easy to understand. Just a single button
that sends a get request with no parameters to the tools file, which then calls a 
method on the page controller to get a new array, which is then passed in to a
package element to format the data. Then part of the page is updated with that new 
HTML.

For a more a detailed example of this, which uses the built in list models to allow
for sorting and paginating a database driven table, please check out my 
[https://github.com/herent/Custom-Objects-in-concrete5-Demo](Custom Objects Demo)

### Theme Page Type Inheritance

The 'boilerplate' page type will show in view.php using the file /page_types/boilerplate.php
if using one of the built in themes. The included theme has a proper template, so if used
it outputs slightly differently.

### Using an External Form

This is a slightly tricky one. Because the external form block does not actually 
look in package directories to see if there are forms there, you will have to copy
the file from the package blocks directory to your outer blocks directory.

It shows:

1. Validation using built in helpers
1. Loading a plain text mail file from the package 
1. Sending an HTML email
1. Some options for the mail helper

### Custom Block

Has a custom "Callout" block that has controller side validation, rich text
processing to keep links correct, etc.

1. Image Picker
1. Text Field
1. WYSIWYG
1. Link Picker
	* Choose if a link is shown or not for use in the template without requiring it to be in the text content
	* External link or a page picker for an internal link
	* Specify text for the link, though the template may not use it.

It uses the validate() function on the page controller to provide more control of
the verification and error message display.

### Block Templates

There are templates for the autonav and page_list blocks in the package, showing
how you can keep your templates organized within your package. 

*PLEASE NOTE :* the templates here are not that detailed. They will be upgraded
in future versions of this package. If people would like to send pull requests 
with templates they find useful for different blocks, they would be welcomed

### Database Formatting Options

The db.xml file shows sample options for most of the fields you can create using
the [http://phplens.com/lens/adodb/docs-datadict.htm](ADODB XML) format

### Page Type Events

The boilerplate page type has an on_page_add() function that puts extra data into
the table installed with the package

### A basic theme

The theme is _very_ skeletal, almost nothing included at all. It is intended to just
be a starting point, but has the basic structure you need to develop your own themes.
It's assumed that most people who would want to use a package like this would end up 
replacing this with their own starting point that's relevant to the way they work, 
their favorite grid system, etc.

How to Use This Package
-----------------------

The intended for people that are developing custom sites more than people that 
are developing for the marketplace. So instead of locally adding a page type,
then adding some attributes via the gui, then having to recreate that whole
process on the live server, you have everything in one package. 

When trying to find your custom templates for blocks, themes, etc, it's also much
nicer to do everything in a package. If you need a new attribute or block, you can
just put that into your upgrade script.

A find and replace for boilerplate within the package directory would be needed 
when adjusting this to work for your own site. Some folders and file names will 
need to be changed as well. So it's not just point and click to make your own stuff,
but if you are a somewhat experienced developer it should not be a big deal to 
make those adjustments.

Credits
=======
This package has been created by Jeremy Werst

jeremy.werst@gmail.com

www.werstnet.com

https://github.com/herent/c5_boilerplate

If you like this package and would like to support further projects like these,
or if you just want to buy me a beer, please send a donation through paypal to
jeremy.werst@gmail.com

Thanks!
