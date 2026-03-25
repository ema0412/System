<?php

namespace App\Http\Requests;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class AttendanceCorrectionRequestForm extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'clock_in_at' => ['required', 'date_format:H:i'],
            'clock_out_at' => ['required', 'date_format:H:i'],
            'note' => ['required', 'string'],
            'breaks' => ['nullable', 'array'],
            'breaks.*.start' => ['nullable', 'date_format:H:i'],
            'breaks.*.end' => ['nullable', 'date_format:H:i'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator): void {
            $in = Carbon::createFromFormat('H:i', (string) $this->input('clock_in_at'));
            $out = Carbon::createFromFormat('H:i', (string) $this->input('clock_out_at'));

            if ($in->gte($out)) {
                $validator->errors()->add('clock_in_at', '出勤時間もしくは退勤時間が不適切な値です');
            }

            foreach ((array) $this->input('breaks', []) as $index => $break) {
                if (empty($break['start']) || empty($break['end'])) {
                    continue;
                }

                $start = Carbon::createFromFormat('H:i', $break['start']);
                $end = Carbon::createFromFormat('H:i', $break['end']);

                if ($start->lt($in) || $start->gt($out)) {
                    $validator->errors()->add("breaks.$index.start", '休憩時間が不適切な値です');
                }

                if ($end->gt($out)) {
                    $validator->errors()->add("breaks.$index.end", '休憩時間もしくは退勤時間が不適切な値です');
                }
            }
        });
    }

    public function messages(): array
    {
        return [
            'note.required' => '備考を記入してください',
        ];
    }
}
