<?php

namespace App\Http\Controllers;

use App\Models\SupportTicket;
use Illuminate\View\View;

class PortalController extends Controller
{
    public function index(): View
    {
        return view('portal.index', [
            'tickets' => SupportTicket::query()->latest()->take(5)->get(),
        ]);
    }
}
