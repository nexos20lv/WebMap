@extends('layouts.app')

@section('title', trans('webmap::messages.title'))

@push('styles')
    <style>
        .webmap-container {
            width: 100%;
            height: calc(100vh - 120px);
            border: 0;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            background: #111;
        }
        .webmap-error {
            text-align: center;
            padding: 60px 20px;
        }
        .webmap-error .map-icon {
            font-size: 5rem;
            margin-bottom: 20px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid px-3" style="max-width: 98%;">
        @if($url)
            <div class="card shadow border-0">
                <div class="card-body p-0">
                    {{-- Use the PHP proxy to bypass mixed-content restrictions --}}
                    <iframe src="{{ route('webmap.proxy', '') }}"
                            class="webmap-container"
                            allowfullscreen
                            loading="lazy">
                    </iframe>
                </div>
            </div>
        @else
            <div class="card shadow border-0">
                <div class="card-body webmap-error">
                    <div class="map-icon">🗺️</div>
                    <h2 class="mb-3">{{ trans('webmap::messages.title') }}</h2>
                    <p class="text-muted mb-4">
                        Aucune URL de carte dynamique n'est configurée.<br>
                        Rendez-vous dans l'espace administrateur pour configurer l'URL.
                    </p>
                    @can('admin.settings')
                        <a href="{{ route('webmap.admin.settings') }}" class="btn btn-primary">
                            <i class="bi bi-gear me-2"></i> Configurer
                        </a>
                    @endcan
                </div>
            </div>
        @endif
    </div>
@endsection