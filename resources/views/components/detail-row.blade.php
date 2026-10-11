@props(['label'])

<div class="detail-row border-bottom py-2 px-3">
    <div class="detail-row-label text-muted">{{ $label }}</div>
    <div class="detail-row-value">{{ $slot }}</div>
</div>
