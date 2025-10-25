<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Exports\TemplateExport;

class GenerateTemplates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-templates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Excel templates for chart types';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Storage::makeDirectory('templates');

        $templates = [
            'pie' => [
                ['Label', 'Value'],
                ['Category 1', 10],
                ['Category 2', 20],
                ['Category 3', 30],
            ],
            'bar' => [
                ['Label', 'Series 1', 'Series 2'],
                ['Jan', 10, 15],
                ['Feb', 20, 25],
                ['Mar', 30, 35],
            ],
            'line' => [
                ['Label', 'Series 1', 'Series 2'],
                ['Q1', 100, 120],
                ['Q2', 150, 140],
                ['Q3', 200, 180],
            ],
            'doughnut' => [
                ['Label', 'Value'],
                ['Segment 1', 25],
                ['Segment 2', 35],
                ['Segment 3', 40],
            ],
            'area' => [
                ['Label', 'Series 1', 'Series 2'],
                ['Week 1', 50, 60],
                ['Week 2', 70, 80],
                ['Week 3', 90, 100],
            ],
            'radar' => [
                ['Label', 'Series 1', 'Series 2'],
                ['Skill 1', 80, 90],
                ['Skill 2', 70, 85],
                ['Skill 3', 90, 95],
            ],
        ];

        foreach ($templates as $type => $data) {
            Excel::store(new TemplateExport($data), 'templates/' . $type . '_template.xlsx', 'public');

            $this->info("Generated template for {$type}");
        }

        $this->info('All templates generated successfully!');
    }
}

