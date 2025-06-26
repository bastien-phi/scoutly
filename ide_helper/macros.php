<?php

declare(strict_types=1);

namespace Illuminate\Http
{
    /**
     * @method mixed validate(array $rules, ...$params)
     *
     * @see project://vendor/laravel/framework/src/Illuminate/Foundation/Providers/FoundationServiceProvider.php L147
     *
     * @method mixed validateWithBag(string $errorBag, array $rules, ...$params)
     *
     * @see project://vendor/laravel/framework/src/Illuminate/Foundation/Providers/FoundationServiceProvider.php L158
     *
     * @method mixed hasValidSignature($absolute = true)
     *
     * @see project://vendor/laravel/framework/src/Illuminate/Foundation/Providers/FoundationServiceProvider.php L176
     *
     * @method mixed hasValidRelativeSignature()
     *
     * @see project://vendor/laravel/framework/src/Illuminate/Foundation/Providers/FoundationServiceProvider.php L180
     *
     * @method mixed hasValidSignatureWhileIgnoring($ignoreQuery = [], $absolute = true)
     *
     * @see project://vendor/laravel/framework/src/Illuminate/Foundation/Providers/FoundationServiceProvider.php L184
     *
     * @method mixed hasValidRelativeSignatureWhileIgnoring($ignoreQuery = [])
     *
     * @see project://vendor/laravel/framework/src/Illuminate/Foundation/Providers/FoundationServiceProvider.php L188
     *
     * @method mixed inertia()
     *
     * @see project://vendor/inertiajs/inertia-laravel/src/ServiceProvider.php L76
     */
    class Request
    {
        /**
         * @param  array  $query  The GET parameters
         * @param  array  $request  The POST parameters
         * @param  array  $attributes  The request attributes (parameters parsed from the PATH_INFO, ...)
         * @param  array  $cookies  The COOKIE parameters
         * @param  array  $files  The FILES parameters
         * @param  array  $server  The SERVER parameters
         * @param  string|resource|null  $content  The raw body data
         */
        public function __construct(array $query = [], array $request = [], array $attributes = [], array $cookies = [], array $files = [], array $server = [], $content = null) {}
    }
}

namespace Illuminate\Routing
{
    /**
     * @method mixed inertia($uri, $component, $props = [])
     *
     * @see project://vendor/inertiajs/inertia-laravel/src/ServiceProvider.php L83
     */
    class Router
    {
        /**
         * Create a new Router instance.
         */
        public function __construct(\Illuminate\Contracts\Events\Dispatcher $events, ?\Illuminate\Container\Container $container = null) {}
    }
}

namespace Illuminate\Support\Facades
{
    /**
     * @method static mixed inertia($uri, $component, $props = [])
     *
     * @see project://vendor/inertiajs/inertia-laravel/src/ServiceProvider.php L83
     */
    class Route {}
}

namespace Illuminate\Testing
{
    /**
     * @method mixed assertInertia(?\Closure $callback = null)
     *
     * @see project://vendor/inertiajs/inertia-laravel/src/Testing/TestResponseMacros.php L12
     *
     * @method mixed inertiaPage()
     *
     * @see project://vendor/inertiajs/inertia-laravel/src/Testing/TestResponseMacros.php L27
     *
     * @method mixed inertiaProps(?string $propName = null)
     *
     * @see project://vendor/inertiajs/inertia-laravel/src/Testing/TestResponseMacros.php L34
     *
     * @method self assertValidContract(int $status)
     *
     * @see project://vendor/soyhuce/laravel-testing/src/TestResponse/ContractAssertions.php L18
     *
     * @method self assertData($expect)
     *
     * @see project://vendor/soyhuce/laravel-testing/src/TestResponse/DataAssertions.php L14
     *
     * @method self assertDataPath(string $key, $expect)
     *
     * @see project://vendor/soyhuce/laravel-testing/src/TestResponse/DataAssertions.php L23
     *
     * @method self assertDataPathCanonicalizing(string $key, array $expect)
     *
     * @see project://vendor/soyhuce/laravel-testing/src/TestResponse/DataAssertions.php L32
     *
     * @method self assertDataPaths(array $expectations)
     *
     * @see project://vendor/soyhuce/laravel-testing/src/TestResponse/DataAssertions.php L44
     *
     * @method self assertDataPathsCanonicalizing(array $expectations)
     *
     * @see project://vendor/soyhuce/laravel-testing/src/TestResponse/DataAssertions.php L58
     *
     * @method self assertDataMissing($item)
     *
     * @see project://vendor/soyhuce/laravel-testing/src/TestResponse/DataAssertions.php L69
     *
     * @method self assertDataPathMissing(string $path, $item)
     *
     * @see project://vendor/soyhuce/laravel-testing/src/TestResponse/DataAssertions.php L78
     *
     * @method self assertJsonPathMissing(string $path, $item)
     *
     * @see project://vendor/soyhuce/laravel-testing/src/TestResponse/JsonAssertions.php L15
     *
     * @method self assertJsonMessage(string $message)
     *
     * @see project://vendor/soyhuce/laravel-testing/src/TestResponse/JsonAssertions.php L24
     *
     * @method self assertSimplePaginated()
     *
     * @see project://vendor/soyhuce/laravel-testing/src/TestResponse/JsonAssertions.php L33
     *
     * @method self assertPaginated()
     *
     * @see project://vendor/soyhuce/laravel-testing/src/TestResponse/JsonAssertions.php L45
     *
     * @method self assertViewHasNull(string $key)
     *
     * @see project://vendor/soyhuce/laravel-testing/src/TestResponse/ViewAssertions.php L16
     */
    class TestResponse
    {
        /**
         * Create a new test response instance.
         *
         * @param  TResponse  $response
         * @param  \Illuminate\Http\Request|null  $request
         */
        public function __construct($response, $request = null) {}
    }
}
