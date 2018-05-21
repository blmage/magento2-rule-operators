# Additional rule operators for Magento 2

## Installation

Upcoming.

## Documentation

This extension adds new operators (along with their negative variants):
* starts with
* ends with
* matches regular expression

to any rule condition based on `Magento\Rule\Model\Condition\AbstractCondition`. This includes conditions from:
* catalog price rules
* catalog widget rules
* cart price rules

### Starts / ends with

This is the same as the original "contains" operator, except that the searched value must be strictly located
respectively at the start or at the end of the tested value.

### Matches regular expression

This performs a test based on a [PCRE regular expression](http://php.net/manual/en/reference.pcre.pattern.syntax.php).
You can either supply:
* just a pattern (eg: `^59\d{3}$`), which will applied with the `i` (case insensitivity) and `u` (UTF-8) modifiers.
Note that you do not have to worry about which delimiter is actually used, as they will automatically be escaped in
your pattern.
* a full regex, including (valid) delimiters and modifiers (eg: `/^59\d{3}$/D`), which will be used as-is.
This is especially useful when you want to have full control over the used
[modifiers](http://php.net/manual/en/reference.pcre.pattern.modifiers.php).

## Support / Suggestions

If you encounter a bug, or if you have a suggestion regarding a new operator, don't hesitate to
post an [issue](https://github.com/blmage/magento2-rule-operators/issues/new)!
