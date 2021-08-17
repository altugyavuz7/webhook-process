<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\WebhookProcess
 *
 * @property int $id
 * @property string $type
 * @property string $scope
 * @property int $bigcommerce_id
 * @property bool $error
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcess newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcess newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcess query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcess whereBigcommerceId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcess whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcess whereError($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcess whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcess whereScope($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcess whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\WebhookProcess whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class WebhookProcess extends Model
{
    protected $table = "webhook_processes";
    protected $fillable = [
        "type", "scope", "bigcommerce_id", "error"
    ];
    protected $casts = [
        "error" => "boolean"
    ];
}
