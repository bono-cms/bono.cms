<?php

/**
 * This file is part of the Bono CMS
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Cms\Middleware;

use Krystal\Application\Middleware\MiddlewareInterface;
use Krystal\InstanceManager\ServiceLocatorInterface;
use Krystal\Application\Route\RouteMatchInterface;

/**
 * Rejects requests that do not satisfy basic safety requirements.
 *
 * This middleware performs two checks before any controller is initialized:
 *
 * 1. Method allow-list — only GET and POST are accepted. Anything else
 *    (PUT, DELETE, PATCH, OPTIONS, HEAD, or an unknown verb) is rejected.
 * 2. CSRF verification — POST requests must carry a valid CSRF token in
 *    the request metadata. GET requests are exempt because they must be
 *    idempotent and side-effect free.
 *
 * On any failure the response is set to HTTP 400 (Bad Request), a short
 * plain-text body is written, and the pipeline halts. The body is
 * required: returning false without populating the response would send
 * an empty body to the client and produce a blank page.
 *
 * Register this middleware on controllers or routes that accept user
 * input, typically at the top of the middleware queue so that malformed
 * requests are rejected as early as possible.
 */
final class RequestValidationMiddleware implements MiddlewareInterface
{
    /**
     * Runs before the controller action.
     *
     * Validates that the request method is one of the accepted types
     * (POST or GET) and, on POST requests, that a valid CSRF token has
     * been submitted. On failure, sets the response status to 400,
     * writes a short explanatory body, and returns false to halt the
     * pipeline.
     *
     * @param Krystal\InstanceManager\ServiceLocatorInterface $sl
     * @param Krystal\Application\Route\RouteMatchInterface $route
     * @return bool True to continue, false to halt
     */
    public function process(ServiceLocatorInterface $sl, RouteMatchInterface $route): bool
    {
        $request = $sl->get('request');
        $response = $sl->get('response');

        // Only POST and GET requests are accepted
        if (!$request->isIntended()) {
            $response->setStatusCode(400);
            $response->send('Bad Request: unsupported HTTP method.');
            return false;
        }

        // Validate CSRF only on POST requests
        if ($request->isPost()) {
            $csrfProtector = $sl->get('csrfProtector');

            $token = $request->hasPost('token') ? $request->getPost('token') : $request->getMetaCsrfToken();

            if (!$csrfProtector->isValid($token)) {
                $response->setStatusCode(400);
                $response->send('Bad Request: invalid or missing CSRF token.');
                return false;
            }
        }

        return true;
    }

    /**
     * Runs after the response has been sent to the client.
     *
     * This middleware has no post-request side effects: it performs no
     * logging, no cleanup, and holds no state between process() and
     * terminate(). The method is intentionally empty to satisfy the
     * MiddlewareInterface contract and to make the no-op explicit.
     *
     * @param Krystal\InstanceManager\ServiceLocatorInterface $sl
     * @param Krystal\Application\Route\RouteMatchInterface $route
     * @return void
     */
    public function terminate(ServiceLocatorInterface $sl, RouteMatchInterface $route)
    {
    }
}