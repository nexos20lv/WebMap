@extends('admin.layouts.admin')

@section('title', trans('webmap::admin.nav.title'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-body">
            <form action="{{ route('webmap.admin.settings.save') }}" method="POST">
                @csrf

                <div class="form-group mb-3">
                    <label for="urlInput">{{ trans('webmap::admin.settings.url_label') }}</label>
                    <input type="url" class="form-control" id="urlInput" name="url" value="{{ old('url', $url) }}" required
                        placeholder="Ex: http://play.nexaria.fr:8123/">
                    <small class="form-text text-muted">{{ trans('webmap::admin.settings.url_help') }}</small>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-save"></i> {{ trans('messages.actions.save') }}
                </button>
            </form>
        </div>
    </div>
@endsection