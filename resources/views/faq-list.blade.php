@extends('layouts.app')

@section('title', 'GYIK - Gyakran Ismételt Kérdések')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">GYIK - Gyakran Ismételt Kérdések</h1>
        <a href="{{ route('user-dashboard') }}" class="btn btn-outline-secondary">Vissza az irányítópultra</a>
    </div>

    @if($faqs->isEmpty())
        <div class="alert alert-info">
            Jelenleg nincsenek elérhető kérdések és válaszok.
        </div>
    @else
        <div class="accordion" id="faqAccordion">
            @foreach($faqs as $index => $faq)
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button @if($index !== 0) collapsed @endif" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $faq->id }}" aria-expanded="@if($index === 0) true @else false @endif" aria-controls="collapse{{ $faq->id }}">
                            {{ $faq->question }}
                        </button>
                    </h2>
                    <div id="collapse{{ $faq->id }}" class="accordion-collapse collapse @if($index === 0) show @endif" data-bs-parent="#faqAccordion">
                        <div class="accordion-body">
                            {{ $faq->answer }}
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
