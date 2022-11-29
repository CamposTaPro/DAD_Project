<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProjectResource;

class TaskResource extends JsonResource
{
    public static $format = "default";
    public function toArray($request)
    {
        switch (TaskResource::$format) {
            case 'detailed':
                return [
                    'id' => $this->id,
                    'description' => $this->description,
                    'completed' => $this->completed ? true : false,
                    'total_hours' => $this->total_hours,
                    'owner_id' => $this->owner_id,
                    'owner' => new UserResource($this->owner),
                    'project_id' => $this->project_id,
                    'project' =>  new ProjectResource($this->project),
                    'notes' => $this->notes
                ];
            case 'with-assigned-users':
                return [
                    'id' => $this->id,
                    'description' => $this->description,
                    'completed' => $this->completed ? true : false,
                    'total_hours' => $this->total_hours,
                    'owner_id' => $this->owner_id,
                    'owner' => new UserResource($this->owner),
                    'project_id' => $this->project_id,
                    'project' =>  new ProjectResource($this->project),
                    'assigned_users' => new UserResource($this->assignedUsers),
                    'notes' => $this->notes
                ];
            default:
                return [
                    'id' => $this->id,
                    'description' => $this->description,
                    'completed' => $this->completed ? true : false,
                    'total_hours' => $this->total_hours,
                    'owner_id' => $this->owner_id,
                    'owner_name' => $this->owner->name,
                    'project_id' => $this->project_id,
                    'project_name' => $this->project ? $this->project->name : '',
                    'notes' => $this->notes
                ];
        }
    }
}
