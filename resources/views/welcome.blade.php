@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <url-shortener-form></url-shortener-form>

                <url-table></url-table>
            </div>
        </div>
    </div>


@endsection

@push('before-js')

        <script>
            @if(session('google_auth'))
                window.google_auth = {!! json_encode(session('google_auth')) !!};
            @else
                window.google_auth = false;
            @endif
            window.auths = {!! json_encode($auths) !!};

            @if(\Cache::get('analytics_update') === null)
                window.last_update = 'Never';
            @else
                window.last_update = {!! json_encode(\Cache::get('analytics_update')->diffForHumans()) !!};
            @endif
        </script>

@endpush