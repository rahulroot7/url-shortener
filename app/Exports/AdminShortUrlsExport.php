<?php

namespace App\Exports;

use App\Models\ShortUrl;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class AdminShortUrlsExport implements FromCollection, WithHeadings
{
    protected $user;
    protected $filter;

    public function __construct(User $user, $filter)
    {
        $this->user = $user;
        $this->filter = $filter;
    }

    public function collection()
    {
        $query = ShortUrl::with('user')
            ->where('company_id', $this->user->company_id);

        switch ($this->filter ?? 'this_month') {

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

        return $query->latest()->get()->map(function ($url) {

            return [

                'Short URL'  => url('/s/' . $url->short_code),

                'Long URL'   => $url->original_url,

                'Hits'       => $url->hits,

                'User Name'  => $url->user->name ?? '-',

                'Created On' => $url->created_at->format('d M Y h:i A'),

            ];
        });
    }

    public function headings(): array
    {
        return [
            'Short URL',
            'Long URL',
            'Hits',
            'User Name',
            'Created On',
        ];
    }
}