<?php

namespace App\Http\Controllers;

use App\Models\Submission;
use Illuminate\Support\Facades\Auth;

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
}
