<?php

namespace App\Repositories;

use App\Models\Timetable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class aScServerRepository
{
    /**
     * Fetch available timetables from asc server. This function only puts data into models and returns it! It DOESN'T save it to database!
     *
     * @return Collection<int, Timetable>
     */
    public function fetchAvailableTimetables(): Collection
    {
        $baseUrl = env("ASC_SERVER_URL");

        $body = [
            '__args' => [null, date("Y")],
            "__gsh" => "00000000"
        ];

        $response = Http::withBody(json_encode($body), 'application/json')
            ->post($baseUrl . "/timetable/server/ttviewer.js?__func=getTTViewerData", $body);

        $defaultTimetableId = $response->json('r.regular.default_num');
        if ($defaultTimetableId === "") $defaultTimetableId = null;

        return collect($response->json('r.regular.timetables'))->map(function ($rawTimetableData) use ($defaultTimetableId) {
            $timetable = new Timetable();
            $timetable->date = $rawTimetableData['datefrom'];
            $timetable->name = $rawTimetableData['text'];
            $timetable->year = $rawTimetableData['year'];
            $timetable->id = $rawTimetableData['tt_num'];
            $timetable->is_default = $timetable->id === $defaultTimetableId;
            return $timetable;
        });
    }

    public function fetchTimetableData(int $timetableId)
    {
        $baseUrl = env("ASC_SERVER_URL");

        $body = [
            '__args' => [null, strval($timetableId)],
            "__gsh" => "00000000"
        ];

        $response = Http::withBody(json_encode($body), 'application/json')
            ->post($baseUrl . "/timetable/server/regulartt.js?__func=regularttGetData", $body);

        return $response->json('r.dbiAccessorRes.tables');
    }
}
