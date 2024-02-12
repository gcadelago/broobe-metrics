<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MetricHistoryRun extends Model
{
    use HasFactory;

    protected $table = 'metric_history_runs';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'url',
        'accessibility_metric',
        'pwa_metric',
        'performance_metric',
        'seo_metric',
        'best_practices_metric',
        'strategy_id'
    ];

    public function strategy(): BelongsTo
    {
        return $this->belongsTo(Strategy::class, 'strategy_id');
    }
}
