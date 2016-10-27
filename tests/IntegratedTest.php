<?php
namespace Tests;

use Illuminate\Http\Request;
use Laravel\Lumen\Application;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class IntegratedTest extends \PHPUnit_Framework_TestCase
{
    public function testRouteBindingAndItsServiceProviderWorksAsExpectedWithLumen()
    {
        // Create new Lumen application
        $app = new Application;

        // Register out RouteBinding service provider
        $app->register('mmghv\LumenRouteBinding\RouteBindingServiceProvider');

        // Get the binder instance
        $binder = $app->make('bindingResolver');

        // Register a simple binding
        $binder->bind('wildcard', function ($val) {
            return "{$val} Resolved";
        });

        // Register a route with a wildcard
        $app->get('/{wildcard}', function ($wildcard) {
            return $wildcard;
        });

        // Dispatch the request
        $response = $app->handle(Request::create('/myWildcard', 'GET'));

        // Assert the binding is resolved
        $this->assertSame('myWildcard Resolved', $response, '-> Response should be the wildcard value after been resolved!');
    }
}
