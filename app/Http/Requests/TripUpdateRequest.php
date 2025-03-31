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
            'start_time'    => 'sometimes|required|date_format:H:i:s',
            'end_time'      => 'sometimes|required|after_or_equal:start_time|date_format:H:i:s',
            'meeting_point' => 'sometimes|required|string',
            'type'          => 'sometimes|required|string|in:Open Trip,Private Trip',
            'status'        => 'sometimes|required|in:Aktif,Non Aktif',

            // Validasi untuk itineraries
            'itineraries' => 'sometimes|array',
            'itineraries.*.day_number' => 'required_with:itineraries|integer',
            'itineraries.*.activities' => 'required_with:itineraries|string',

            // Validasi untuk flight schedules
            'flight_schedules' => 'sometimes|array',
            'flight_schedules.*.route' => 'required_with:flight_schedules|string',
            'flight_schedules.*.eta_time' => 'required_with:flight_schedules|date_format:H:i:s',
            'flight_schedules.*.eta_text' => 'required_with:flight_schedules|string',
            'flight_schedules.*.etd_time' => 'required_with:flight_schedules|date_format:H:i:s',
            'flight_schedules.*.etd_text' => 'required_with:flight_schedules|string',

            // Validasi untuk trip durations
            'trip_durations' => 'sometimes|array',
            'trip_durations.*.duration_label' => 'required_with:trip_durations|string',
            'trip_durations.*.duration_days' => 'required_with:trip_durations|integer',
            'trip_durations.*.duration_nights' => 'required_with:trip_durations|integer',
            'trip_durations.*.status' => 'required_with:trip_durations|in:Aktif,Non Aktif',

            // Validasi untuk trip prices dalam setiap trip durations
            'trip_durations.*.prices' => 'sometimes|array',
            'trip_durations.*.prices.*.pax_min' => 'required_with:trip_durations.*.prices|integer',
            'trip_durations.*.prices.*.pax_max' => 'required_with:trip_durations.*.prices|integer',
            'trip_durations.*.prices.*.price_per_pax' => 'required_with:trip_durations.*.prices|numeric',
            'trip_durations.*.prices.*.status' => 'required_with:trip_durations.*.prices|in:Aktif,Non Aktif',

            // Validasi untuk additional fees
            'additional_fees' => 'sometimes|array',
            'additional_fees.*.fee_category' => 'required_with:additional_fees|string',
            'additional_fees.*.price' => 'required_with:additional_fees|numeric',
            'additional_fees.*.region' => 'required_with:additional_fees|in:Domestic,Overseas',
            'additional_fees.*.unit' => 'required_with:additional_fees|in:per_pax,per_5pax,per_day,per_day_guide',
            'additional_fees.*.pax_min' => 'required_with:additional_fees|integer',
            'additional_fees.*.pax_max' => 'required_with:additional_fees|integer',
            'additional_fees.*.day_type' => 'required_with:additional_fees|in:Weekday,Weekend',
            'additional_fees.*.is_required' => 'required_with:additional_fees|boolean',
            'additional_fees.*.status' => 'required_with:additional_fees|in:Aktif,Non Aktif',

            // Validasi untuk surcharge
            'surcharge' => 'sometimes|array',
            'surcharge.*.season' => 'required_with:surcharge|string',
            'surcharge.*.start_date' => 'required_with:surcharge|date',
            'surcharge.*.end_date' => 'required_with:surcharge|date',
            'surcharge.*.surcharge_price' => 'required_with:surcharge|numeric',
            'surcharge.*.status' => 'required_with:surcharge|in:Aktif,Non Aktif',
        ];
    }
}
