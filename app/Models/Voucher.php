<?php

namespace App\Models;

use App\Traits\ApiTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property string invoice_code
 * @property string invoice_serie
 * @property int invoice_correlative
 * @property string invoice_type_currency
 * @property string issuer_name
 * @property string issuer_document_type
 * @property string issuer_document_number
 * @property string receiver_name
 * @property string receiver_document_type
 * @property string receiver_document_number
 * @property float total_amount
 * @property string xml_content
 * @property int user_id
 * @property-read User $user
 * @property-read Collection|User[] $lines
 * @property Carbon|null created_at
 * @property Carbon|null updated_at
 * @property Carbon|null deleted_at
 * @method static Builder included()
 * @method static Builder code()
 * @method static Builder betweenDates()
 * @method static Builder sort()
 * @mixin Builder
 */
class Voucher extends Model
{
    use HasFactory;
    use HasUuids;
    use SoftDeletes;
    use ApiTrait;

    const STATUS_STORE = true;
    const STATUS_REJECTED = false;

    protected $fillable = [
        'invoice_code',
        'invoice_serie',
        'invoice_correlative',
        'invoice_type_currency',
        'issuer_name',
        'issuer_document_type',
        'issuer_document_number',
        'receiver_name',
        'receiver_document_type',
        'receiver_document_number',
        'total_amount',
        'xml_content',
        'user_id',
    ];

    protected $allowFilters = [
        'invoice_serie',
        'invoice_correlative',
        'start_date',
        'end_date',
    ];

    protected $allowIncludeds = [
        'user',
        'lines',
    ];

    protected $allowSorts = [
        'invoice_serie',
        'invoice_correlative',
        'total_amount',
        'created_at',
    ];

    protected $casts = [
        'total_amount' => 'float',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * @return HasMany
     */
    public function lines(): HasMany
    {
        return $this->hasMany(VoucherLine::class);
    }
}
