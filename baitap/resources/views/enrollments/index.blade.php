@extends('layouts.master')

@section('content')
<div class="container mt-4">
    <h2>Danh sách Đăng ký Khóa học</h2>
    
    <div class="mb-3">
        <a href="{{ route('enrollments.create') }}" class="btn btn-success">Đăng ký cho Học viên mới</a>
    </div>

    <table class="table table-bordered">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Tên Học Viên</th>
                <th>Khóa Học</th>
                <th>Ngày Đăng Ký</th>
                <th>Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($enrollments as $enrollment)
                <tr>
                    <td>{{ $enrollment->id }}</td>
                    <td>{{ $enrollment->student->name ?? 'N/A' }} <br><small class="text-muted">{{ $enrollment->student->email ?? '' }}</small></td>
                    <td>{{ $enrollment->course->name ?? 'N/A' }}</td>
                    <td>{{ $enrollment->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <form action="{{ route('enrollments.destroy', $enrollment->id) }}" method="POST" onsubmit="return confirm('Hủy đăng ký của học viên này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Hủy đăng ký</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $enrollments->links() }}
</div>
@endsection