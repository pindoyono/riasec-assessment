<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use App\Models\Question;
use App\Models\School;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $totalStudents = Student::count();
        $completedAssessments = Assessment::where('status', 'completed')->count();
        $pendingAssessments = Assessment::whereIn('status', ['pending', 'in_progress'])->count();
        $totalSchools = School::where('is_active', true)->count();
        $totalQuestions = Question::where('is_active', true)->count();

        return [
            Stat::make('Total Siswa', $totalStudents)
                ->description('Siswa terdaftar')
                ->descriptionIcon('heroicon-m-user-group')
                ->color('primary')
                ->chart([7, 3, 4, 5, 6, 3, 5, 8]),

            Stat::make('Assessment Selesai', $completedAssessments)
                ->description('Assessment telah dikerjakan')
                ->descriptionIcon('heroicon-m-clipboard-document-check')
                ->color('success')
                ->chart([2, 4, 6, 3, 7, 5, 8, $completedAssessments]),

            Stat::make('Menunggu Assessment', $pendingAssessments)
                ->description('Belum/sedang mengerjakan')
                ->descriptionIcon('heroicon-m-clock')
                ->color('warning'),

            Stat::make('Total Sekolah', $totalSchools)
                ->description('Sekolah aktif')
                ->descriptionIcon('heroicon-m-building-library')
                ->color('info'),
        ];
    }
}
