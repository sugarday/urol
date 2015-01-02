=== S2Member Control ===
Contributors: dkukral, jboydston, Droyal
Donate link: http://joeboydston.com/
Tags: s2member, referrer, geolocation, social
Requires at least: 3.0
Tested up to: 3.9.1
Stable Tag: 2.4

Plugin for WordPress to control when the S2 Member plugin is loaded / unloaded

== Description ==

Plugin for WordPress allows admins to unload / load S2 based on different criteria (inbound referrer, zip code, city, etc)

== Installation ==

Copy "s2member-control" folder to your WordPress Plugins Directory. Activate plugin via WordPress settings.

More info on Must Use Plugins here: 
http://codex.wordpress.org/Must_Use_Plugins

== Other Notes ==

Requires s2member plugin
Requires PHP GeoIP module

Tested and working with S2Member Control V120703 + S2Member Pro v120703
== Screenshots ==

None.

== Changelog ==
= 2.4 = 
Verified compat with WP 3.9.1

= 2.3 = 
Added cookie option to allow access if a cookie is set

= 2.2 = 
Added mobile browser option

= 2.1 =
Moved the options to wp_object_cache to improve performance
Added a safe_unserialize function 

= 2.0.2 =
Added checks if user is logged in or if is_admin to make we don't unload for logged in users.

= 2.0.1 =
Cleaned up some debugging code I accidentally left in

= 2.0 = 
Rewrite of logic to remove S2 plugins from active plugins rather than try to unload / unhook everything.  

** Do not use versions prior to this as they were buggy ** 


= 0.5 = 
Removed buggy domain / tld code
Added code to make free categories

= 0.0 = 
* Initial version

== Frequently Asked Questions ==

None.

== Upgrade Notice ==

None.
