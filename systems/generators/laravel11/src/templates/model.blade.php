namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;{{ $uses }}

class {{ $modelName }} extends {{ $extends }}
{
    use SoftDeletes;{{ $traits }}

    protected $fillable = [
        {!! $fillable !!}
    ];
{!! $casts !!}{!! $relationships !!}{!! $methods !!}
}