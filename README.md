Read / Read <sup>[reed][red]</sup>
============


**A WordPress plugin that adds generates the estimated time to read a post and intelligently tracks when as user had read the post.**

![Screenshot](http://edwardmcintyre.com/pub/github/wp-read-read.jpg)

## Features
* Calculates the time it will take to read a post
* Outputs the estimated time to read in the post content. 
* Counts post number of times the post has been read by using JavaScript to track the users scroll position within the post container. 
* Counts single post views
* Generates read ratio statistics based on the logged post views & read count
* Displays statistics inside the WordPress admin post table.

## Installation
1. Copy the `wp-read-read` directory into your `wp-content/plugins` directory
2. Navigate to the *Plugins* dashboard page
3. Locate the menu item that reads `Read / Read`
4. Click on *Activate*

## Styling
The estimated time to read can by styled using the CSS class `read_time`


## Output Read Time
You can also output the read time for any post by calling the `get_read_time` function.
```php
if( class_exists( 'Read_Read' ) ) {
    echo '<strong>' . Read_Read::get_read_time( get_the_ID() ) . '</strong>';
}
```

## Credits
* [Edward McIntyre](https://github.com/twittem/) plugin Author
* [Tom McFarlin](http://tommcfarlin.com/) plugin structure is based on Tom's [WordPress-Plugin-Boilerplate 2.0](https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate)
* [Caleb Troughton](https://github.com/imakewebthings) plugin makes use of [jquery-waypoints](https://github.com/imakewebthings/jquery-waypoints)

## Roadmap Items
* Localization
* Support for custom post types & pages
* Activation & Deactivation functions
* Dashboard Widgets
* Post Leadboard Widgets

## Changlog
**1.0**
* Initial Release


[![Bitdeli Badge](https://d2weczhvl823v0.cloudfront.net/twittem/wp-read-read/trend.png)](https://bitdeli.com/free "Bitdeli Badge")

