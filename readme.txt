=== Google Analytics Dashboard Stats ===
Contributors: mmattner
Donate link:
Plugin URI: http://mikemattner.com/project/
Author: Mike Mattner
Author URI: http://www.mikemattner.com
Tags: Google Analytics, dashboard widget
Requires at least: 3.3.0
Tested up to: 3.3.2
Stable tag: 1.5.3

Displays an overview of visits and pageviews on your dashboard; additionally, you can include your Analytics tracking code on your site.

== Description ==

= Introduction =
This plugin allows you to display an overview of visits and pageviews on your dashboard and, if you choose, can also show top content and top sources. This is just a simple overview, but gives you a general idea of how your site is performing using basic standard metrics. In addition, you can now include your Google Analytics code on your site if you haven't already done so.

Using it is fairly easy: simply install the plugin; setup an application specific password for your WordPress installation and Analytics account; enter the property ID of of the analytics profile you want to show as well as a title to identify it; and you should be all set.

Setting up your tracking code is as simple as copying and pasting the code and ticking a check box. The code will automatically be placed in the `head` of your page.

If you need help, visit the plugin's settings page and view the help tab.

== Features ==
* Dashboard widget showing a graph of visits and pageviews.
* Widget can also include top content and top sources.
* Add Google Analytics tracking code to your site.

== Installation ==

1. Unzip and upload the folder `/google-analytics-dashboard-stats/` to the `/wp-content/plugins/` directory of your WordPress installation.
2. Activate the plugin through the 'Plugins' menu in WordPress.
3. You'll be using your Google Analytics login information, but you'll have to setup an application specific password. (http://support.google.com/accounts/bin/answer.py?hl=en&answer=185833)
4. Visit the settings page to enter your information and choose your options.
5. Everything should be setup and your widget should be on your dashboard! 

== Frequently Asked Questions ==

= My password isn't working, what am I doing wrong? =

In order to display Analytics data on your dashboard, you'll need to provide your Google Analytics account login data—but you can't user your normal password. The Core Reporting API 2.4 requires that you setup a unique application password.

How do I do set up an application password? The process itself is actually pretty simple.

* First you'll need to setup up 2-Step verification on your Google Account.
* After that, you'll need to setup an application specific password.

Once you've completed those steps, use the password you generate as your Analytics password.

= What is my profile information? =

In order to show your data on the dashboard, you'll need to provide your Profile ID.

Where can I find my 'profile id?' Finding your Profile ID is fairly easy. When logged into your Analytics account, and viewing the reports for the site you're planning to use on the dashboard, click on the 'Admin' tab. In the 'Admin' tab under 'Profiles' you'll click on 'Profile Settings' and your Profile ID should be right under Profile Name.

What is my 'profile label?' This is just a way to identify the account on the dashboard and has no real relation to Google Analytics data. Label it whatever you like.

= What gets displayed on my dashboard? = 

The default widget displays a chart showing pageviews and visits, as well as the total numbers of the same. This plugin provides the option to display the top 5 sources and top 5 most viewed pages. This is just a simple overview, but gives you a general idea of how your site is performing using basic standard metrics.

= How do I add tracking code? =

This is a very basic way to add tracking code to your site's head tags, so I suggest using the Asynchronous tracking method. Just copy and paste your code, make sure to enable the option, and you should be set.

== Changelog ==

= 1.5.3 =
* Now sets defaults on activation.
* Updated backend.
* Added contextual help menu to help you get started.
* Added the ability to include Analytics code on your blog.
* Changed when css and javascript files are loaded in the admin.

= 1.5.2 =
* Updated how the plugin handles css and javascript files in the admin.

= 1.5.1 =
* Fixed minor error handling issues.

= 1.5 =
* First public version.

= 1.0 =
* This is the first version, and was private development only.

== Upgrade Notice ==

= 1.5.3 =