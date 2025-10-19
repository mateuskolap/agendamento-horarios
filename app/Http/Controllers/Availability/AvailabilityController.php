<?php

namespace App\Http\Controllers\Availability;

use App\Data\StoreAvailabilityData;
use App\Http\Controllers\Controller;
use App\Http\Requests\Availability\IndexAvailabilityRequest;
use App\Http\Requests\Availability\StoreAvailabilityRequest;
use App\Models\Availability;
use App\Services\Availability\AvailabilityService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use RuntimeException;

class AvailabilityController extends Controller
{
    public function __construct(
        private readonly AvailabilityService $availabilityService,
    )
    {
    }

    public function index(IndexAvailabilityRequest $request): Response
    {
        $validated = $request->validated();

        $availabilitiesList = Availability::query()
            ->when(!empty($validated['weekday']), function ($weekdayQuery) use ($validated) {
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
        $storeAvailabilityDTO = StoreAvailabilityData::from($request->validated());

        try {
            $this->availabilityService->create($storeAvailabilityDTO);
        } catch (RuntimeException $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', 'Disponibilidade adicionada com sucesso.');
    }

    public function destroy(Availability $availability): RedirectResponse
    {
        $availability->delete();

        return back()->with('success', 'Disponibilidade removida com sucesso.');
    }
}
