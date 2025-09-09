@extends('Backend.Layout.MasterLayout')

@section('Content')

    <div class="container mt-4">
        <div class="row align-items-center g-2">

            <!-- Search -->
            <div class="col-12 col-md-8 col-lg-5">
                <form action="" method="get">
                    <div class="input-group">
                        <input type="date" class="form-control">
                        <input type="text" class="form-control" placeholder="হিসাবের ধরন দিয়ে সার্চ করুন…">
                        <button class="btn btn-outline-success" type="submit">
                            <i class="fa-solid fa-magnifying-glass"></i> অনুসন্ধান
                        </button>
                    </div>
                </form>
            </div>

            <!-- Create Button -->
            <div class="col-12 col-md-4 col-lg-2 ms-md-auto text-md-end">
                <button class="btn btn-success w-100 w-md-auto" data-bs-toggle="modal" data-bs-target="#createFinanceModal">
                    <i class="fa-solid fa-plus me-1"></i> নতুন হিসাবপত্র
                </button>
            </div>

        </div>
    </div>

    <div class="card table-card shadow-sm mt-3">
        <div class="card-header text-white text-center">
            <i class="fas fa-file-invoice-dollar"></i> আর্থিক হিসাবপত্রের তালিকা
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead>
                        <tr class="text-center">
                            <th>ক্রমিক নং</th>
                            <th>হিসাবের ধরন</th>
                            <th>তারিখ</th>
                            <th>অ্যাকশন</th>
                        </tr>
                    </thead>
                    <tbody>

                        @foreach ($finances as $finance)
                            <tr class="text-center">
                                <td data-label="ক্রমিক নং">{{ $finance->id }}</td>
                                <td data-label="হিসাবের ধরন">{{ $finance->title }}</td>
                                <td data-label="তারিখ">{{ $finance->date }}</td>
                                <td data-label="অ্যাকশন">
                                    <div class="d-flex flex-row justify-content-center gap-2">
                                        <a href="{{ route('finance.sheet.download', $finance->pdf_path) }}" class="btn action-btn-info">
                                            <i class="fa-solid fa-download"></i>
                                        </a>
                                        <button class="btn action-btn-success" data-bs-toggle="modal"
                                            data-bs-target="#editFinanceModal{{ $finance->id }}" data-id="{{ $finance->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="btn action-btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#deleteFinanceModal{{ $finance->id }}" data-id="{{ $finance->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>

                            <!-- Edit Modal -->
                            <div class="modal fade" id="editFinanceModal{{ $finance->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-success text-white">
                                            <h6 class="modal-title">হিসাবপত্র সম্পাদনা</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('finance.sheet.update', $finance->id) }}" method="POST" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-body">
                                                <div class="row g-3">
                                                    <div class="col-12">
                                                        <label class="form-label @error('pdf_path') text-danger @enderror">
                                                            @error('pdf_path')
                                                                {{ $message }}
                                                            @else
                                                                নতুন রসিদ/ডকুমেন্ট আপলোড করুন
                                                            @enderror
                                                        </label>
                                                        <input type="file" name="pdf_path" class="form-control" accept="image/*,application/pdf">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label @error('title') text-danger @enderror">
                                                            @error('title')
                                                                {{ $message }}
                                                            @else
                                                                হিসাবের ধরন
                                                            @enderror
                                                        </label>
                                                        <input type="text" name="title" class="form-control" value="{{ $finance->title }}">
                                                    </div>
                                                    <div class="col-12">
                                                        <label class="form-label @error('date') text-danger @enderror">
                                                            @error('date')
                                                                {{ $message }}
                                                            @else
                                                                তারিখ
                                                            @enderror
                                                        </label>
                                                        <input type="date" name="date" class="form-control" value="{{ $finance->date }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                                <button type="submit" name="submit" class="btn btn-success">আপডেট করুন</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Delete Modal -->
                            <div class="modal fade" id="deleteFinanceModal{{ $finance->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-danger text-white">
                                            <h6 class="modal-title">হিসাবপত্র মুছুন</h6>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <form action="{{ route('finance.sheet.destroy', $finance->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <input type="hidden" name="id" id="{{ $finance->id }}">
                                            <div class="modal-body text-center">
                                                <p>আপনি কি নিশ্চিত যে আপনি এই হিসাবপত্রটি মুছে ফেলতে চান?</p>
                                                <p class="text-danger">এই কাজটি পূর্বাবস্থায় ফেরানো যাবে না।</p>
                                            </div>
                                            <div class="modal-footer justify-content-center">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                                                <button type="submit" name="submit" class="btn btn-danger">মুছে ফেলুন</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="createFinanceModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">নতুন হিসাবপত্র যোগ করুন</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('finance.sheet.create') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">হিসাবের ধরন</label>
                                <input type="text" name="title" class="form-control" required>
                            </div>

                            <!-- ইমেজ আপলোড (Optional) -->
                            <div class="col-12">
                                <label class="form-label">রসিদ/ডকুমেন্ট</label>
                                <input type="file" name="pdf_path" class="form-control" accept="image/*,application/pdf">
                            </div>

                            <div class="col-6">
                                <label class="form-label">তারিখ</label>
                                <input type="date" name="date" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" data-bs-dismiss="modal">বাতিল</button>
                        <button type="submit" name="submit" class="btn btn-success">সংরক্ষণ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection