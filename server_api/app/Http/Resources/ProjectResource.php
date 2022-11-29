<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\TaskResource;

class ProjectResource extends JsonResource
{
    public static $format = 'default';
    public function toArray($request)
    {
        switch (ProjectResource::$format) {
            case 'withTasks':
                return [
                    "id" => $this->id,
                    "name" => $this->name,
                    "responsible_id" => $this->responsible_id,
                    "responsible_name" => $this->responsible->name,
                    "status" => $this->status,
                    "status_name" => $this->statusName,
                    "preview_start_date" => $this->preview_start_date,
                    "preview_end_date" => $this->preview_end_date,
                    "real_start_date" => $this->real_start_date,
                    "real_end_date" => $this->real_end_date,
                    "total_hours" => $this->total_hours,
                    "billed" => $this->billed,
                    "total_price" => $this->total_price,
                    "total_tasks" => $this->totalTasks,
                    "tasks" => TaskResource::collection($this->tasks->sortByDesc('id'))
                ];
            default:
                return [
                    "id" => $this->id,
                    "name" => $this->name,
                    "responsible_id" => $this->responsible_id,
                    "responsible_name" => $this->responsible->name,
                    "status" => $this->status,
                    "status_name" => $this->statusName,
                    "preview_start_date" => $this->preview_start_date,
                    "preview_end_date" => $this->preview_end_date,
                    "real_start_date" => $this->real_start_date,
                    "real_end_date" => $this->real_end_date,
                    "total_hours" => $this->total_hours,
                    "billed" => $this->billed,
                    "total_price" => $this->total_price,
                    "total_tasks" => $this->totalTasks,
                ];
        }
    }
}
