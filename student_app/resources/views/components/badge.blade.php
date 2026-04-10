@props(['status'])

@if($status == 'published')
    <span class="badge bg-success">Xuất bản</span>
@else
    <span class="badge bg-secondary">Bản nháp</span>
@endif