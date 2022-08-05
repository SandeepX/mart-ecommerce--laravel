<?php

namespace App\Modules\Application\Traits\CheckDelete;

trait CheckDelete{
    
    /**
     * Delete only when there is no reference to other models.
     *
     * @param array $relations
     * @return response
     */

    public function canDelete(String ...$relations)
    {
        
        foreach ($relations as $relation) {
            if ($this->$relation()->count()> 0) {
                $data['can'] = false;
                $data['relation'] = $relation;
                return $data;
            }
        }

        $data['can'] = true;
        $data['relation'] = '';
        return $data;
    }
}