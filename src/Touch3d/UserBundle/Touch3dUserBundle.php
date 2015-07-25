<?php

namespace Touch3d\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class Touch3dUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
