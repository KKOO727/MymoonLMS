<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DrawAdminExport implements FromCollection, WithHeadings, WithMapping
{
    protected $users;
    protected $allsum;

    public function __construct($users,$allsum)
    {
        $this->users = $users;
        $this->allsum = $allsum;
    }

    public function collection()
    {
        return $this->users;
    }

    public function headings(): array
    {
        return [
            trans('admin.username'),
            trans('admin.real_name'),
            trans('admin.amount'),
            trans('admin.bank_name'),
            trans('admin.creditcard_number'),
            trans('admin.account_number'),
            trans('admin.total_payment_table_header'),
        ];
    }

    public function map($user): array
    {
        $meta = arrayToList($user->userMetas, 'option', 'value');
        $bank_name = isset($meta['bank_name']) ? $meta['bank_name'] : '';
        $bank_card = isset($meta['bank_card']) ? $meta['bank_card'] : '';
        $bank_account = isset($meta['bank_account']) ? $meta['bank_account'] : '';
        $total_payment = $this->allsum . trans('admin.currency');

        return [
            $user->username,
            $user->name,
            $user->income,
            $bank_name,
            $bank_card,
            $bank_account,
            $total_payment
        ];
    }
}
