Shop module
===========

This module allows you to manage your not-too complicated e-commerce shop. 
It has only very common features. In next versions it will be extended with more features.

NOTE
====

If you're planning to use this module with several languages, please don't. 
While technically it would surely work, you might end up with products duplications.
Full internalization support is still under development and will be available soon (Where you can edit all translations for one product).


@TODO:

- Add closest by price
- Tracking doesn't work
- Bug with sortings on site
- Fix this: $this->storage->set(self::STORAGE_KEY, $data, 86400) in RecentProduct;