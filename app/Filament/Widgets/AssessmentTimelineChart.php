<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use Carbon\Carbon;
use Filament\Widgets\ChartWidget;

class AssessmentTimelineChart extends ChartWidget
{
    protected ?string $heading = 'Assessment per Hari (7 Hari Terakhir)';

    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'half';

    protected function getData(): array
    {
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $labels[] = $date->format('d M');

            $count = Assessment::where('status', 'completed')
                ->whereDate('completed_at', $date)
                ->count();

            $data[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Assessment Selesai',
                    'data' => $data,
                    'borderColor' => '#4f46e5',
                    'backgroundColor' => 'rgba(79, 70, 229, 0.1)',
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
