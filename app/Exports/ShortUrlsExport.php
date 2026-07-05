<?php

namespace App\Exports;

use App\Models\ShortUrl;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ShortUrlsExport implements FromCollection, WithHeadings
{
    protected $filter;

    public function __construct($filter)
    {
        $this->filter = $filter;
    }

    public function collection()
    {
        $query = ShortUrl::with('company');

        switch ($this->filter) {

            case 'today':

                $query->whereDate('created_at', today());

                break;

            case 'last_week':

                $query->whereBetween('created_at', [
                    now()->subWeek()->startOfWeek(),
                    now()->subWeek()->endOfWeek(),
                ]);

                break;

            case 'last_month':

                $query->whereMonth('created_at', now()->subMonth()->month)
                      ->whereYear('created_at', now()->subMonth()->year);

                break;

            case 'this_month':
            default:

                $query->whereMonth('created_at', now()->month)
                      ->whereYear('created_at', now()->year);

                break;
        }

        return $query->get()->map(function ($shortUrl) {

            return [

                'Company'      => $shortUrl->company->name ?? '-',

                'Original URL' => $shortUrl->original_url,

                'Short URL'    => url('/s/' . $shortUrl->short_code),

                'Hits'         => $shortUrl->hits,

                'Created At'   => $shortUrl->created_at->format('d M Y'),

            ];

        });
    }

    public function headings(): array
    {
        return [

            'Company',

            'Original URL',

            'Short URL',

            'Hits',

            'Created At',

        ];
    }
}