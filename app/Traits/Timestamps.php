<?php

namespace App\Traits;

trait Timestamps
{
    public function getCreatedAtColumn()
    {
        return 'created_at';
    }

    public function getUpdatedAtColumn()
    {
        return 'updated_at';
    }

    public function getDeletedAtColumn()
    {
        return 'deleted_at';
    }
}
