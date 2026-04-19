<?php

namespace App\Filament\Widgets;

use App\Models\Assessment;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class RiasecDistributionChart extends ChartWidget
{
    protected ?string $heading = 'Distribusi Tipe RIASEC';

    protected static ?int $sort = 2;

    protected int | string | array $columnSpan = 'half';

    protected function getData(): array
    {
        $distribution = Assessment::where('status', 'completed')
            ->whereNotNull('riasec_code')
            ->get()
            ->flatMap(function ($assessment) {
                return str_split($assessment->riasec_code ?? '');
            })
            ->countBy()
            ->toArray();

        $categories = ['R', 'I', 'A', 'S', 'E', 'C'];
        $data = [];

        foreach ($categories as $code) {
            $data[] = $distribution[$code] ?? 0;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah',
                    'data' => $data,
                    'backgroundColor' => [
                        '#ef4444', // R - Red
                        '#3b82f6', // I - Blue
                        '#eab308', // A - Yellow
                        '#22c55e', // S - Green
                        '#8b5cf6', // E - Purple
                        '#6b7280', // C - Gray
                    ],
                ],
            ],
            'labels' => ['Realistic', 'Investigative', 'Artistic', 'Social', 'Enterprising', 'Conventional'],
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'display' => false,
                ],
            ],
            'scales' => [
                'y' => [
                    'beginAtZero' => true,
                ],
            ],
        ];
    }
}
