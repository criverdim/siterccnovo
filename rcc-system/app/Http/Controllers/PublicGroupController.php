<?php

namespace App\Http\Controllers;

use App\Models\Group;

class PublicGroupController extends Controller
{
    public function index()
    {
        $q = request()->string('q')->toString();
        $weekday = request()->string('weekday')->toString();

        $groups = Group::query()
            ->when($q, fn ($qr) => $qr->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%$q%")
                    ->orWhere('address', 'like', "%$q%");
            }))
            ->when($weekday, fn ($qr) => $qr->where('weekday', $weekday))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        return view('groups.index', compact('groups'));
    }

    public function show(Group $group)
    {
        return view('groups.show', compact('group'));
    }
}
