<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class QuizzesAdminExport implements FromCollection, WithHeadings, WithMapping
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
            trans('admin.instructor'),
            trans('admin.question_count'),
            trans('admin.participants_count'),
            trans('admin.average_grade'),
            trans('admin.certificate'),
            trans('admin.th_status'),
        ];
    }

    public function map($quiz): array
    {
        $quiz_name = $quiz->name . PHP_EOL . ' (' . $quiz->content->title . ') ';
        $certificate = ($quiz->certificate) ? trans('admin.yes') : trans('admin.no');
        $status = ($quiz->status == 'active') ? trans('admin.active') : trans('admin.disabled');

        return [
            $quiz_name,
            $quiz->user->name,
            count($quiz->questions),
            0,
            0,
            $certificate,
            $status
        ];
    }

}
