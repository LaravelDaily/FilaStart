use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;{{ $uses }}

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{{ $tableName }}', static function (Blueprint $table) {
{!! $tableColumns !!}
        });
    }
};
