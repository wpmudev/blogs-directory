# Blog Directory

**INACTIVE NOTICE: This plugin is unsupported by WPMUDEV, we've published it here for those technical types who might want to fork and maintain it for their needs.**

## Translations

Translation files can be found at https://github.com/wpmudev/translations

## Blog Directory creates a simple and customizable list of all the sites on your network.

This easy to use plugin completely automates site list management so you can set it, and forget it. Every added site, tagline change and deleted blog instantly updates for an always current directory.

### Adapts to fit your theme's style

By default, Blog Directory grabs style elements from your design to integrate perfectly with every theme. Or add a splash of color for a little extra flare using the integrated color customization tool. Users can quickly discover and navigate blogs on your network with the built-in directory search. Each setting, from display order to listings per page, can be adjusted with a click. 

![Blog Directory adapts to your theme](http://premium.wpmudev.org/wp-content/uploads/2009/09/sites-list.jpg)


 Blog Directory adapts to your theme

### Do more with WPMU DEV

Pair Blog Directory with other premium plugins and get more. Activate [Pro Sites](http://premium.wpmudev.org/project/pro-sites/) and limit your site list to only paid blogs or install [Avatars](http://premium.wpmudev.org/project/avatars/) and display a blog avatar for each site in your directory. 

![Seamless Pro Sites integration](http://premium.wpmudev.org/wp-content/uploads/2009/09/pro-sites-integration.jpg)


 Seamless Pro Sites integration

 Save time with Blog Directory – instant setup and a streamlined site list you don't have to micromanage.

## Usage

Start by reading [Installing plugins](https://premium.wpmudev.org/wpmu-manual/installing-regular-plugins-on-wpmu/) section in our comprehensive [WordPress and WordPress Multisite Manual](https://premium.wpmudev.org/wpmu-manual/) if you are new to WordPress Multisite.

### To install:

1.  Download the plugin file
2.  Unzip the file into a folder on your hard drive
3.  Upload **/blogs-directory/** folder to **/wp-content/plugins/** folder on your site
4.  Visit **Network Admin -> Plugins** and **Network Activate** it there.

_Note: If you have an older version of the plugin installed in /mu-plugins/ please delete it._

*   **That's it! No configuration necessary!**

### To use:

It should auto create a page called "Sites" on your main site where anyone can search to locate a site. On the off chance the page isn't auto created all you need to do is create a page with the slug "blogs" without the quotes. 

![blogs-directory-sites-page](https://premium.wpmudev.org/wp-content/uploads/2009/09/blogs-directory-sites-page.png)

 **Changing how sites are displayed in your Site Directory is as simple as pie.** You will see the following screen under **Network Admin** > **Settings** > **Site Directory** 

![1\. Select your preferred sort option. 2\. Select the number of sites you want to display per page. 3\. Select whether to hide unpaid sites if you are using the Pro Sites Plugin, and if you want to hide sites that have been set to Private. 4\. Select the Title you want for your Directory page. 5\. Chose to toggle on or off site descriptions. 6\. Adjust the background and border colors.](https://premium.wpmudev.org/wp-content/uploads/2009/09/blogs-directory-settings.png)

 1\. Select your preferred sort option.  
2\. Select the number of sites you want to display per page.  
3\. Select whether to hide unpaid sites if you are using the Pro Sites Plugin, and if you want to hide sites that have set to Private.  
4\. Select the Title you want for your Directory page.  
5\. Choose to toggle on or off site descriptions.  
6\. Adjust the background and border colors.

 **Searching for a site is a real snap too.** 1\. Go to your new Site Directory, and click on the site you want to check out. 2\. Or use the built-in search feature that enables your users to find exactly what they are looking for on your network. They can even search for individual words in the site names or descriptions. Say, for example, you just know there's a cool site in the network with a bunch of drawing tutorials, but can't remember the name. No problem, simply enter a likely word in the search form, and all sites containing that word will be returned in the results. How cool is that? 

![blogs-directory-search](https://premium.wpmudev.org/wp-content/uploads/2009/09/blogs-directory-search.png)

### Customization

You can change the page slug used by Blogs Directory by opening blogs-directory.php and editing the slug on line 37, which by default reads as follows: define('BLOGS_DIRECTORY_SLUG', 'your-slug');
