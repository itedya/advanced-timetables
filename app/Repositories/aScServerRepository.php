<?php

namespace App\Repositories;

use App\Models\Classroom;
use App\Models\DayDefinition;
use App\Models\DayIdentifier;
use App\Models\Group;
use App\Models\Period;
use App\Models\SchoolClass;
use App\Models\Subject;
use App\Models\Teacher;
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

        $periods = collect(collect($response->json('r.dbiAccessorRes.tables'))
            ->first(fn($table) => $table['id'] === "periods")['data_rows'])
            ->map(function ($row) {
                $period = new Period();
                $period->id = $row['id'];
                $period->name = $row['name'];
                $period->short = $row['short'];
                $period->start_time = $row['starttime'];
                $period->end_time = $row['endtime'];
                return $period;
            });

        $dayIdentifiers = collect(collect($response->json('r.dbiAccessorRes.tables'))
            ->first(fn($table) => $table['id'] === "daysdefs")['data_rows'])
            ->map(fn($row) => $row['vals'])
            ->flatten(1)
            ->unique()
            ->map(fn($identifier) => new DayIdentifier(['identifier' => $identifier]));

        $daysDefinitions = collect(collect($response->json('r.dbiAccessorRes.tables'))
            ->first(fn($table) => $table['id'] === "daysdefs")['data_rows'])
            ->map(function ($row) {
                $dayDefinition = new DayDefinition();
                $dayDefinition->id = substr($row['id'], 1);
                $dayDefinition->name = $row['name'];
                $dayDefinition->short = $row['short'];
                $dayDefinition->setRelation('dayIdentifiers', $row['vals']);
                return $dayDefinition;
            });

        $classrooms = collect(collect($response->json('r.dbiAccessorRes.tables'))
            ->first(fn($table) => $table['id'] === "classrooms")['data_rows'])
            ->map(function ($row) {
                $classroom = new Classroom();
                $classroom->id = substr($row['id'], 1);
                $classroom->name = $row['name'];
                $classroom->short = $row['short'];
                return $classroom;
            });

        $schoolClasses = collect(collect($response->json('r.dbiAccessorRes.tables'))
            ->first(fn($table) => $table['id'] === "classes")['data_rows'])
            ->map(function ($row) {
                $schoolClass = new SchoolClass();
                $schoolClass->id = substr($row['id'], 1);
                $schoolClass->name = $row['name'];
                $schoolClass->short = $row['short'];
                return $schoolClass;
            });

        $teachers = collect(collect($response->json('r.dbiAccessorRes.tables'))
            ->first(fn($table) => $table['id'] === "teachers")['data_rows'])
            ->map(function ($row) {
                $teacher = new Teacher();
                $teacher->id = substr($row['id'], 1);
                $teacher->first_name = $row['firstname'];
                $teacher->last_name = $row['lastname'];
                $teacher->short = $row['short'];
                return $teacher;
            });

        $groups = collect(collect($response->json('r.dbiAccessorRes.tables'))
            ->first(fn($table) => $table['id'] === "groups")['data_rows'])
            ->map(function ($row) {
                $group = new Group();
                $group->id = substr($row['id'], 1);
                $group->name = $row['name'];
                $group->class_id = $row['classid'];
                $group->short = $row['short'];
                return $group;
            });

        $subjects = collect(collect($response->json('r.dbiAccessorRes.tables'))
            ->first(fn($table) => $table['id'] === "subjects")['data_rows'])
            ->map(function ($row) {
                $subject = new Subject();
                $subject->id = substr($row['id'], 1);
                $subject->name = $row['name'];
                $subject->short = $row['short'];
                return $subject;
            });

        return $periods;
    }
}
