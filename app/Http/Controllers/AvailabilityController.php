<?php

namespace App\Http\Controllers;

use App\Models\Availability;
use App\Services\AvailabilityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use RuntimeException;

class AvailabilityController extends Controller
{
    public function __construct(
        private readonly AvailabilityService $availabilityService,
    ) {}

    public function index(Request $request): Response
    {
        $request->merge([
            'weekday' => $request->input('weekday', 1),
        ]);

        $validated = $request->validate([
            'weekday' => ['nullable', 'integer', 'between:1,6'],
        ]);

        $availabilitiesList = Availability::query()
            ->when(! empty($validated['weekday']), function ($weekdayQuery) use ($validated) {
                $weekdayQuery->where('weekday', $validated['weekday']);
            })
            ->get();

        return Inertia::render('settings/Availabilities', [
            'availabilities_list' => $availabilitiesList,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'weekday' => ['required', 'integer', 'between:1,6'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        try {
            $this->availabilityService->create($validated);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back();
    }

    public function update(Request $request, Availability $availability): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => ['nullable', 'exists:users,id'],
            'weekday' => ['nullable', 'integer', 'between:1,6'],
            'start_time' => ['nullable', 'date_format:H:i'],
            'end_time' => ['nullable', 'date_format:H:i', 'after:start_time'],
        ]);

        try {
            $this->availabilityService->update($availability, $validated);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back();
    }

    public function destroy(Availability $availability): RedirectResponse
    {
        $availability->delete();

        return back()->with('success', 'Disponibilidade removida com sucesso.');
    }
}
