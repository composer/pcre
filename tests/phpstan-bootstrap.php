<?php

// PHPStan 2.2.13+ fails to resolve \Stringable on PHP 7.4
if (PHP_VERSION_ID < 80000 && !interface_exists('Stringable', false)) {
    interface Stringable
    {
    }
}
