# Export Posts to CSV
** by [cog.dog](https://cog.dog)

-----
*If this kind of stuff has any value to you, please consider supporting me so I can do more!*

[![Support me on Patreon](http://cogdog.github.io/images/badge-patreon.png)](https://patreon.com/cogdog) [![Support me on via PayPal](http://cogdog.github.io/images/badge-paypal.png)](https://paypal.me/cogdog)

----- 


Export data for all posts or a category of posts in CSV format with including author name, author user name, publication date, URL (local or remote if syndicated, identification of Feed Wordpress syndicated posts, character count, word count, and link count, and list of outward hypertext link.

![](export-csv.jpg "Screenshot of options for plugin")

## Description
Get blog data in a data format for use elsewhere. This plugin was written originally for use on sites that aggregate external posts with the [Feed Wordpress plugin](https://wordpress.org/plugins/feedwordpress/), but will equally return data on locally published posts (or mixes thereof)

This plugin adds a *Post CSV Export* submenu item to the Tools menu. The tool generates an export of blog data in CSV format, for either the entire site, or within a selected category. 

The data so far being exported for each post includes:

* post ID
* source (either 'local' or 'syndicated')
* post title
* publication date and time
* author name (first and last name from profile)
* author username
* blogname (host blog or remote if syndicated post)
* post character count (string character count after HTML stripped out)
* post word count (after HTML stripped out)
* number of links in post (count of `</a>` tags)
* list of hyperlink urls (from all `<a href="http://*****.****.***">...</a>` tags)
* number of tags
* list of tags
* number of comments (if it is a locally published post, syndicated are messy)

There is now a form field to list any post meta (custom field) names, each which will appear as a column in output


![](post-meta-items.jpg "field for post meta")

## Possible additions may include

* restrict by date range (DONE)
* Embedded media? (count use of img, object, iframe, what else?)
* names of commenters (would be just what they put in form)
* filter results by keyword?? Or is that better done on the analysis side

## Installation

1. Upload `posts2csv` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Click on the Export to CSV  link from the Tools sub menu
4. Save .csv value
5. Enjoy your data

## Frequently Asked Questions

### Huh?

ok



## Changes

### 0.31
* Via request by Aaron Davis, aded option to enter names of post meta keys to pull as column data


### 0.21
* Fixed issues with date limits.


### 0.2
* Adding date fields to use before, after, or bracket by date


### 0.1 
* Just got it working!





