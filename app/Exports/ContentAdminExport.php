<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ContentAdminExport implements FromCollection, WithHeadings, WithMapping
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
            trans('admin.course_title'),
            trans('admin.th_date'),
            trans('admin.th_vendor'),
            trans('admin.sales'),
            trans('admin.parts'),
            trans('admin.income'),
            trans('admin.views'),
            trans('admin.price'),
            trans('admin.category'),
            trans('admin.publish_type'),
            trans('admin.th_status')
        ];
    }

    public function map($sheet): array
    {
        $meta = arrayToList($sheet->metas, 'option', 'value');
        $privete = ($sheet->private == 1) ? trans('admin.exclusive') : trans('admin.open');
        $mode = ($sheet->mode == 'publish') ? trans('admin.published') : (($sheet->mode == 'waiting') ? trans('admin.waiting') : trans('admin.unpublished'));
        $category = (isset($sheet->category->title)) ? $sheet->category->title : trans('admin.not_defined');

        return [
            $sheet->title,
            date('d F Y | H:i', $sheet->created_at),
            $sheet->user->username,
            $sheet->sells_count,
            $sheet->partsactive_count,
            $sheet->transactions->sum('price'),
            $sheet->view,
            isset($meta['price']) ? $meta['price'] : '',
            $category,
            $privete,
            $mode
        ];
    }

}
