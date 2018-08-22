<?php

use App\Dorcas\Support\Gravatar;

/**
 * Generates a URL using the provided base.
 *
 * @param string     $base
 * @param string     $path
 * @param array|null $parameters
 * @param bool       $secure
 *
 * @return string
 */
function custom_url(string $base, string $path, array $parameters = null, bool $secure = true): string
{
    $uri = new \GuzzleHttp\Psr7\Uri($base);
    # create the URI
    if (!empty($path) && !(is_string($path) || is_array($path))) {
        throw new InvalidArgumentException('path should either be a string or an array');
    }
    if (!empty($path)) {
        $path = is_string($path) ? $path : implode('/', $path);
        $uri = $uri->withPath(starts_with($path, '/') ? $path : '/'.$path);
    }
    if (!empty($parameters)) {
        $uri = $uri->withQuery(http_build_query($parameters));
    }
    if ($secure) {
        $uri = $uri->withScheme('https');
    }
    return (string) $uri;
}

/**
 * Generates an asset URL for static content, taking the CDN into consideration
 *
 * @param string $path
 * @param bool   $secure
 *
 * @return string
 */
function cdn(string $path, bool $secure = true)
{
    $base = config('app.cdn_url', config('app.url'));
    # we get the base URL first
    return custom_url($base, $path, null, $secure);
}

/**
 * Returns an absolute URL for the path, using the Dorcas domain as the base.
 *
 * @param string     $path
 * @param array|null $parameters
 * @param bool       $secure
 *
 * @return string
 */
function dorcas_url(string $path, array $parameters = null, bool $secure = true): string
{
    return custom_url('https://dorcas.ng', $path, $parameters, $secure);
}

/**
 * A simpler way to generate the gravatar
 *
 * @param string $email
 * @param bool   $secure
 * @param int    $width
 * @param string $default
 * @param string $rating
 *
 * @return string
 */
function gravatar(
    string $email,
    bool $secure = true,
    int $width = 400,
    string $default = Gravatar::DEFAULT_IMG_RETRO,
    string $rating = Gravatar::RATED_G
): string {
    return Gravatar::getGravatar($email, $secure, $width, $default, $rating);
}

/**
 * Calculates what the page number should be based on the supplied offset, and limit values.
 *
 * @param int $offset
 * @param int $limit
 *
 * @return int
 */
function get_page_number(int $offset, int $limit): int
{
    return (int) (($offset + $limit) / $limit);
}

/**
 * Tries to get the reserved subdomain for the currently authenticated account.
 *
 * @param \Hostville\Dorcas\Sdk|null $sdk
 *
 * @return null|string
 */
function get_dorcas_subdomain(\Hostville\Dorcas\Sdk $sdk = null)
{
    $subdomains = (new \App\Http\Controllers\Controller())->getSubDomains($sdk);
    # get the sub-domains for the authenticated account
    if (empty($subdomains) || $subdomains->count() === 0) {
        # none found
        return null;
    }
    $subdomain = $subdomains->first();
    $scheme = app()->environment() === 'production' ? 'https' : 'http';
    return $scheme . '://' . $subdomain->prefix . '.' . $subdomain->domain['data']['domain'];
}

/**
 * Converts an image at the specified path/url to a base64-encoded version.
 *
 * @param string $url   URL or path of the image file.
 *
 * @return null|string
 */
function image_to_base64(string $url)
{
    $specialFiles = ['svg' => 'svg+xml'];
    # an array of special extension to type list
    $type = pathinfo($url, PATHINFO_EXTENSION);
    $type = !array_key_exists($type, $specialFiles) ? $type : $specialFiles[$type];
    # update the type
    $encoded = 'data:image/'.$type.';base64,';
    if (($rawContent = file_get_contents($url)) === false) {
        return null;
    }
    return $encoded . base64_encode($rawContent);
}
