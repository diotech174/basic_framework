<?php

namespace BlastCloud\Guzzler;

use BlastCloud\Chassis\Helpers\File;

/**
 * Class Expectation
 * @package Guzzler
 * @method $this endpoint(string $uri, string $method)
 * @method $this get(string $uri)
 * @method $this post(string $uri)
 * @method $this put(string $uri)
 * @method $this delete(string $uri)
 * @method $this patch(string $uri)
 * @method $this options(string $uri)
 * @method $this synchronous()
 * @method $this asynchronous()
 * @method $this withHeader(string $key, $value)
 * @method $this withHeaders(array $values)
 * @method $this withOption(string $key, $value)
 * @method $this withOptions(array $values)
 * @method $this withQuery(array $values, bool $exclusive = false)
 * @method $this withoutQuery()
 * @method $this withQueryKey(string $key)
 * @method $this withQueryKeys(array $keys)
 * @method $this withRpc(string $endpoint, string $method, array $params, ?string $id)
 * @method $this withJson(array $values, bool $exclusive = false)
 * @method $this withForm(array $form, bool $exclusive = false)
 * @method $this withFormField(string $key, $value)
 * @method $this withBody($body, bool $exclusive = false)
 * @method $this withEndpoint(string $uri, string $method)
 * @method $this withFile(string $field, File $file)
 * @method $this withFiles(array $files, bool $exclusive = false)
 * @method $this withCallback(\Closure $callback, string $message = null)
 */
class Expectation extends \BlastCloud\Chassis\Expectation
{

}
