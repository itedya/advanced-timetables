<?php

namespace App\Console\Commands;

use App\Models\Period;
use App\Models\Timetable;
use App\Repositories\aScServerRepository;
use Illuminate\Console\Command;
use Illuminate\Support\Collection;

class RefreshServerData extends Command
{
    public function __construct(private aScServerRepository $ascServerRepository)
    {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'asc-server:refresh-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh data from asc server';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $timetables = $this->ascServerRepository->fetchAvailableTimetables();

        $t = $this->ascServerRepository->fetchTimetableData($timetables[0]->id);
        dd($t);
//        $timetablesData = $timetables->map(function ($timetable) {
//            return $this->ascServerRepository->fetchTimetableData($timetable->id);
//        });
//
//        dd($timetablesData);

//        $timetables->forEach(function ($ele) {
//            $ele->saveOrFail();
//        });

        return Command::SUCCESS;
    }
}
