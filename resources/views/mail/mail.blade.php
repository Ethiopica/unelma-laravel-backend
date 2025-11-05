<x-layout>

    <x-slot:title>
        Email - From Us
    </x-slot:title>
    
    @isset($contactMessage)
        {{-- Contact Form Submission Email --}}
        <div class="card text-bg-dark bg border border-3 border-danger d-flex justify-content-center align-items-center">
            <img src="/images/image.png" class="card-img" alt="Image Placeholder" width="400px">
            <div class="card-img-overlay">
                <h5 class="card-title">New Contact Form Submission</h5>
                <p class="card-text"><strong>Name:</strong> {{ $contactMessage->name }}</p>
                <p class="card-text"><strong>Email:</strong> {{ $contactMessage->email }}</p>
                <p class="card-text"><strong>Submitted:</strong> {{ $contactMessage->created_at->format('F j, Y \a\t g:i A') }}</p>
                <p class="card-text"><small>{{ $contactMessage->ip_address ?? 'N/A' }}</small></p>
            </div>
            <div class="card-body text-white">
                <h6 class="card-title">Message:</h6>
                <p class="card-text text-success">{{ $contactMessage->message }}</p>
                <a href="{{ url('/admin/contact-messages') }}" class="btn btn-primary">View in Admin Panel</a>
            </div>
        </div>
    @else
        {{-- Reply Message Email (existing) --}}
        <div class="card text-bg-dark bg border border-3 border-danger d-flex justify-content-center align-items-center">
            <img src="/images/image.png" class="card-img" alt="Image Placeholder" width="400px">
            <div class="card-img-overlay">
                <h5 class="card-title">Team Project from Group 3</h5>
                <p class="card-text">You might know this is the project from Team no 3.</p>
                <p class="card-text"><small>Can you please check queries in FAQ of our website</small></p>
            </div>
            <p class="text-success">
                @isset($reply)
                    {{ $reply }}
                @endisset
            </p>
        </div>
    @endisset
</x-layout>
