@extends('layouts.app')

@section('title', 'Új feltöltés')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Új feltöltés</h1>
        <a href="{{ route('user-submissions') }}" class="btn btn-outline-secondary">Vissza</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('store-user-submission') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label for="promotion_id" class="form-label">Promóció</label>
                    <select id="promotion_id" name="promotion_id" class="form-select" required>
                        <option value="">Válassz promóciót...</option>
                        @foreach($promotions as $promotion)
                            <option value="{{ $promotion->id }}" @selected(old('promotion_id') == $promotion->id)>
                                {{ $promotion->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="doc_img_path" class="form-label">Dokumentum (jpg, png, ...)</label>
                    <input id="doc_img_path" type="file" name="doc_img_path" class="form-control" accept="image/*" required>
                </div>

                <div class="mb-3">
                    <label for="ap_no" class="form-label">AP szám (számla azonosító)</label>
                    <input id="ap_no" type="number" name="ap_no" class="form-control" min="1" step="1" value="{{ old('ap_no') }}" required>
                </div>

                <div class="mb-3">
                    <label for="purchase_date" class="form-label">Vásárlás dátuma</label>
                    <input id="purchase_date" type="date" name="purchase_date" class="form-control" value="{{ old('purchase_date') }}" required>
                </div>

                <div class="mb-4">
                    <label for="items" class="form-label">Termékek</label>
                    <select id="items" name="items[]" class="form-select" multiple required>
                        @foreach($products as $product)
                            <option value="{{ $product->id }}" @selected(collect(old('items', []))->contains($product->id))>
                                {{ $product->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Feltöltés mentése</button>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(function () {
            $('#items').select2({
                width: '100%',
                placeholder: 'Valassz termekeket...'
            });
        });
    </script>
@endpush
