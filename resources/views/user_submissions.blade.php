@extends('layouts.app')

@php
    use App\Helpers\SubmissionStatusHelper;
@endphp

@section('title', 'Saját feltöltéseim')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">Saját feltöltéseim</h1>
        <a href="{{ route('create-user-submission') }}" class="btn btn-primary">Új feltöltés</a>
    </div>

    @if($submissions->isEmpty())
        <div class="alert alert-info">
            Még nincs egyetlen feltöltésed sem.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped table-hover align-middle">
                <thead>
                    <tr>
                        <th>#Azonosító</th>
                        <th>Promóció</th>
                        <th>Statusz</th>
                        <th>Termékek</th>
                        <th>Vásárlás dátuma</th>
                        <th class="text-end">Műveletek</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($submissions as $submission)
                        <tr>
                            <td>{{ $submission->id }}</td>
                            <td>{{ $submission->promotion?->name ?? '-' }}</td>
                            <td>
                                {{ SubmissionStatusHelper::label($submission->status) }}
                                @if($submission->status === 'need_data' && $submission->message)
                                    <br><small class="text-muted">💬 Üzenet érkezett</small>
                                @endif
                            </td>
                            <td>
                                @if($submission->items)
                                    <ul class="mb-0">
                                        @foreach($submission->items as $item)
                                            <li>{{ $item['name'] }} ({{ $item['price'] }} Ft)</li>
                                        @endforeach
                                    </ul>
                                @else
                                    -
                                @endif
                            </td>
                            <td>{{ $submission->purchase_date?->format('Y-m-d') ?? '-' }}</td>
                            <td class="text-end">
                                <a href="{{ route('view-user-submission', $submission) }}" class="btn btn-outline-secondary btn-sm" title="Megtekintes">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>

                                @if($submission->status === 'submitted')
                                    <form action="{{ route('delete-user-submission', $submission) }}" method="POST" class="d-inline" onsubmit="return confirm('Biztosan törölni szeretnéd ezt a feltöltést?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" title="Törlés">
                                            <i class="fa fa-trash" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                @endif

                                @if($submission->status === 'rejected' && $submission->appeald_at === null)
                                    <form action="{{ route('appeal-user-submission', $submission) }}" method="POST" class="d-inline" onsubmit="return confirm('Biztosan szeretnél fellebbezést benyújtani?');">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-warning btn-sm" title="Fellebbezés">
                                            <i class="fa fa-gavel" aria-hidden="true"></i>
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
