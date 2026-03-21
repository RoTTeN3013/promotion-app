<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Promotion;

class DashboardController extends Controller
{
    public function Show()
    {
        $activePromotions = (new Promotion())->getActivePromotions();
        return view('user_dashboard', compact('activePromotions'));
    }

    public function showPromotion(Promotion $promotion)
    {
        $promotion->load('promotionItems.product');

        return view('promotion_details', compact('promotion'));
    }
}
