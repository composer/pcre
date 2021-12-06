composer/pcre
=============

PCRE wrapping library that offers type-safe `preg_*` replacements.

This library gives you a way to ensure `preg_*` functions do not fail silently, returning unexpected `null`s that may not be handled.

It also makes it easier ot work with static analysis tools like PHPStan or Psalm as it simplifies and reduces the possible return values from all the `preg_*` functions which are quite packed with edge cases.

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
if (preg_match('{fo+}', $string, $matches, PREG_OFFSET_CAPTURE)) { ... }
if (preg_match_all('{fo+}', $string, $matches)) { ... }
$newString = preg_replace('{fo+}', 'bar', $string);
$newString = preg_replace_callback('{fo+}', function ($match) { return strtoupper($match[0]); }, $string);
$newString = preg_replace_callback_array(['{fo+}' => fn ($match) => strtoupper($match[0])], $string);
$filtered = preg_grep('{[a-z]}', $elements);
$array = preg_split('{[a-z]+}', $string);
```

You can now call these on the `Preg` class:

```php
use Composer\Pcre\Preg;

if (Preg::match('{fo+}', $string, $matches)) { ... }
if (Preg::matchWithOffset('{fo+}', $string, $matches)) { ... }
if (Preg::matchAll('{fo+}', $string, $matches)) { ... }
$newString = Preg::replace('{fo+}', 'bar', $string);
$newString = Preg::replaceCallback('{fo+}', function ($match) { return strtoupper($match[0]); }, $string);
$newString = Preg::replaceCallbackArray(['{fo+}' => fn ($match) => strtoupper($match[0])], $string);
$filtered = Preg::grep('{[a-z]}', $elements);
$array = Preg::split('{[a-z]+}', $string);
```

The main difference is if anything fails to match/replace/.., it will throw a `Composer\Pcre\PcreException`
instead of returning `null` (or false in some cases), so you can now use the return values safely relying on
the fact that they can only be strings (for replace), ints (for match) or arrays (for grep/split).

Similarly, due to type safety requirements matching using PREG_OFFSET_CAPTURE is made available via
`matchWithOffset` and `matchAllWithOffset`. You cannot pass the flag to `match`/`matchAll`.

Additionally the `Preg` class provides match methods that return `bool` rather than `int`, for stricter type safety
when the number of pattern matches is not useful:

```php
use Composer\Pcre\Preg;

if (Preg::isMatch('{fo+}', $string, $matches)) // bool
if (Preg::isMatchAll('{fo+}', $string, $matches)) // bool
```

If you would prefer a slightly more verbose usage, replacing by-ref arguments by result objects, you can use the `Regex` class:

```php
use Composer\Pcre\Regex;

// this is useful when you are just interested in knowing if something matched
// as it returns a bool instead of int(1/0) for match
$bool = Regex::isMatch('{fo+}', $string);

$result = Regex::match('{fo+}', $string);
if ($result->matched) { something($result->matches); }

$result = Regex::matchWithOffset('{fo+}', $string);
if ($result->matched) { something($result->matches); }

$result = Regex::matchAll('{fo+}', $string);
if ($result->matched && $result->count > 3) { something($result->matches); }

$newString = Regex::replace('{fo+}', 'bar', $string)->result;
$newString = Regex::replaceCallback('{fo+}', function ($match) { return strtoupper($match[0]); }, $string)->result;
$newString = Regex::replaceCallbackArray(['{fo+}' => fn ($match) => strtoupper($match[0])], $string)->result;
```

Note that `preg_grep` and `preg_split` are only callable via the `Preg` class as they do not have
complex return types warranting a specific result object.

See the [MatchResult](src/MatchResult.php), [MatchWithOffsetResult](src/MatchWithOffsetResult.php), [MatchAllResult](src/MatchAllResult.php),
[MatchAllWithOffsetResult](src/MatchAllWithOffsetResult.php), and [ReplaceResult](src/ReplaceResult.php) class sources for more details.


License
-------

composer/pcre is licensed under the MIT License, see the LICENSE file for details.
