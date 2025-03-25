@extends('layouts/contentNavbarLayout')

@section('title', 'Join Meeting')

@section('content')
    <h1 class="mb-4">Meeting Room: {{ $room }}</h1>

    <div id="jitsi-container" style="height: 600px;"></div>

    <script src="https://syncora.duckdns.org/external_api.js"></script>

    <script>
        const domain = "syncora.duckdns.org";
        const urlParams = new URLSearchParams(window.location.search);
        const jwtToken = urlParams.get('token'); // Get the JWT token from the URL
        const roomName = "{{ $room }}";
        const options = {
            roomName: roomName,
            jwt: jwtToken,
            width: "100%",
            height: 600,
            parentNode: document.getElementById("jitsi-container"),
            userInfo: {
                displayName: "{{ auth()->user()->name ?? 'Guest' }}"
            },
            configOverwrite: {
                prejoinPageEnabled: true,
                hosts: {
                    domain: "syncora.duckdns.org",
                    guest: "guest.syncora.duckdns.org"
                }
            }
        };

        const api = new JitsiMeetExternalAPI(domain, options);
        console.log("Jitsi API initialized:", api);
    </script>
@endsection
