<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestMatcherInterface;

final class HomepageMatcher implements RequestMatcherInterface
{
    /**
     * Decides whether the rule(s) implemented by the strategy matches the supplied request.
     */
    public function matches(Request $request): bool {
        $routeName = $request->attributes->get('_route');
        if ($routeName === 'homepage') {
            return true;
        }
        return false;
    }
}