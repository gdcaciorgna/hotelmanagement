<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    private $statusColor;

    public function getStatusColor()
    {
        switch ($this->status) {
            case 'Available':
                return 'bg-success';
            case 'Cleaning':
                return 'bg-danger';
            case 'Unavailable':
                return 'bg-primary';
            default:
                return 'bg-secondary';
        }
    }

    public function getStatusFormatted()
    {
        switch ($this->status) {
            case 'Available':
                return 'Disponible';
            case 'Cleaning':
                return 'En limpieza';
            case 'Unavailable':
                return 'Ocupado';
            default:
                return 'Estado desconocido';
        }
    }

}
