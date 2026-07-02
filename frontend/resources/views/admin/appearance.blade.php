@extends('layouts.admin')
@section('title', 'Kustomisasi Tampilan')
@section('page_title', 'Kustomisasi Tampilan Website')

@section('content')
<form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
@csrf
<div class="grid-2" style="gap:var(--space-6);">
    <div>
        <div class="card" style="margin-bottom:var(--space-5);">
            <h4 style="margin-bottom:var(--space-5);"><i class="bi bi-palette"></i> Color Scheme</h4>
            @foreach([['label'=>'Primary (Dark Base)','var'=>'--color-primary','val'=>'#1C1C1E'],['label'=>'Accent (CTA/Highlight)','var'=>'--color-accent','val'=>'#FF6B2C'],['label'=>'Accent 2 (Success/Progress)','var'=>'--color-accent-2','val'=>'#A8E63D'],['label'=>'Background','var'=>'--color-bg','val'=>'#FFFFFF'],['label'=>'Surface','var'=>'--color-surface','val'=>'#F4F4F5']] as $c)
            <div class="color-picker-group">
                <div class="color-swatch" style="background:{{ $c['val'] }};"><input type="color" value="{{ $c['val'] }}"></div>
                <div><strong style="font-size:var(--fs-sm);">{{ $c['label'] }}</strong><br><span class="text-muted" style="font-size:var(--fs-xs);">{{ $c['val'] }}</span></div>
            </div>
            @endforeach
        </div>

        <div class="card" style="margin-bottom:var(--space-5);">
            <h4 style="margin-bottom:var(--space-5);">🔤 Typography</h4>
            <div class="form-group"><label class="form-label">Font Heading</label>
                <select class="form-input form-select"><option selected>Barlow</option><option>Inter</option><option>Poppins</option><option>Montserrat</option><option>Oswald</option><option>Roboto</option></select>
            </div>
            <div class="form-group"><label class="form-label">Font Body</label>
                <select class="form-input form-select"><option>Barlow</option><option selected>Inter</option><option>Poppins</option><option>Open Sans</option><option>Lato</option><option>Roboto</option></select>
            </div>
        </div>

        <div class="card" style="margin-bottom:var(--space-5);">
            <h4 style="margin-bottom:var(--space-5);"><i class="bi bi-image"></i> Branding</h4>
            <div class="form-group"><label class="form-label">Upload Logo</label><input type="file" class="form-input" accept="image/*" style="padding:10px;"></div>
            <div class="form-group"><label class="form-label">Upload Favicon</label><input type="file" class="form-input" accept="image/*" style="padding:10px;"></div>
            <div class="form-group"><label class="form-label">Gambar Hero Homepage</label><input type="file" class="form-input" accept="image/*" style="padding:10px;"></div>
        </div>

        <div class="card" style="margin-bottom:var(--space-5);">
            <h4 style="margin-bottom:var(--space-4);">🌓 Mode Tampilan</h4>
            <div class="flex-between"><span style="font-size:var(--fs-sm);">Dark Mode untuk Website Publik</span><label class="toggle"><input type="checkbox"><span class="toggle-slider"></span></label></div>
        </div>

        <div style="display:flex;gap:var(--space-3);">
            <button type="submit" class="btn btn-primary">Terapkan Perubahan</button>
            <button class="btn btn-outline">Reset ke Default</button>
        </div>
    </div>

    {{-- PREVIEW --}}
    <div>
        <div class="card" style="position:sticky;top:100px;">
            <h4 style="margin-bottom:var(--space-4);"><i class="bi bi-eye-fill"></i> Preview</h4>
            <div style="border:1px solid var(--color-border);border-radius:var(--radius-lg);overflow:hidden;">
                <div style="background:var(--color-primary);padding:var(--space-3) var(--space-4);display:flex;justify-content:space-between;align-items:center;">
                    <span style="color:var(--color-primary);font-weight:800;font-size:var(--fs-sm);"><i class="bi bi-lightning-charge-fill"></i> Fitness Vals</span>
                    <div style="display:flex;gap:var(--space-2);">
                        <span style="color:rgba(255,255,255,0.6);font-size:var(--fs-xs);">Training</span>
                        <span style="color:rgba(255,255,255,0.6);font-size:var(--fs-xs);">Membership</span>
                    </div>
                </div>
                <div style="padding:var(--space-8) var(--space-6);background:var(--color-bg);">
                    <div style="font-family:var(--font-heading);font-size:var(--fs-xl);font-weight:800;margin-bottom:var(--space-3);">TRANSFORM YOUR BODY</div>
                    <p style="font-size:var(--fs-xs);color:var(--color-text-secondary);margin-bottom:var(--space-4);">Platform kebugaran premium.</p>
                    <div style="display:flex;gap:var(--space-2);">
                        <span style="background:var(--color-accent);color:#fff;padding:6px 16px;border-radius:var(--radius-md);font-size:var(--fs-xs);font-weight:600;">Join Now</span>
                        <span style="border:1px solid var(--color-border);padding:6px 16px;border-radius:var(--radius-md);font-size:var(--fs-xs);">Learn More</span>
                    </div>
                </div>
                <div style="display:flex;justify-content:center;gap:var(--space-6);padding:var(--space-4);background:var(--color-surface);">
                    <div style="text-align:center;"><span style="font-weight:800;color:var(--color-primary);">150</span><br><span style="font-size:10px;color:var(--color-text-muted);">MEMBERS</span></div>
                    <div style="text-align:center;"><span style="font-weight:800;">8</span><br><span style="font-size:10px;color:var(--color-text-muted);">TRAINERS</span></div>
                </div>
            </div>
            <p class="text-center text-muted" style="font-size:var(--fs-xs);margin-top:var(--space-3);">Preview akan berubah real-time saat kamu mengubah pengaturan.</p>
        </div>
    </div>
</div>
</form>
@endsection
