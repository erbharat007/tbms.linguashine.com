@props(['action', 'message' => 'Are you sure you want to delete this record?'])

<form method="POST" action="{{ $action }}" onsubmit="return confirm(@js($message));" class="inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="text-sm font-medium text-red-600 transition hover:text-red-800">Delete</button>
</form>
