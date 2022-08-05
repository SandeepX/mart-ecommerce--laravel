<?php


namespace App\Modules\B2cCustomer\Helper;


use App\Modules\User\Models\User;

class UserB2CFilterHelper
{
    public static function filterPaginatedB2CUser($filterParameters, $paginateBy, $with = [])
    {
        $userB2C = User::with($with)
            ->when(isset($filterParameters['user_type']), function ($query) use ($filterParameters) {
                $query->whereHas('userType', function ($query) use ($filterParameters) {
                    $query->whereIn('slug', $filterParameters['user_type']);
                });
            })

            ->when(isset($filterParameters['email']), function ($query) use ($filterParameters) {
                $query->where('login_email', 'like', '%' . $filterParameters['email'] . '%');
            })

            ->when(isset($filterParameters['user_name']), function ($query) use ($filterParameters) {
                $query->where('name', 'like', '%' . $filterParameters['user_name'] . '%');
            });

        $paginateBy = isset($filterParameters['records_per_page']) ? $filterParameters['records_per_page'] : $paginateBy;

        $userB2C = $userB2C->latest()->paginate($paginateBy);
        return $userB2C;
    }


}

