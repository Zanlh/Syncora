@extends('layouts/contentNavbarLayout')

@section('title', 'Join Meeting')

@section('content')
    <h1 class="mb-4">Meeting Room: {{ $room }}</h1>

    <!-- Join Meeting Button -->
    <a href="{{ $meetingLink }}" target="_blank" class="btn btn-primary">
        Join Meeting
    </a>

    {{-- <div id="jitsi-container" style="height: 600px;"></div> --}}

    <script src="https://localhost:8443/external_api.js"></script> <!-- Use self-hosted Jitsi -->
    <script>
        const domain = "localhost:8443";
        const options = {
            roomName: "{{ $room }}",
            width: "100%",
            height: 600,
            parentNode: document.getElementById("jitsi-container"),
            userInfo: {
                displayName: "{{ auth()->user()->name ?? 'Guest' }}"
            },
            configOverwrite: {
                prejoinPageEnabled: true,
            }
        };
        const api = new JitsiMeetExternalAPI(domain, options);
    </script>
@endsection
