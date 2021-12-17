<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class BalanceAdminExport implements FromCollection, WithHeadings, WithMapping
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
            trans('admin.document_number'),
            trans('admin.th_date'),
            trans('admin.description'),
            trans('admin.document_type'),
            trans('admin.amount'),
            trans('admin.creator'),
            trans('admin.user'),
            trans('admin.extra_info')
        ];
    }

    public function map($sheet): array
    {
        $type = ($sheet->type == 'add') ? trans('admin.addiction') : trans('admin.deduction');
        $mode = ($sheet->mode == 'user') ? $sheet->exporter->name : trans('admin.automatic');
        $tuser = empty($sheet->user) ? '' : $sheet->user->name;


        return [
            $sheet->id,
            date('d F Y | H:i', $sheet->created_at),
            $sheet->title,
            $type,
            $sheet->price,
            $mode,
            $tuser,
            $sheet->description
        ];
    }
}
