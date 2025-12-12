<x-layout>
    <x-slot:title>Edit Service - {{ config('app.name') }}</x-slot:title>
    
    <!-- Main Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-900">Dashboard</a>
                <span>/</span>
                <a href="{{ route('admin.services.index') }}" class="hover:text-gray-900">Services</a>
                <span>/</span>
                <span class="text-gray-900">Edit: {{ $service->name }}</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-800">Edit Service</h1>
            <p class="text-gray-600 mt-1">Update service information and settings</p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-md p-8">
            <form method="POST" action="{{ route('admin.services.update', $service) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Service Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                        Service Name <span class="text-red-500">*</span>
                    </label>
                    <input type="text" id="name" name="name" value="{{ old('name', $service->name) }}"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('name') border-red-500 @enderror"
                        placeholder="e.g., Cloud Server Pro">
                    @error('name')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Description -->
                <div class="mb-6">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea id="description" name="description" rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('description') border-red-500 @enderror"
                        placeholder="Describe your service features and benefits...">{{ old('description', $service->description) }}</textarea>
                    @error('description')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Icon -->
                <div class="mb-6">
                    <label for="icon" class="block text-sm font-medium text-gray-700 mb-2">
                        Icon (Emoji or Icon Class)
                    </label>
                    <input type="text" id="icon" name="icon" value="{{ old('icon', $service->icon) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('icon') border-red-500 @enderror"
                        placeholder="e.g., ☁️ or fa-cloud">
                    @error('icon')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">You can use emoji or icon class names (e.g., Font Awesome
                        classes)</p>
                </div>

                <!-- Service Image -->
                <div class="mb-6">
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-2">
                        Service Image
                    </label>

                    @if ($service->image)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}"
                                class="w-48 h-32 object-cover rounded border-2 border-gray-300">
                        </div>
                    @endif

                    <input type="file" id="image" name="image"
                        accept="image/jpeg,image/png,image/jpg,image/gif,image/webp"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image') border-red-500 @enderror">
                    @error('image')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">
                        {{ $service->image ? 'Upload a new image to replace the current one. ' : '' }}
                        Accepted formats: JPEG, PNG, GIF, WebP. Max size: 2MB
                    </p>
                </div>

                <!--Serive Image Url -->
                <div class="mb-6">
                    <label for="image_url" class="block text-sm font-medium text-gray-700 mb-2">
                       Or Image Url
                    </label>
                    @if ($service->image)
                        <div class="mb-4">
                            <p class="text-sm text-gray-600 mb-2">Current Image:</p>
                            <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}"
                                class="w-48 h-32 object-cover rounded border-2 border-gray-300">
                        </div>
                    @endif
                    <input type="text" id="image_url" name="image_url" value="{{ old('image_url',$service->image_url) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('image_url') border-red-500 @enderror"
                        placeholder="e.g., https://www.example.com/images/service1.jpg">
                    @error('image_url')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    
                </div>

                <!-- Stripe Price ID -->
                <div class="mb-6">
                    <label for="stripe_price_id" class="block text-sm font-medium text-gray-700 mb-2">
                        Stripe Price ID
                    </label>
                    <input type="text" id="stripe_price_id" name="stripe_price_id" value="{{ old('stripe_price_id', $service->stripe_price_id) }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('stripe_price_id') border-red-500 @enderror"
                        placeholder="e.g., price_1SZtv4Jg4Qxq8pC4yT9kDMrG">
                    @error('stripe_price_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Required for direct service checkout. Get this from your Stripe Dashboard → Products → Prices. Leave empty if using plans only.</p>
                </div>

                 <!-- Service Plans Section -->
                 <div class="border-t pt-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Service Plans</h3>
                    <div id="plansContainer" class="mt-6">
                  
                        @php 
                            $planIndex = 1;
                            $existingPlans = old('plans', []);
                            $plansCollection = $service->getPlansSafely();
                            if ($plansCollection && $plansCollection->count() > 0) {
                                $existingPlans = old('plans', $plansCollection->toArray());
                            }
                        @endphp
                        @foreach ($existingPlans as $plan )
                        <div class="plan-item mb-6">
                        <div class="flex justify-between items-center mb-2 mt-6">
                            <h4 class="font-medium text-gray-700">Plan #{{$planIndex}}</h4>
                            <button type="button" class="removePlanBtn text-red-500 hover:text-red-700">&times;</button>
                            </div>
                            @if(isset($plan['id']))
                                <input type="hidden" name="plans[{{$planIndex}}][id]" value="{{ $plan['id'] }}">
                            @endif
                            <input type="text" name="plans[{{$planIndex}}][name]" value="{{ $plan['name'] ?? '' }}" placeholder="Plan Name *" class="mb-3 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <input type="number" name="plans[{{$planIndex}}][price]" value="{{ $plan['price'] ?? '' }}" placeholder="Price *" class="mb-3 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <input type="text" name="plans[{{$planIndex}}][period]" value="{{ $plan['period'] ?? '' }}" placeholder="Period" class="mb-3 mb-3 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <input type="text" name="plans[{{$planIndex}}][stripePriceId]" value="{{ $plan['stripePriceId'] ?? ($plan['stripe_price_id'] ?? '') }}" placeholder="Stripe Price ID *" required class="mb-3 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <input type="text" name="plans[{{$planIndex}}][features]" value="{{ is_array($plan['features'] ?? null) ? implode(',', $plan['features']) : ($plan['features'] ?? '') }}" placeholder="Features (comma-separated)" class="mb-3 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>
                            @php $planIndex++; @endphp
                        @endforeach
                    
                    </div>
                    <button type="button" id="addPlanBtn"
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                        Add Plan
                    </button>
                </div>

                <!-- Settings Section -->
                <div class="border-t pt-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Display Settings</h3>

                    <!-- Display Order -->
                    <div class="mb-6">
                        <label for="order" class="block text-sm font-medium text-gray-700 mb-2">
                            Display Order
                        </label>
                        <input type="number" id="order" name="order" value="{{ old('order', $service->order) }}"
                            min="0"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('order') border-red-500 @enderror"
                            placeholder="0">
                        @error('order')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                        <p class="mt-1 text-sm text-gray-500">Lower numbers appear first (0 = highest priority)</p>
                    </div>

                    <!-- Checkboxes -->
                    <div class="space-y-4">
                        <!-- Featured -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" id="is_featured" name="is_featured" value="1"
                                    {{ old('is_featured', $service->is_featured) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </div>
                            <div class="ml-3">
                                <label for="is_featured" class="font-medium text-gray-700">
                                    Featured Service
                                </label>
                                <p class="text-sm text-gray-500">
                                    Display a featured badge on this service
                                </p>
                            </div>
                        </div>

                        <!-- Active Status -->
                        <div class="flex items-start">
                            <div class="flex items-center h-5">
                                <input type="checkbox" id="is_active" name="is_active" value="1"
                                    {{ old('is_active', $service->is_active) ? 'checked' : '' }}
                                    class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                            </div>
                            <div class="ml-3">
                                <label for="is_active" class="font-medium text-gray-700">
                                    Active
                                </label>
                                <p class="text-sm text-gray-500">
                                    Make this service visible on the website
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Service Info -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6">
                    <div class="grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <p class="text-gray-600">Created:</p>
                            <p class="font-medium text-gray-900">
                                {{ $service->created_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600">Last Updated:</p>
                            <p class="font-medium text-gray-900">
                                {{ $service->updated_at->format('M d, Y \a\t h:i A') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="flex items-center justify-between pt-6 border-t">
                    <a href="{{ route('admin.services.index') }}"
                        class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200">
                        Cancel
                    </a>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition duration-200 flex items-center space-x-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                            </path>
                        </svg>
                        <span>Update Service</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        let planIndex = {{ $planIndex }};
        const addPlanBtn = document.getElementById("addPlanBtn");
        const plansContainer = document.getElementById("plansContainer");
    
        addPlanBtn.addEventListener("click", () => {
            addPlanFields();
        });
        function addPlanFields() {
            const planFields = document.createElement("div");
            planFields.classList.add("plan-item", "mb-6");
            planFields.innerHTML = `
                <div class="flex justify-between items-center mb-2 mt-6">
                <h4 class="font-medium text-gray-700">Plan #${planIndex}</h4>
                <button type="button" class="removePlanBtn text-red-500 hover:text-red-700">&times;</button>
                </div>
                <input type="text" name="plans[${planIndex}][name]" placeholder="Plan Name *" class="mb-3 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <input type="number" name="plans[${planIndex}][price]" placeholder="Price *" class="mb-3 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" >
                <input type="text" name="plans[${planIndex}][period]" placeholder="Period" class="mb-3 mb-3 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <input type="text" name="plans[${planIndex}][stripePriceId]" placeholder="Stripe Price ID *" required class="mb-3 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                <input type="text" name="plans[${planIndex}][features]" placeholder="Features (comma-separated)" class="mb-3 w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
    
        `;
            plansContainer.appendChild(planFields);
    
            planFields.querySelector(".removePlanBtn").addEventListener("click", () => {
                planFields.remove();
                planIndex--;
            });
            planIndex++;
        }

        document.querySelectorAll(".removePlanBtn").forEach(btn => {
            btn.addEventListener("click", (e) => btn.closest(".plan-item").remove());
        });
    </script>
</x-layout>
