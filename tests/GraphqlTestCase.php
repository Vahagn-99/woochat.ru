<?php

namespace Tests;

use Nuwave\Lighthouse\Testing\MakesGraphQLRequests;
use Nuwave\Lighthouse\Testing\RefreshesSchemaCache;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

class GraphqlTestCase extends BaseTestCase
{
    use WithAuth;
    use MakesGraphQLRequests;
    use RefreshesSchemaCache;

    protected function setUpTraits(): void
    {
        $uses = parent::setUpTraits();
        if (isset($uses[WithAuth::class]) && method_exists($this, 'authenticateUser')) {
            $this->authenticateUser();
        }
    }
}