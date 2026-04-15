<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreTicketRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'regex:/^\+[1-9]\d{1,14}$/'],
            'email' => ['required', 'email:rfc', 'max:191'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
            'attachment' => ['nullable', 'file', 'max:10240'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.regex' => 'Номер телефону повинен бути у форматі E.164.',
        ];
    }

    /**
     * @return array{name: string, phone: string, email: string, subject: string, message: string}
     */
    public function ticketData(): array
    {
        /** @var array{name: string, phone: string, email: string, subject: string, message: string} $data */
        $data = $this->safe()->only([
            'name',
            'phone',
            'email',
            'subject',
            'message',
        ]);

        return $data;
    }
}
