<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\WebhookProcessError
 *
 * @property int $id
 * @property int $process_id
 * @property string|null $line
 * @property string|null $file
 * @property string $message
 * @property string|null $code
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcessError newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcessError newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcessError query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcessError whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcessError whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcessError whereFile($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcessError whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcessError whereLine($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcessError whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcessError whereProcessId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcessError whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property-read \App\WebhookProcess $process
 */
class WebhookProcessError extends Model
{
    protected $table = "webhook_process_errors";
    protected $fillable = [
        "process_id", "line", "file", "message", "code"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function process()
    {
        return $this->belongsTo(WebhookProcess::class, 'process_id');
    }
}
