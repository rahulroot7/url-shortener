<?php

namespace App\Exports;

use App\Models\ShortUrl;
use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MemberShortUrlsExport implements FromCollection, WithHeadings
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
        $query = ShortUrl::where('user_id', $this->user->id);

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

        return $query->latest()->get()->map(function ($shortUrl) {

            return [

                'Short URL'  => url('/s/' . $shortUrl->short_code),

                'Long URL'   => $shortUrl->original_url,

                'Hits'       => $shortUrl->hits,

                'Created On' => $shortUrl->created_at->format('d M Y h:i A'),

            ];
        });
    }

    public function headings(): array
    {
        return [
            'Short URL',
            'Long URL',
            'Hits',
            'Created On',
        ];
    }
}