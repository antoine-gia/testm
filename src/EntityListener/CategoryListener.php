<?php

declare(strict_types=1);

namespace App\EntityListener;

use Doctrine\ORM\Event\PreFlushEventArgs;

class CategoryListener
{
    public function preFlush(PreFlushEventArgs $args)
    {
        dd($args);
    }
}
