# Export Posts to CSV

Export data for all posts or a category of posts in CSV format with including author name, author user name, publication date, URL (local or remote if syndicated, identification of Feed Wordpress syndicated posts, character count, word count, and link count, and list of outward hypertext link

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
* number of links in post (count of `&lt;/a&gt;` tags)
* list of hyperlink urls (from all `&lt;a href=&gt;...&lt;\&gt;` tags)
* number of tags
* list of tags
* number of comments (if it is a locally published post, syndicated are messy)

Possible additions may include:
* Embedded media? (count use of img, object, iframe, what else?)
* names of commenters (would be just what they put in form)
* filter results by keyword?? Or is that better done on the analysis side



## Please Note
This is my first real plugin!


## Installation

1. Upload `posts2csv` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Click on the Export to CSV  link from the Tools sub menu
4. Save .csv value
5. Enjoy

## Frequently Asked Questions

### Huh?

ok


## Screenshots
 
one day 

## Changelog

### 0.1 
* Just got it working!
















