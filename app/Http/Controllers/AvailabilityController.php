<?php

namespace App\Http\Controllers;

use App\Http\Requests\Availability\IndexAvailabilityRequest;
use App\Http\Requests\Availability\StoreAvailabilityRequest;
use App\Http\Requests\Availability\UpdateAvailabilityRequest;
use App\Models\Availability;
use App\Services\AvailabilityService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use RuntimeException;

class AvailabilityController extends Controller
{
    public function __construct(
        private readonly AvailabilityService $availabilityService,
    ) {}

    public function index(IndexAvailabilityRequest $request): Response
    {
        $validated = $request->validated();

        $availabilitiesList = Availability::query()
            ->when(! empty($validated['weekday']), function ($weekdayQuery) use ($validated) {
                $weekdayQuery->where('weekday', $validated['weekday']);
            })
            ->orderBy('start_time')
            ->get();

        return Inertia::render('settings/Availabilities', [
            'availabilities_list' => $availabilitiesList,
        ]);
    }

    public function store(StoreAvailabilityRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            $this->availabilityService->create($validated);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Disponibilidade adicionada com sucesso.');
    }

    public function update(UpdateAvailabilityRequest $request, Availability $availability): RedirectResponse
    {
        $validated = $request->validated();

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
