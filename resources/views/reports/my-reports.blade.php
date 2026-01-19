@extends('template.temp')

@section('content')
<!-- Breadcrumb Begin -->
<div class="breadcrumb-option set-bg" data-setbg="{{ asset('garasi62/img/breadcrumb-bg.jpg') }}">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 text-center">
                <div class="breadcrumb__text">
                    <h2>Laporan Saya</h2>
                    <div class="breadcrumb__links">
                        <a href="{{ route('home') }}"><i class="fa fa-home"></i> Home</a>
                        <span>Laporan Saya</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Breadcrumb End -->

<!-- Reports Section Begin -->
<section class="spad" style="padding: 60px 0; background: #f8f9fa;">
    <div class="container">
        <!-- Stats Cards -->
        <div class="row mb-5">
            <div class="col-lg-3 col-md-6 mb-4">
                <div style="background: #fff; padding: 25px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                    <div style="font-size: 36px; color: #f59e0b; margin-bottom: 10px;">
                        <i class="fa fa-hourglass-half"></i>
                    </div>
                    <div style="font-size: 32px; font-weight: 800; color: #1a1a1a; margin-bottom: 5px;">{{ $stats['pending'] }}</div>
                    <div style="font-size: 14px; color: #6b7280; font-weight: 600;">Menunggu Review</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div style="background: #fff; padding: 25px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                    <div style="font-size: 36px; color: #3b82f6; margin-bottom: 10px;">
                        <i class="fa fa-eye"></i>
                    </div>
                    <div style="font-size: 32px; font-weight: 800; color: #1a1a1a; margin-bottom: 5px;">{{ $stats['reviewed'] }}</div>
                    <div style="font-size: 14px; color: #6b7280; font-weight: 600;">Sedang Ditinjau</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div style="background: #fff; padding: 25px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                    <div style="font-size: 36px; color: #10b981; margin-bottom: 10px;">
                        <i class="fa fa-check-circle"></i>
                    </div>
                    <div style="font-size: 32px; font-weight: 800; color: #1a1a1a; margin-bottom: 5px;">{{ $stats['resolved'] }}</div>
                    <div style="font-size: 14px; color: #6b7280; font-weight: 600;">Selesai</div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-4">
                <div style="background: #fff; padding: 25px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center;">
                    <div style="font-size: 36px; color: #dc2626; margin-bottom: 10px;">
                        <i class="fa fa-flag"></i>
                    </div>
                    <div style="font-size: 32px; font-weight: 800; color: #1a1a1a; margin-bottom: 5px;">{{ $stats['total'] }}</div>
                    <div style="font-size: 14px; color: #6b7280; font-weight: 600;">Total Laporan</div>
                </div>
            </div>
        </div>

        <!-- Info Box -->
        <div class="row mb-4">
            <div class="col-12">
                <div style="background: linear-gradient(135deg,rgb(255, 255, 255),rgb(255, 255, 255)); color: #fff; padding: 20px; border-radius: 5px; margin-bottom: 30px;">
                    <h5 style="margin: 0 0 10px 0; font-weight: 700;">
                        <i class="fa fa-info-circle"></i> Informasi Proses Laporan
                    </h5>
                    <div style="font-size: 14px; line-height: 1.8;">
                        <p style="margin: 0;"><strong>1. Laporan Dikirim</strong> - Laporan Anda telah masuk ke sistem dan menunggu review admin</p>
                        <p style="margin: 5px 0 0 0;"><strong>2. Review Admin</strong> - Admin akan meninjau laporan dalam 1-3 hari kerja</p>
                        <p style="margin: 5px 0 0 0;"><strong>3. Update Status</strong> - Status laporan akan diperbarui setelah admin selesai meninjau</p>
                        <p style="margin: 5px 0 0 0;"><strong>4. Tindak Lanjut</strong> - Admin akan mengambil tindakan sesuai hasil review (Selesai/Ditolak)</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Reports List -->
        <div class="row">
            <div class="col-12">
                <div style="background: #fff; padding: 30px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
                    <h4 style="margin: 0 0 25px 0; font-weight: 800; color: #1a1a1a;">
                        <i class="fa fa-list"></i> Daftar Laporan Saya
                    </h4>

                    @if($reports->count() > 0)
                        <div class="table-responsive">
                            <table class="table" style="margin: 0;">
                                <thead style="background: #f8f9fa;">
                                    <tr>
                                        <th style="padding: 12px; font-weight: 700; color: #1a1a1a;">Tanggal</th>
                                        <th style="padding: 12px; font-weight: 700; color: #1a1a1a;">Mobil</th>
                                        <th style="padding: 12px; font-weight: 700; color: #1a1a1a;">Alasan</th>
                                        <th style="padding: 12px; font-weight: 700; color: #1a1a1a;">Status</th>
                                        <th style="padding: 12px; font-weight: 700; color: #1a1a1a;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reports as $report)
                                    <tr style="border-bottom: 1px solid #e5e7eb;">
                                        <td style="padding: 15px;">
                                            <div>
                                                <strong style="color: #1a1a1a;">{{ $report->created_at->format('d M Y') }}</strong><br>
                                                <small style="color: #6b7280;">{{ $report->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td style="padding: 15px;">
                                            @if($report->car)
                                                <div>
                                                    <strong style="color: #1a1a1a;">{{ strtoupper($report->car->brand) }} {{ $report->car->nama }}</strong><br>
                                                    <a href="{{ route('car.details', $report->car->id) }}" target="_blank" style="color: #3b82f6; font-size: 12px;">
                                                        <i class="fa fa-external-link"></i> Lihat Mobil
                                                    </a>
                                                </div>
                                            @else
                                                <span style="color: #9ca3af;">Mobil tidak ditemukan</span>
                                            @endif
                                        </td>
                                        <td style="padding: 15px;">
                                            <span style="background: #dbeafe; color: #1e40af; padding: 5px 12px; border-radius: 5px; font-size: 12px; font-weight: 600;">
                                                {{ $report->reason_label }}
                                            </span>
                                        </td>
                                        <td style="padding: 15px;">
                                            @if($report->status == 'pending')
                                                <span style="background: #fef3c7; color: #92400e; padding: 5px 12px; border-radius: 5px; font-size: 12px; font-weight: 600;">
                                                    <i class="fa fa-hourglass-half"></i> Menunggu Review
                                                </span>
                                            @elseif($report->status == 'reviewed')
                                                <span style="background: #dbeafe; color: #1e40af; padding: 5px 12px; border-radius: 5px; font-size: 12px; font-weight: 600;">
                                                    <i class="fa fa-eye"></i> Sedang Ditinjau
                                                </span>
                                            @elseif($report->status == 'resolved')
                                                <span style="background: #d1fae5; color: #065f46; padding: 5px 12px; border-radius: 5px; font-size: 12px; font-weight: 600;">
                                                    <i class="fa fa-check-circle"></i> Selesai
                                                </span>
                                            @else
                                                <span style="background: #f3f4f6; color: #374151; padding: 5px 12px; border-radius: 5px; font-size: 12px; font-weight: 600;">
                                                    <i class="fa fa-times-circle"></i> Ditolak
                                                </span>
                                            @endif
                                        </td>
                                        <td style="padding: 15px;">
                                            <button type="button" class="btn btn-sm" style="background: #3b82f6; color: #fff; border-radius: 5px; border: none; padding: 6px 15px;" data-toggle="modal" data-target="#reportModal{{ $report->id }}">
                                                <i class="fa fa-eye"></i> Detail
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Modal Detail Report -->
                                    <div class="modal fade" id="reportModal{{ $report->id }}" tabindex="-1" role="dialog">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content" style="border-radius: 5px;">
                                                <div class="modal-header" style="background: #3b82f6; color: #fff; border-radius: 5px 5px 0 0;">
                                                    <h5 class="modal-title" style="font-weight: 700;">
                                                        <i class="fa fa-flag"></i> Detail Laporan
                                                    </h5>
                                                    <button type="button" class="close" data-dismiss="modal" style="color: #fff; opacity: 1;">
                                                        <span>&times;</span>
                                                    </button>
                                                </div>
                                                <div class="modal-body" style="padding: 25px;">
                                                    <div style="margin-bottom: 20px;">
                                                        <h6 style="font-weight: 700; color: #1a1a1a; margin-bottom: 10px;">Informasi Laporan</h6>
                                                        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px;">
                                                            <p style="margin: 5px 0;"><strong>Tanggal:</strong> {{ $report->created_at->format('d M Y, H:i') }}</p>
                                                            <p style="margin: 5px 0;"><strong>Mobil:</strong> {{ strtoupper($report->car->brand ?? 'N/A') }} {{ $report->car->nama ?? '' }}</p>
                                                            <p style="margin: 5px 0;"><strong>Alasan:</strong> {{ $report->reason_label }}</p>
                                                            <p style="margin: 5px 0;">
                                                                <strong>Status:</strong> 
                                                                @if($report->status == 'pending')
                                                                    <span style="background: #fef3c7; color: #92400e; padding: 3px 10px; border-radius: 5px; font-size: 12px;">Menunggu Review</span>
                                                                @elseif($report->status == 'reviewed')
                                                                    <span style="background: #dbeafe; color: #1e40af; padding: 3px 10px; border-radius: 5px; font-size: 12px;">Sedang Ditinjau</span>
                                                                @elseif($report->status == 'resolved')
                                                                    <span style="background: #d1fae5; color: #065f46; padding: 3px 10px; border-radius: 5px; font-size: 12px;">Selesai</span>
                                                                @else
                                                                    <span style="background: #f3f4f6; color: #374151; padding: 3px 10px; border-radius: 5px; font-size: 12px;">Ditolak</span>
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>

                                                    <div style="margin-bottom: 20px;">
                                                        <h6 style="font-weight: 700; color: #1a1a1a; margin-bottom: 10px;">Pesan Laporan</h6>
                                                        <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; border-left: 4px solid #3b82f6;">
                                                            <p style="margin: 0; color: #374151; line-height: 1.6;">{{ $report->message }}</p>
                                                        </div>
                                                    </div>

                                                    @if($report->admin_notes)
                                                    <div style="margin-bottom: 20px;">
                                                        <h6 style="font-weight: 700; color: #1a1a1a; margin-bottom: 10px;">
                                                            <i class="fa fa-comment"></i> Catatan Admin
                                                        </h6>
                                                        <div style="background: #fef3c7; padding: 15px; border-radius: 5px; border-left: 4px solid #f59e0b;">
                                                            <p style="margin: 0; color: #92400e; line-height: 1.6;">{{ $report->admin_notes }}</p>
                                                        </div>
                                                    </div>
                                                    @endif

                                                    @if($report->reviewer)
                                                    <div>
                                                        <p style="margin: 0; font-size: 13px; color: #6b7280;">
                                                            <strong>Ditinjau oleh:</strong> {{ $report->reviewer->name }} 
                                                            <small>({{ $report->reviewed_at->format('d M Y, H:i') }})</small>
                                                        </p>
                                                    </div>
                                                    @endif
                                                </div>
                                                <div class="modal-footer" style="border-top: 1px solid #e5e7eb; padding: 15px 25px;">
                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal" style="border-radius: 5px;">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-4">
                            {{ $reports->links() }}
                        </div>
                    @else
                        <div style="text-align: center; padding: 60px 20px;">
                            <div style="font-size: 64px; color: #d1d5db; margin-bottom: 20px;">
                                <i class="fa fa-flag"></i>
                            </div>
                            <h5 style="color: #6b7280; margin-bottom: 10px;">Belum Ada Laporan</h5>
                            <p style="color: #9ca3af; margin-bottom: 20px;">Anda belum pernah melaporkan mobil apapun.</p>
                            <a href="{{ route('cars') }}" class="btn" style="background: #3b82f6; color: #fff; border-radius: 5px; padding: 10px 25px;">
                                <i class="fa fa-car"></i> Jelajahi Mobil
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Reports Section End -->
@endsection

