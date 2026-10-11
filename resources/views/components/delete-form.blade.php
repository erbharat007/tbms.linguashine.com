@props(['action', 'message' => 'Are you sure you want to delete this record?'])

<form method="POST" action="{{ $action }}" onsubmit="return confirm(@js($message));" class="d-inline">
    @csrf
    @method('DELETE')
    <button type="submit" class="action-icon action-danger" title="Delete">
        <i class="fas fa-trash"></i>
    </button>
</form>
