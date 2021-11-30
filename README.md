composer/pcre
=============

PCRE wrapping library that offers type-safe preg_* replacements.

If you are using a modern PHP version you are probably better off using [spatie/regex](https://github.com/spatie/regex) instead as it offers more functionality.

This library is a minimalistic way to ensure preg_* functions do not fail silently, returning unexpected `null`s.

[![Continuous Integration](https://github.com/composer/pcre/workflows/Continuous%20Integration/badge.svg?branch=main)](https://github.com/composer/pcre/actions)


Installation
------------

Install the latest version with:

```bash
$ composer require composer/pcre
```


Requirements
------------

* PHP 5.3.2 is required but using the latest version of PHP is highly recommended.


Basic usage
-----------

Instead of:

```php
if (preg_match('{fo+}', $string, $matches)) { ... }
if (preg_match_all('{fo+}', $string, $matches)) { ... }
$newString = preg_replace('{fo+}', 'bar', $string);
$newString = preg_replace_callback('{fo+}', function ($match) { return strtoupper($match[0]); }, $string);
```

You can now do:

```php
use Composer\Pcre\Regex;

$result = Regex::match('{fo+}', $string);
if ($result->matched) { something($result->matches); }

$result = Regex::matchAll('{fo+}', $string);
if ($result->matched && $result->count > 3) { something($result->matches); }

$newString = Regex::replace('{fo+}', 'bar', $string)->result;
$newString = Regex::replaceCallback('{fo+}', function ($match) { return strtoupper($match[0]); }, $string)->result;
```

See the *Result classes for more.

License
-------

composer/pcre is licensed under the MIT License, see the LICENSE file for details.
