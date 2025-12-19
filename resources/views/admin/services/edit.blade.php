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
                    <div class="flex gap-2">
                        <select id="stripe_price_select" 
                            class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">-- Select a Stripe Price --</option>
                        </select>
                        <button type="button" id="refreshStripePrices" 
                            class="px-4 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition duration-200"
                            title="Refresh prices from Stripe">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                            </svg>
                        </button>
                    </div>
                    <input type="hidden" id="stripe_price_id" name="stripe_price_id" value="{{ old('stripe_price_id', $service->stripe_price_id) }}">
                    <div id="stripe_price_info" class="mt-2 text-sm text-green-600 {{ $service->stripe_price_id ? '' : 'hidden' }}">
                        @if($service->stripe_price_id)
                            Current: {{ $service->stripe_price_id }}
                        @endif
                    </div>
                    @error('stripe_price_id')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Select a price from your Stripe account or leave empty if using plans only.</p>
                </div>

                <!-- Payment Type -->
                <div class="mb-6">
                    <label for="payment_type" class="block text-sm font-medium text-gray-700 mb-2">
                        Payment Type
                    </label>
                    <select id="payment_type" name="payment_type"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('payment_type') border-red-500 @enderror">
                        <option value="">Auto-detect from Stripe (default)</option>
                        <option value="subscription" {{ old('payment_type', $service->payment_type) === 'subscription' ? 'selected' : '' }}>Subscription (Recurring)</option>
                        <option value="one_time" {{ old('payment_type', $service->payment_type) === 'one_time' ? 'selected' : '' }}>One-Time Payment</option>
                    </select>
                    @error('payment_type')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Explicitly set payment type, or leave empty to auto-detect from Stripe price type</p>
                </div>

                 <!-- Service Plans Section -->
                 <div class="border-t pt-6 mb-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Service Plans</h3>
                    <p class="text-sm text-gray-500 mb-4">Connect each plan with a Stripe price for payment processing.</p>
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
                        <div class="plan-item mb-6 p-4 border border-gray-200 rounded-lg bg-gray-50">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="font-medium text-gray-700">Plan #{{$planIndex}}</h4>
                                <button type="button" class="removePlanBtn text-red-500 hover:text-red-700 text-xl font-bold">&times;</button>
                            </div>
                            @if(isset($plan['id']))
                                <input type="hidden" name="plans[{{$planIndex}}][id]" value="{{ $plan['id'] }}">
                            @endif
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Plan Name *</label>
                                    <input type="text" name="plans[{{$planIndex}}][name]" value="{{ $plan['name'] ?? '' }}" placeholder="e.g., Basic, Pro, Enterprise" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Price *</label>
                                    <input type="number" name="plans[{{$planIndex}}][price]" value="{{ $plan['price'] ?? '' }}" placeholder="e.g., 29.99" step="0.01" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Billing Period</label>
                                    <select name="plans[{{$planIndex}}][period]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">Select period</option>
                                        <option value="month" {{ ($plan['period'] ?? '') === 'month' ? 'selected' : '' }}>Monthly</option>
                                        <option value="year" {{ ($plan['period'] ?? '') === 'year' ? 'selected' : '' }}>Yearly</option>
                                        <option value="week" {{ ($plan['period'] ?? '') === 'week' ? 'selected' : '' }}>Weekly</option>
                                        <option value="one-time" {{ ($plan['period'] ?? '') === 'one-time' ? 'selected' : '' }}>One-time</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-600 mb-1">Stripe Price</label>
                                    <div class="stripe-price-wrapper space-y-2">
                                        <select class="plan-stripe-price-select w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" data-current-value="{{ $plan['stripe_price_id'] ?? $plan['stripePriceId'] ?? '' }}">
                                            <option value="">-- Select Stripe Price --</option>
                                        </select>
                                        <input type="text" name="plans[{{$planIndex}}][stripePriceId]" value="{{ $plan['stripe_price_id'] ?? $plan['stripePriceId'] ?? '' }}" placeholder="Or enter Price ID (e.g., price_1ABC...)" class="plan-stripe-price-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                                    </div>
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-600 mb-1">Features (comma-separated)</label>
                                <input type="text" name="plans[{{$planIndex}}][features]" value="{{ is_array($plan['features'] ?? null) ? implode(',', $plan['features']) : ($plan['features'] ?? '') }}" placeholder="e.g., 10GB Storage, 24/7 Support, API Access" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
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
        let stripePrices = [];
        const addPlanBtn = document.getElementById("addPlanBtn");
        const plansContainer = document.getElementById("plansContainer");
        const stripePriceSelect = document.getElementById("stripe_price_select");
        const stripePriceIdInput = document.getElementById("stripe_price_id");
        const stripePriceInfo = document.getElementById("stripe_price_info");
        const refreshBtn = document.getElementById("refreshStripePrices");

        // Load Stripe prices on page load
        document.addEventListener("DOMContentLoaded", loadStripePrices);
        refreshBtn.addEventListener("click", loadStripePrices);

        // Handle service-level Stripe price selection
        stripePriceSelect.addEventListener("change", function() {
            stripePriceIdInput.value = this.value;
            if (this.value) {
                const selectedPrice = stripePrices.find(p => p.id === this.value);
                if (selectedPrice) {
                    stripePriceInfo.textContent = `Selected: ${selectedPrice.display_name}`;
                    stripePriceInfo.classList.remove("hidden");
                }
            } else {
                stripePriceInfo.classList.add("hidden");
            }
        });

        async function loadStripePrices() {
            refreshBtn.disabled = true;
            refreshBtn.innerHTML = '<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
            
            try {
                const response = await fetch("{{ route('admin.stripe.prices') }}");
                const data = await response.json();
                
                if (data.success) {
                    stripePrices = data.prices;
                    updatePriceDropdowns();
                } else {
                    console.error("Failed to load Stripe prices:", data.message);
                    alert("Failed to load Stripe prices. Please check your Stripe API key.");
                }
            } catch (error) {
                console.error("Error loading Stripe prices:", error);
                alert("Error loading Stripe prices. Please try again.");
            } finally {
                refreshBtn.disabled = false;
                refreshBtn.innerHTML = '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>';
            }
        }

        function updatePriceDropdowns() {
            // Update service-level dropdown
            const currentServiceValue = stripePriceIdInput.value;
            stripePriceSelect.innerHTML = '<option value="">-- Select a Stripe Price --</option>';
            stripePrices.forEach(price => {
                const option = document.createElement("option");
                option.value = price.id;
                option.textContent = price.display_name;
                if (price.id === currentServiceValue) {
                    option.selected = true;
                }
                stripePriceSelect.appendChild(option);
            });

            // Update all plan dropdowns
            document.querySelectorAll(".plan-stripe-price-select").forEach(select => {
                const wrapper = select.closest('.stripe-price-wrapper');
                const textInput = wrapper ? wrapper.querySelector('.plan-stripe-price-input') : null;
                const currentValue = textInput ? textInput.value : select.dataset.currentValue || '';
                
                select.innerHTML = '<option value="">-- Select Stripe Price --</option>';
                stripePrices.forEach(price => {
                    const option = document.createElement("option");
                    option.value = price.id;
                    option.textContent = price.display_name;
                    if (price.id === currentValue) {
                        option.selected = true;
                    }
                    select.appendChild(option);
                });
            });

            // Bind change events for existing plan dropdowns
            bindPlanDropdownEvents();
        }

        function bindPlanDropdownEvents() {
            document.querySelectorAll(".stripe-price-wrapper").forEach(wrapper => {
                const select = wrapper.querySelector(".plan-stripe-price-select");
                const input = wrapper.querySelector(".plan-stripe-price-input");
                
                if (!select || !input) return;
                
                // Remove existing event listeners by cloning
                const newSelect = select.cloneNode(true);
                select.parentNode.replaceChild(newSelect, select);
                
                // Sync dropdown to text input
                newSelect.addEventListener("change", function() {
                    input.value = this.value;
                });
                
                // Sync text input to dropdown (if value matches an option)
                input.addEventListener("input", function() {
                    const value = this.value.trim();
                    const option = Array.from(newSelect.options).find(opt => opt.value === value);
                    if (option) {
                        newSelect.value = value;
                    } else {
                        newSelect.value = ""; // Reset dropdown if manual entry doesn't match
                    }
                });
            });
        }

        function getPriceOptionsHtml(selectedValue = '') {
            let html = '<option value="">-- Select Stripe Price --</option>';
            stripePrices.forEach(price => {
                const selected = price.id === selectedValue ? 'selected' : '';
                html += `<option value="${price.id}" ${selected}>${price.display_name}</option>`;
            });
            return html;
        }
    
        addPlanBtn.addEventListener("click", () => {
            addPlanFields();
        });

        function addPlanFields(existingData = null) {
            const planFields = document.createElement("div");
            planFields.classList.add("plan-item", "mb-6", "p-4", "border", "border-gray-200", "rounded-lg", "bg-gray-50");
            
            const stripePriceValue = existingData?.stripePriceId || '';
            
            planFields.innerHTML = `
                <div class="flex justify-between items-center mb-4">
                    <h4 class="font-medium text-gray-700">Plan #${planIndex}</h4>
                    <button type="button" class="removePlanBtn text-red-500 hover:text-red-700 text-xl font-bold">&times;</button>
                </div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Plan Name *</label>
                        <input type="text" name="plans[${planIndex}][name]" value="${existingData?.name || ''}" placeholder="e.g., Basic, Pro, Enterprise" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Price *</label>
                        <input type="number" name="plans[${planIndex}][price]" value="${existingData?.price || ''}" placeholder="e.g., 29.99" step="0.01" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Billing Period</label>
                        <select name="plans[${planIndex}][period]" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            <option value="">Select period</option>
                            <option value="month" ${existingData?.period === 'month' ? 'selected' : ''}>Monthly</option>
                            <option value="year" ${existingData?.period === 'year' ? 'selected' : ''}>Yearly</option>
                            <option value="week" ${existingData?.period === 'week' ? 'selected' : ''}>Weekly</option>
                            <option value="one-time" ${existingData?.period === 'one-time' ? 'selected' : ''}>One-time</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Stripe Price</label>
                        <div class="stripe-price-wrapper space-y-2">
                            <select class="plan-stripe-price-select w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                ${getPriceOptionsHtml(stripePriceValue)}
                            </select>
                            <input type="text" name="plans[${planIndex}][stripePriceId]" value="${stripePriceValue}" placeholder="Or enter Price ID (e.g., price_1ABC...)" class="plan-stripe-price-input w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-600 mb-1">Features (comma-separated)</label>
                    <input type="text" name="plans[${planIndex}][features]" value="${existingData?.features || ''}" placeholder="e.g., 10GB Storage, 24/7 Support, API Access" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
            `;
            
            plansContainer.appendChild(planFields);
            
            // Handle remove button
            planFields.querySelector(".removePlanBtn").addEventListener("click", () => {
                planFields.remove();
            });

            // Handle Stripe price selection for this plan
            const priceSelect = planFields.querySelector(".plan-stripe-price-select");
            const priceInput = planFields.querySelector(".plan-stripe-price-input");
            
            // Sync dropdown to text input
            priceSelect.addEventListener("change", function() {
                priceInput.value = this.value;
            });
            
            // Sync text input to dropdown (if value matches an option)
            priceInput.addEventListener("input", function() {
                const value = this.value.trim();
                const option = Array.from(priceSelect.options).find(opt => opt.value === value);
                if (option) {
                    priceSelect.value = value;
                } else {
                    priceSelect.value = ""; // Reset dropdown if manual entry doesn't match
                }
            });
            
            planIndex++;
        }

        // Bind remove button events for existing plans
        document.querySelectorAll(".removePlanBtn").forEach(btn => {
            btn.addEventListener("click", (e) => btn.closest(".plan-item").remove());
        });
    </script>
</x-layout>
