<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'full_name' => $this->full_name,
            'email' => $this->email,
            'phone' => $this->phone,
            'designation' => $this->designation,
            'salary' => number_format((float) $this->salary, 2, '.', ''),
            'status' => $this->status,
            'joined_date' => $this->joined_date?->format('Y-m-d'),
            'department' => new DepartmentResource($this->whenLoaded('department')),
            'projects' => $this->whenLoaded('projects', function () {
                return $this->projects->map(fn ($p) => [
                    'id' => $p->id,
                    'name' => $p->name,
                    'role' => $p->pivot->role ?? 'Member',
                ]);
            }),
            'created_at' => $this->created_at?->toDateTimeString(),
        ];
    }
}
