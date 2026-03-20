@extends('layouts.app')

@section('title', 'Sajat feltolteseim')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Sajat feltolteseim</h1>
        <a href="#" class="btn btn-primary">Uj feltoltes</a>
    </div>

    @if($submissions->isEmpty())
        <div class="alert alert-info">
            Meg nincs egyetlen feltoltesed sem.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Promo</th>
                        <th>Statusz</th>
                        <th>Vasarlas datuma</th>
                        <th class="text-end">Muvelet</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $submission)
                        <tr>
                            <td>{{ $submission->id }}</td>
                            <td>{{ $submission->promotion?->name ?? '-' }}</td>
                            <td>{{ $submission->status ?? '-' }}</td>
                            <td>{{ $submission->purchase_date?->format('Y-m-d') ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('view-user-submission', $submission) }}" class="btn btn-outline-secondary btn-sm" title="Megtekintes">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
