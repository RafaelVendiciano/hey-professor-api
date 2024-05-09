<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\hasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'question',
        'user_id',
        'status'
    ];


    public function user():BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function scopePublished(Builder $query):Builder {
        return $query->where('status', '=', 'published');
    }

    public function scopeSearch(Builder $query, string $search = null):Builder {
        return $query->when($search, fn(Builder $query) => $query->where('question', 'like', "%{$search}%"));
    }

    public function votes(): hasMany {
       return $this->hasMany(Vote::class);
    }
}
