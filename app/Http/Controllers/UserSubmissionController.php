<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Promotion;
use App\Models\Submission;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserSubmissionController extends Controller
{
    public function index()
    {
        $submissions = Submission::query()
            ->with('promotion')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('user_submissions', compact('submissions'));
    }

    public function show(Submission $submission)
    {
        abort_unless($submission->user_id === Auth::id(), 403);

        $submission->load('promotion');

        return view('user_submission_view', compact('submission'));
    }

    public function create()
    {
        $promotions = Promotion::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        $products = Product::query()
            ->orderBy('name')
            ->get(['id', 'name']);

        return view('user_submission_create', compact('promotions', 'products'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'promotion_id' => ['required', 'exists:promotions,id'],
            'doc_img_path' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,bmp', 'max:5120'],
            'ap_no' => ['required', 'integer', 'min:1'],
            'purchase_date' => ['required', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*' => ['integer', 'exists:products,id'],
        ], [
            'required' => 'A(z) :attribute mező kitöltése kötelező.',
            'exists' => 'A(z) :attribute mezőben érvénytelen érték szerepel.',
            'file' => 'A(z) :attribute mezőben fájlt kell feltölteni.',
            'mimes' => 'A(z) :attribute csak a következő formátumok egyikében tölthető fel: :values.',
            'max.file' => 'A(z) :attribute mérete legfeljebb :max KB lehet.',
            'integer' => 'A(z) :attribute mező csak egész szám lehet.',
            'min.numeric' => 'A(z) :attribute értéke legalább :min kell legyen.',
            'date' => 'A(z) :attribute mezőnek érvényes dátumnak kell lennie.',
            'array' => 'A(z) :attribute mezőnek tömb típusúnak kell lennie.',
            'min.array' => 'A(z) :attribute mezőben legalább :min elemet kell megadni.',
            'items.*.exists' => 'A kiválasztott termék nem létezik.',
            'items.*.integer' => 'A termék azonosítója csak egész szám lehet.',
        ], [
            'promotion_id' => 'promóció',
            'doc_img_path' => 'dokumentum',
            'ap_no' => 'AP szám',
            'purchase_date' => 'vásárlás dátuma',
            'items' => 'termékek',
            'items.*' => 'termék',
        ]);

        $validator->after(function ($validator) use ($request) {
            $promotionId = $request->input('promotion_id');
            $purchaseDateInput = $request->input('purchase_date');

            if (!$promotionId) {
                return;
            }

            $promotion = Promotion::find($promotionId);

            if (!$promotion) {
                return;
            }

            if ($purchaseDateInput) {
                $purchaseDate = Carbon::parse($purchaseDateInput)->startOfDay();
                $promotionDateFrom = Carbon::parse($promotion->date_from)->startOfDay();
                $promotionDateTo = Carbon::parse($promotion->date_to)->endOfDay();

                if ($purchaseDate->lt($promotionDateFrom) || $purchaseDate->gt($promotionDateTo)) {
                    $validator->errors()->add(
                        'purchase_date',
                        'A vásárlás dátuma csak a promóció érvényességi időszakán belül lehet.'
                    );
                }
            }

            $now = now();
            $uploadFrom = Carbon::parse($promotion->upload_from);
            $uploadTo = Carbon::parse($promotion->upload_to);

            if ($now->lt($uploadFrom) || $now->gt($uploadTo)) {
                $validator->errors()->add(
                    'promotion_id',
                    'A feltöltés időpontja kívül esik a promóció feltöltési időablakán.'
                );
            }
        });

        $validated = $validator->validate();

        $selectedProductIds = array_map('intval', $validated['items']);
        $products = Product::query()
            ->whereIn('id', $selectedProductIds)
            ->get(['id', 'name', 'price'])
            ->keyBy('id');

        $itemsData = [];
        foreach ($selectedProductIds as $productId) {
            $product = $products->get($productId);
            if (!$product) {
                continue;
            }

            $itemsData[] = [
                'id' => (int) $product->id,
                'name' => (string) $product->name,
                'price' => (int) $product->price,
            ];
        }

        $storedPath = $request->file('doc_img_path')->store('doc_images', 'public');

        Submission::create([
            'user_id' => Auth::id(),
            'promotion_id' => $validated['promotion_id'],
            'doc_img_path' => $storedPath,
            'ap_no' => $validated['ap_no'],
            'items' => $itemsData,
            'purchase_date' => $validated['purchase_date'],
            'status' => 'submitted',
        ]);

        return redirect()
            ->route('user-submissions')
            ->with('success', 'A feltöltés sikeresen elküldve.');
    }
}
