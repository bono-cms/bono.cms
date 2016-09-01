CHANGELOG
=========

1.3
---

 * Added Theme item in administration panel. Ability to switch default themes right from administration panel without touching a configuration file
 * Fixed minor issue with Mailer service regarding SPAM. Since now it must work in all hosting providers
 * Moved debug information from footer to menu
 * Improvement in theme configuration file. Added optional protection for required modules
 * Added static about resource usage in the footer
 * Switch to environment variables
 * Added module manager to install and uninstall modules
 * Removed extra `module` key in configuration arrays
 * Ability to change home page controller right from the site configuration
 * In themes, it's now possible to include links from external resources
 * Added shared partials for themes. Most of them can be reused in various themes
 * Since now themes may have their own translation files. They must be located at `{theme}/translations/{language}.php`
 * Added CSS class for table filters
 * In administration panel added SiteMap link previewer
 * Changed background image in administration panel
 * Wrapped login form into transparent box
 * Fixed CSS issue with double-background on the login page
 * Changed the way of storing configuration data. Since now its stored in the database
 * Decreased font size of the footer in administration panel
 * Fixed display name for non-existing users in History view. Since now it displays a warning message instead of empty string
 * Fixes in .gitignore
 * Added ability to deal with shared template blocks. Added very common ones as well
 * Added brand favicon for administration panel. It no longer uses the shared one
 * Added automatic CAPTCHA protection in administration panel. It will be shown after 5 invalid login attempts
 * Added support for dynamic administration segment
 * In administration area, replaced static URLs with their corresponding generators in templates
 * No longer throwing exception when there's no `theme` section in theme's configuration
 * Forced to load theme plugins even if there's no `theme` section
 * Added `getSwitchUrl()` in language entities
 * Removed `CHANGELOG.md` from Site module
 * Added shared view variables `$locale` and `$currentUrl` for the site layout
 * Fixed issue with language switching on site
 * In administration panel, forced to switch to default language, when there's only one published language
 * Internally improved the way of changing languages on site
 * Added SiteMap generator
 * Added support for table prefix
 * Forced to always display summary in grids
 * Fixed issue with country flags when creating languages
 * Fixed issue with 404 page for administration controllers
 * Deprecated PNG icons. Since now FA-icons are used instead
 * Renamed "Menu" item to "System" at the top right corner
 * Fixed issue with responsiveness
 * Changed sorting order to DESC for users grid
 * Improved internal structure
 * Improvements in templates and layout
 * Fixed wrong caption in users grid

1.1
---

 * Fixed minor bugs.
 * Renamed the Admin to Cms. As this makes more sense.


1.0
---

 * First public version