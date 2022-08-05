<?php


namespace App\Modules\QuizGame\Helpers;

use App\Modules\QuizGame\Models\QuizParticipator;

class QuizGameParticipatorHelper
{
    public static function getAllParticipatorByFilter($filterParameters)
    {
        $allParticipatorByfilter = QuizParticipator::when(isset($filterParameters['participator_type']), function ($query) use ($filterParameters) {
                $query->where('participator_type', 'like', '%' . $filterParameters['participator_type'] . '%');
            })
            ->when(isset($filterParameters['recharge_phone_no']), function ($query) use ($filterParameters) {
                $query->where('recharge_phone_no', $filterParameters['recharge_phone_no']);

            })
            ->when(isset($filterParameters['status']), function ($query) use ($filterParameters) {
                $query->where('status', $filterParameters['status']);
            })
            ->when(isset($filterParameters['store_name']), function ($query) use ($filterParameters) {
                $query->where('store_name', 'like', '%' . $filterParameters['store_name'] . '%');
            })
            ->when(isset($filterParameters['participation_from']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '>=', date('Y-m-d', strtotime($filterParameters['participation_from'])));

            })
            ->when(isset($filterParameters['participation_to']), function ($query) use ($filterParameters) {
                $query->whereDate('created_at', '<=', date('Y-m-d', strtotime($filterParameters['participation_to'])));

            })
            ->orderBy('created_at', 'DESC')
            ->paginate(QuizParticipator::RECORDS_PER_PAGE);
        return $allParticipatorByfilter;

    }
}
