<?php

namespace App\Http\Controllers;

use App\Models\StudyGroup;
use App\Models\BlogRequest;
use App\Models\EventRequest;
use App\Models\Report;
use App\Models\User;
use App\Models\Forum;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    public function index(): View
    {
        $pendingStudyGroups = StudyGroup::where('status', 'pending')->count();
        $pendingBlogs = BlogRequest::where('status', 'pending')->count();
        $pendingEvents = EventRequest::where('status', 'pending')->count();
        $pendingReports = Report::where('status', 'pending')->count();

        return view('admin.index', compact(
            'pendingStudyGroups',
            'pendingBlogs',
            'pendingEvents',
            'pendingReports'
        ));
    }
}
