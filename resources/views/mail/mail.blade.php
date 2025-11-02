<x-layout>

    <x-slot:title>
        Email - From Us
    </x-slot:title>
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
</x-layout>
