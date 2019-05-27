# How to ..

## Use this add-on

Add, remove, modify your links in the CMS, under the "Linkage" link on the left-hand side menu.
Your links should appear when you log into the CMS (as the default start page replacing "Pages")


## Stop being the landing page...

Ok then I will!!
Remove the file `/linkage/_config/config.yml` and then flush your cache ?flush=all
That'll do it.

## Changing the HTML

See `/linkage/templates/Includes/Links.ss`

This uses SilverStripes templating language mixed with HTML