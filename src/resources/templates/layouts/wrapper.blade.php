namespace {{ $original->namespace }};

use Illuminate\Support\Facades\Cache;

class {{ $original->name }}{{ $attribute_name }}AttributeWrapper extends \{{ $original->full_qualifier }}
{
    private $original;

    public function __construct(\{{ $original->full_qualifier }} $original)
    {
        $this->original = $original;
    }

    @yield('content')
}