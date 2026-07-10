<?php

/**
 * Kunci uji reCAPTCHA Google (berlaku di 127.0.0.1 karena domain IP tidak bisa didaftar di Google).
 */
const RECAPTCHA_TEST_SITE_KEY = '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI';
const RECAPTCHA_TEST_SECRET_KEY = '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe';

/**
 * Site key reCAPTCHA untuk tampilan widget (otomatis pakai kunci uji bila akses lewat 127.0.0.1 atau localhost).
 */
function recaptcha_site_key(): string
{
    $host = request()->getHost();
    if (in_array($host, ['127.0.0.1', 'localhost'], true)) {
        return RECAPTCHA_TEST_SITE_KEY;
    }
    return (string) config('services.recaptcha.site_key');
}

/**
 * Secret key reCAPTCHA untuk verifikasi di backend (otomatis pakai kunci uji bila akses lewat 127.0.0.1).
 */
function recaptcha_secret_key(): string
{
    $host = request()->getHost();
    if (in_array($host, ['127.0.0.1', 'localhost'], true)) {
        return RECAPTCHA_TEST_SECRET_KEY;
    }
    return (string) config('services.recaptcha.secret_key');
}
