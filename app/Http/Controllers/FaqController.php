<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\View\View;

class FaqController extends Controller
{
    public function index(): View
    {
        $faqs = Faq::query()
            ->orderBy('created_at')
            ->get();

        return view('faq-list', compact('faqs'));
    }
}
