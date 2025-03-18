@extends('layouts/contentNavbarLayout')

@section('title', 'Join Meeting')

@section('content')
    <h1 class="mb-4">Meeting Room: {{ $room }}</h1>

    <!-- Join Meeting Button -->
    <a href="{{ $meetingLink }}" target="_blank" class="btn btn-primary">
        Join Meeting
    </a>

    {{-- <div id="jitsi-container" style="height: 600px;"></div> --}}

    <script src="https://meet.jit.si/external_api.js"></script>

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

        // Add conference terminated event listener
        api.addEventListener("conferenceTerminated", function(event) {
            console.log("Meeting Ended:", event);
            setTimeout(function() {
                window.close();
                window.location.href = "{{ route('agent.meetings') }}";
            }, 1000); // 1-second delay
        });

        // Add conference failed event listener
        api.addEventListener("conferenceFailed", function(event) {
            console.log("Conference Failed:", event);
            if (event?.error === "conference.destroyed") {
                console.log("Meeting was terminated by the host.");
                window.location.href = "{{ route('agent.meetings') }}"; // Redirect when the meeting is destroyed
            } else {
                console.log("Other conference failure:", event);
            }
        });
        // Additional logging for API initialization
        console.log("Jitsi API initialized:", api);
    </script>
@endsection
