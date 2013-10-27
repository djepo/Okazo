<?php

namespace okazo\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class okazoUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
