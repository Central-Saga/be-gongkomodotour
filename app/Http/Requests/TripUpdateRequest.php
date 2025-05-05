<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TripUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'          => 'sometimes|required|string|max:255',
            'include'       => 'sometimes|required|string',
            'exclude'       => 'sometimes|required|string',
            'note'          => 'sometimes|nullable|string',
            'region'        => 'sometimes|required|in:Domestic,Overseas,Domestic & Overseas',
            'start_time'    => 'sometimes|required|date_format:H:i:s',
            'end_time'      => 'sometimes|required|after_or_equal:start_time|date_format:H:i:s',
            'meeting_point' => 'sometimes|required|string',
            'type'          => 'sometimes|required|string|in:Open Trip,Private Trip',
            'status'        => 'sometimes|required|in:Aktif,Non Aktif',
            'is_highlight'  => 'sometimes|required|in:Yes,No',
            'destination_count' => 'sometimes|required|integer|min:0',
            'has_boat'      => 'sometimes|required|boolean',
            'has_hotel'     => 'sometimes|required|boolean',

            // Validasi untuk itineraries
            'itineraries' => 'sometimes|array',
            'itineraries.*.day_number' => 'required_with:itineraries|integer',
            'itineraries.*.activities' => 'required_with:itineraries|string',

            // Validasi untuk flight schedules
            'flight_schedules' => 'sometimes|array',
            'flight_schedules.*.route' => 'required_with:flight_schedules|string',
            'flight_schedules.*.eta_time' => 'nullable|date_format:H:i:s',
            'flight_schedules.*.eta_text' => 'nullable|string',
            'flight_schedules.*.etd_time' => 'nullable|date_format:H:i:s',
            'flight_schedules.*.etd_text' => 'nullable|string',

            // Validasi untuk trip durations
            'trip_durations' => 'sometimes|array',
            'trip_durations.*.duration_label' => 'required_with:trip_durations|string',
            'trip_durations.*.duration_days' => 'required_with:trip_durations|integer',
            'trip_durations.*.duration_nights' => 'required_with:trip_durations|integer',
            'trip_durations.*.status' => 'required_with:trip_durations|in:Aktif,Non Aktif',

            // Validasi untuk itineraries dalam trip durations
            'trip_durations.*.itineraries' => 'sometimes|array',
            'trip_durations.*.itineraries.*.id' => 'sometimes|integer',
            'trip_durations.*.itineraries.*.day_number' => 'required_with:trip_durations.*.itineraries|integer',
            'trip_durations.*.itineraries.*.activities' => 'required_with:trip_durations.*.itineraries|string',

            // Validasi untuk trip prices dalam setiap trip durations
            'trip_durations.*.prices' => 'sometimes|array',
            'trip_durations.*.prices.*.pax_min' => 'required_with:trip_durations.*.prices|integer',
            'trip_durations.*.prices.*.pax_max' => 'required_with:trip_durations.*.prices|integer',
            'trip_durations.*.prices.*.price_per_pax' => 'required_with:trip_durations.*.prices|numeric|min:0',
            'trip_durations.*.prices.*.status' => 'required_with:trip_durations.*.prices|in:Aktif,Non Aktif',

            // Validasi untuk additional fees
            'additional_fees' => 'sometimes|array',
            'additional_fees.*.fee_category' => 'required_with:additional_fees|string',
            'additional_fees.*.price' => 'required_with:additional_fees|numeric|min:0',
            'additional_fees.*.region' => 'required_with:additional_fees|in:Domestic,Overseas,Domestic & Overseas',
            'additional_fees.*.unit' => 'required_with:additional_fees|in:per_pax,per_5pax,per_day,per_day_guide',
            'additional_fees.*.pax_min' => 'required_with:additional_fees|integer',
            'additional_fees.*.pax_max' => 'required_with:additional_fees|integer',
            'additional_fees.*.day_type' => 'required_with:additional_fees|in:Weekday,Weekend',
            'additional_fees.*.is_required' => 'required_with:additional_fees|boolean',
            'additional_fees.*.status' => 'required_with:additional_fees|in:Aktif,Non Aktif',

            // Validasi untuk surcharge
            'surcharges' => 'sometimes|array',
            'surcharges.*.season' => 'required_with:surcharges|string',
            'surcharges.*.start_date' => 'required_with:surcharges|date',
            'surcharges.*.end_date' => 'required_with:surcharges|date',
            'surcharges.*.surcharge_price' => 'required_with:surcharges|numeric|min:0',
            'surcharges.*.status' => 'required_with:surcharges|in:Aktif,Non Aktif',
        ];
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'trip_durations' => $this->convertPricesToFloat($this->trip_durations ?? []),
            'additional_fees' => $this->convertAdditionalFeesToFloat($this->additional_fees ?? []),
            'surcharges' => $this->convertSurchargesToFloat($this->surcharges ?? []),
            'has_boat' => (bool) $this->has_boat
        ]);

        // Konversi is_required ke boolean
        if ($this->has('additional_fees')) {
            $this->merge([
                'additional_fees' => array_map(function ($fee) {
                    if (isset($fee['is_required'])) {
                        $fee['is_required'] = (bool) $fee['is_required'];
                    }
                    return $fee;
                }, $this->additional_fees)
            ]);
        }
    }

    /**
     * Convert prices to float in trip durations
     */
    private function convertPricesToFloat(array $durations): array
    {
        return array_map(function ($duration) {
            if (isset($duration['prices'])) {
                $duration['prices'] = array_map(function ($price) {
                    if (isset($price['price_per_pax'])) {
                        $price['price_per_pax'] = (float) $price['price_per_pax'];
                    }
                    return $price;
                }, $duration['prices']);
            }
            return $duration;
        }, $durations);
    }

    /**
     * Convert additional fees prices to float
     */
    private function convertAdditionalFeesToFloat(array $fees): array
    {
        return array_map(function ($fee) {
            if (isset($fee['price'])) {
                $fee['price'] = (float) $fee['price'];
            }
            return $fee;
        }, $fees);
    }

    /**
     * Convert surcharges prices to float
     */
    private function convertSurchargesToFloat(array $surcharges): array
    {
        return array_map(function ($surcharge) {
            if (isset($surcharge['surcharge_price'])) {
                $surcharge['surcharge_price'] = (float) $surcharge['surcharge_price'];
            }
            return $surcharge;
        }, $surcharges);
    }
}
