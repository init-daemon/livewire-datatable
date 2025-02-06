<tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
    @foreach($columns as $column)
    <td class="px-6 py-4 whitespace-nowrap text-sm">
        @if($column === 'status')
        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full
                    {{ $row[$column] === 'Active' ? 'bg-green-100 text-green-800' :
                       ($row[$column] === 'Pending' ? 'bg-yellow-100 text-yellow-800' :
                        'bg-red-100 text-red-800') }}">
            {{ $row[$column] }}
        </span>
        @elseif(in_array($column, ['rate', 'balance', 'deposit']))
        <span class="{{ $row[$column] < 0 ? 'text-red-600' : 'text-gray-900' }} font-medium">
            {{ number_format($row[$column], 2) }} $
        </span>
        @elseif($column === 'created_at')
        <span class="text-gray-500">
            {{ \Carbon\Carbon::parse($row[$column])->format('d/m/Y H:i') }}
        </span>
        @elseif($column === 'user_id')
        <span class="text-gray-500 font-medium">#{{ $row[$column] }}</span>
        @else
        <span class="text-gray-900">{{ $row[$column] }}</span>
        @endif
    </td>
    @endforeach
</tr>