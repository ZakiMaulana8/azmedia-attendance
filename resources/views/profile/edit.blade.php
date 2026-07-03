@extends('layouts.app')
@section('title', 'Profil Saya')

@section('content')
<div class="px-md max-w-lg mx-auto pt-lg pb-4">

    {{-- Header --}}
    <section class="mb-lg">
        <h2 class="text-headline-lg-mobile font-bold text-on-surface">Profil Saya</h2>
        <p class="text-body-sm text-on-surface-variant">Informasi akun dan data magang</p>
    </section>

    {{-- Flash status --}}
    @if(session('status'))
        <div class="mb-lg p-md bg-primary-container text-on-primary-container rounded-xl
                    flex items-center gap-sm text-body-sm font-semibold">
            <span class="material-symbols-outlined text-[18px]" style="font-variation-settings:'FILL' 1">check_circle</span>
            {{ session('status') }}
        </div>
    @endif

    {{-- Avatar & identitas --}}
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-lg shadow-sm mb-lg
                flex flex-col items-center text-center gap-sm">
        <div class="w-20 h-20 rounded-full overflow-hidden border-4 border-primary-container shadow-sm">
            <img src="{{ $user->photo_url }}" alt="{{ $user->name }}"
                 class="w-full h-full object-cover">
        </div>
        <div>
            <p class="text-headline-md font-bold text-on-surface">{{ $user->name }}</p>
            <p class="text-body-sm text-on-surface-variant">{{ $user->email }}</p>
        </div>
        {{-- Role badge --}}
        @php
            $roleBadge = match($user->role) {
                'admin' => ['label' => 'Admin',            'class' => 'bg-tertiary-container text-on-tertiary-container'],
                'guru'  => ['label' => 'Guru Pembimbing',  'class' => 'bg-secondary-container text-on-secondary-container'],
                default => ['label' => 'Siswa Magang',     'class' => 'bg-primary-container text-on-primary-container'],
            };
        @endphp
        <span class="text-status-badge font-bold px-md py-1 rounded-full {{ $roleBadge['class'] }}">
            {{ $roleBadge['label'] }}
        </span>
        <p class="text-label-md text-outline">Login via Google</p>
    </div>

    {{-- Edit form (nama & telepon) --}}
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm mb-lg">
        <p class="text-label-md text-on-surface-variant uppercase tracking-wider mb-md">Edit Profil</p>

        <form method="POST" action="{{ route('profile.update') }}" class="space-y-md">
            @csrf
            @method('PATCH')

            <div class="space-y-xs">
                <label for="name" class="text-label-md text-on-surface-variant block">Nama Lengkap</label>
                <input id="name" type="text" name="name"
                       value="{{ old('name', $user->name) }}"
                       class="w-full h-touch-target px-md rounded-lg border border-outline-variant
                              bg-surface focus:border-primary focus:ring-1 focus:ring-primary
                              outline-none text-body-lg"
                       required>
                @error('name')<p class="text-body-sm text-error">{{ $message }}</p>@enderror
            </div>

            <div class="space-y-xs">
                <label for="email" class="text-label-md text-on-surface-variant block">Email</label>
                <div class="relative">
                    <input type="text" value="{{ $user->email }}" disabled
                           class="w-full h-touch-target px-md rounded-lg border border-outline-variant
                                  bg-surface-container text-on-surface-variant text-body-lg cursor-not-allowed">
                    <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-outline text-[18px]">lock</span>
                </div>
                <p class="text-label-md text-outline ml-1">Email dikelola oleh Google, tidak dapat diubah.</p>
            </div>

            <div class="space-y-xs">
                <label for="phone" class="text-label-md text-on-surface-variant block">Nomor Telepon</label>
                <input id="phone" type="tel" name="phone"
                       value="{{ old('phone', $user->phone) }}"
                       placeholder="Contoh: 081234567890"
                       class="w-full h-touch-target px-md rounded-lg border border-outline-variant
                              bg-surface focus:border-primary focus:ring-1 focus:ring-primary
                              outline-none text-body-lg">
                @error('phone')<p class="text-body-sm text-error">{{ $message }}</p>@enderror
            </div>

            <button type="submit"
                    class="w-full h-touch-target bg-primary text-on-primary font-bold rounded-lg
                           flex items-center justify-center gap-sm active:scale-95 transition-transform shadow-sm">
                <span class="material-symbols-outlined text-[18px]">save</span>
                Simpan Perubahan
            </button>
        </form>
    </div>

    {{-- Info magang (readonly, hanya untuk siswa) --}}
    @if($user->isSiswa())
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm mb-lg">
        <p class="text-label-md text-on-surface-variant uppercase tracking-wider mb-md">Data Magang</p>

        <div class="grid grid-cols-2 gap-md text-body-sm">
            <div>
                <p class="text-label-md text-outline uppercase mb-xs">Asal Sekolah</p>
                <p class="text-on-surface font-semibold">{{ $user->asal_sekolah ?? '—' }}</p>
            </div>
            <div>
                <p class="text-label-md text-outline uppercase mb-xs">Jurusan</p>
                <p class="text-on-surface font-semibold">{{ $user->jurusan ?? '—' }}</p>
            </div>
            <div>
                <p class="text-label-md text-outline uppercase mb-xs">NIS</p>
                <p class="text-on-surface font-semibold">{{ $user->nis_nip ?? '—' }}</p>
            </div>
            <div>
                <p class="text-label-md text-outline uppercase mb-xs">Mulai Magang</p>
                <p class="text-on-surface font-semibold">
                    {{ $user->tanggal_mulai?->translatedFormat('d M Y') ?? '—' }}
                </p>
            </div>
            <div>
                <p class="text-label-md text-outline uppercase mb-xs">Selesai Magang</p>
                <p class="text-on-surface font-semibold">
                    {{ $user->tanggal_selesai?->translatedFormat('d M Y') ?? '—' }}
                </p>
            </div>
            <div>
                <p class="text-label-md text-outline uppercase mb-xs">Sisa Hari</p>
                @php $sisa = $user->sisaHariMagang(); @endphp
                <p class="font-semibold {{ $sisa !== null && $sisa <= 7 ? 'text-error' : 'text-on-surface' }}">
                    {{ $sisa !== null ? $sisa . ' hari' : '—' }}
                </p>
            </div>
        </div>

        @if($user->tanggal_mulai && $user->tanggal_selesai)
        @php $progress = $user->progressMagang(); @endphp
        <div class="mt-md pt-md border-t border-outline-variant">
            <div class="flex justify-between text-label-md text-on-surface-variant mb-xs">
                <span>Progress Magang</span>
                <span class="font-semibold text-primary">{{ $progress }}%</span>
            </div>
            <div class="w-full bg-surface-container rounded-full h-2">
                <div class="bg-primary h-2 rounded-full transition-all duration-700"
                     style="width: {{ $progress }}%"></div>
            </div>
        </div>
        @endif

        <p class="text-label-md text-outline mt-md">
            * Data magang hanya dapat diubah oleh admin.
        </p>
    </div>
    @endif

    {{-- Logout --}}
    <div class="bg-surface-container-lowest border border-outline-variant rounded-xl p-md shadow-sm">
        <p class="text-label-md text-on-surface-variant uppercase tracking-wider mb-md">Sesi</p>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="w-full h-touch-target border border-error text-error rounded-lg font-semibold
                           flex items-center justify-center gap-sm hover:bg-error-container/20
                           active:scale-95 transition-all">
                <span class="material-symbols-outlined text-[18px]">logout</span>
                Keluar dari Akun
            </button>
        </form>
    </div>

</div>
@endsection

@section('bottom-nav')
    <x-bottom-nav active="profile" :role="auth()->user()->role" />
@endsection
