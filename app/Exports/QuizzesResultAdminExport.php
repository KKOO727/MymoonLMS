<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QuizzesResultAdminExport implements FromCollection, WithHeadings, WithMapping
{
    protected $sheets;

    public function __construct($sheets)
    {
        $this->sheets = $sheets;
    }

    public function collection()
    {
        return $this->sheets;
    }

    public function headings(): array
    {
        return [
            trans('admin.th_name'),
            trans('admin.student'),
            trans('admin.instructor'),
            trans('admin.grades'),
            trans('admin.grade_date'),
            trans('admin.th_status'),
        ];
    }

    public function map($result): array
    {
        $quiz_name = $result->quiz->name . PHP_EOL . ' (' . $result->quiz->content->title . ') ';
        if ($result->status == 'pass') {
            $status = trans('main.passed');
        } elseif ($result->status == 'fail') {
            $status = trans('main.failed');
        } else {
            $status = trans('main.waiting');
        }


        return [
            $quiz_name,
            $result->student->name,
            $result->quiz->user->name,
            $result->user_grade,
            date('Y-m-d | H:i', $result->created_at),
            $status
        ];
    }

}
