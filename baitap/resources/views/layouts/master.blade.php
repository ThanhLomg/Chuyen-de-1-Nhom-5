<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hệ thống Quản lý Khóa học (LMS)</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        body {
            background-color: #f4f6f9; /* Màu nền xám nhạt cho dịu mắt */
        }
        .sidebar {
            height: 100vh;
            background-color: #212529; /* Màu tối cho sidebar */
            padding-top: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.1);
        }
        .sidebar a {
            color: #c2c7d0;
            text-decoration: none;
            display: block;
            padding: 12px 20px;
            margin: 0 10px 5px 10px;
            border-radius: 5px;
            transition: all 0.3s;
        }
        .sidebar a:hover, .sidebar a.active {
            background-color: #0d6efd;
            color: white;
        }
        .main-content {
            padding: 30px;
            height: 100vh;
            overflow-y: auto;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-2 d-none d-md-block sidebar">
                <h4 class="text-white text-center mb-4 fw-bold">LMS Admin</h4>
                <ul class="list-unstyled">
                    <li><a href="{{ route('dashboard') }}">📊 Dashboard</a></li>
                    <li><a href="{{ route('courses.index') }}">📚 Khóa học</a></li>
                    <li><a href="{{ route('enrollments.index') }}">🎓 Học viên</a></li>
                </ul>
            </nav>

            <main class="col-md-10 main-content">
                
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                        <strong>Thành công!</strong> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                
                @if(session('danger'))
                    <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
                        <strong>Lỗi!</strong> {{ session('danger') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')

            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>