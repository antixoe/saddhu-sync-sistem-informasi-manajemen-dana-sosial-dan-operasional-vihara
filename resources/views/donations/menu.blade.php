@extends('layouts.app')

@section('title', 'Donation Menu')
@section('header', 'Support the Vihara')
@section('subtitle', 'Choose how you would like to contribute to our sacred community')

@section('content')
    <div class="max-w-4xl mx-auto space-y-8">
        {{-- Donation Categories Menu --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($fundCategories as $category)
                <div class="card-spiritual p-6 text-center hover:shadow-lg transition-shadow">
                    <div class="mb-4">
                        <i class="fas fa-{{ $category->icon ?? 'hand-holding-heart' }} text-4xl text-saffron"></i>
                    </div>
                    <h3 class="font-semibold text-lg mb-2 text-deep-brown">{{ $category->name }}</h3>
                    <p class="text-gray-600 text-sm mb-4">{{ $category->description ?? 'Support this important fund' }}</p>
                    <button onclick="openDonationModal({{ $category->id }}, '{{ $category->name }}')"
                            class="w-full px-4 py-2 bg-saffron text-white rounded-lg hover:bg-saffron/90 transition-colors">
                        <i class="fas fa-donate mr-2"></i>Donate Now
                    </button>
                </div>
            @endforeach
        </div>

        {{-- General Donation Option --}}
        <div class="card-spiritual p-6 text-center">
            <div class="mb-4">
                <i class="fas fa-heart text-4xl text-saffron"></i>
            </div>
            <h3 class="font-semibold text-lg mb-2 text-deep-brown">General Donation</h3>
            <p class="text-gray-600 text-sm mb-4">Support the vihara in any way you can</p>
            <button onclick="openDonationModal(null, 'General Donation')"
                    class="px-6 py-2 bg-saffron text-white rounded-lg hover:bg-saffron/90 transition-colors">
                <i class="fas fa-donate mr-2"></i>Donate Now
            </button>
        </div>
    </div>

    {{-- Donation Modal --}}
    <div id="donationModal" class="fixed inset-0 bg-black bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen p-4">
            <div class="bg-white rounded-lg max-w-lg w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-deep-brown" id="modalTitle">Make a Donation</h3>
                        <button onclick="closeDonationModal()" class="text-gray-400 hover:text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    {{-- Payment Methods Selection --}}
                    <div class="mb-6">
                        <h4 class="font-semibold mb-3">Choose Payment Method</h4>
                        <div class="grid grid-cols-2 gap-3">
                            <button onclick="selectPaymentMethod('qris')" id="qris-btn" class="payment-method-btn p-3 border-2 border-gray-200 rounded-lg text-center hover:border-saffron transition-colors">
                                <i class="fas fa-mobile-alt text-2xl text-saffron mb-2"></i>
                                <p class="font-medium text-sm">QRIS</p>
                            </button>
                            <button onclick="selectPaymentMethod('bank')" id="bank-btn" class="payment-method-btn p-3 border-2 border-gray-200 rounded-lg text-center hover:border-saffron transition-colors">
                                <i class="fas fa-university text-2xl text-saffron mb-2"></i>
                                <p class="font-medium text-sm">Bank Transfer</p>
                            </button>
                            <button onclick="selectPaymentMethod('virtual')" id="virtual-btn" class="payment-method-btn p-3 border-2 border-gray-200 rounded-lg text-center hover:border-saffron transition-colors">
                                <i class="fas fa-credit-card text-2xl text-saffron mb-2"></i>
                                <p class="font-medium text-sm">Virtual Account</p>
                            </button>
                            <button onclick="selectPaymentMethod('cash')" id="cash-btn" class="payment-method-btn p-3 border-2 border-gray-200 rounded-lg text-center hover:border-saffron transition-colors">
                                <i class="fas fa-money-bill-wave text-2xl text-saffron mb-2"></i>
                                <p class="font-medium text-sm">Cash</p>
                            </button>
                        </div>
                    </div>

                    {{-- Payment Details Section --}}
                    <div id="payment-details" class="mb-6 hidden">
                        {{-- QRIS Details --}}
                        <div id="qris-details" class="payment-detail hidden">
                            <h4 class="font-semibold mb-3 text-center">QRIS Payment</h4>
                            <div class="text-center">
                                @if($qrCode)
                                    <img src="{{ $qrCode }}" alt="Donation QR code" class="mx-auto max-h-64 mb-3">
                                @else
                                    <div class="w-48 h-48 bg-gray-100 border-2 border-dashed border-gray-300 flex items-center justify-center text-gray-500 mx-auto mb-3">
                                        <div class="text-center">
                                            <i class="fas fa-qrcode text-4xl mb-2"></i>
                                            <p class="text-sm">QR Code</p>
                                            <p class="text-xs">Not configured</p>
                                        </div>
                                    </div>
                                @endif
                                <p class="text-sm text-gray-600">Scan with any QRIS-compatible payment app</p>
                            </div>
                        </div>

                        {{-- Bank Transfer Details --}}
                        <div id="bank-details" class="payment-detail hidden">
                            <h4 class="font-semibold mb-4 flex items-center">
                                <i class="fas fa-university text-saffron mr-2"></i>
                                Bank Transfer Details
                            </h4>

                            @if($bankDetails)
                                <div class="space-y-4">
                                    {{-- Bank Account Information --}}
                                    <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                                        <h5 class="font-medium text-blue-900 mb-2 flex items-center">
                                            <i class="fas fa-info-circle mr-2"></i>
                                            Account Information
                                        </h5>
                                        <div class="bg-white p-3 rounded border text-sm">
                                            <p class="whitespace-pre-line text-gray-800">{!! nl2br(e($bankDetails)) !!}</p>
                                        </div>
                                    </div>

                                    {{-- Transfer Instructions --}}
                                    <div class="bg-green-50 border border-green-200 p-4 rounded-lg">
                                        <h5 class="font-medium text-green-900 mb-3 flex items-center">
                                            <i class="fas fa-list-check mr-2"></i>
                                            How to Transfer
                                        </h5>
                                        <div class="space-y-2 text-sm text-green-800">
                                            <div class="flex items-start">
                                                <span class="bg-green-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">1</span>
                                                <p>Open your banking app or visit your bank</p>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="bg-green-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">2</span>
                                                <p>Select "Transfer" or "Send Money"</p>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="bg-green-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">3</span>
                                                <p>Enter the account number and bank details above</p>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="bg-green-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">4</span>
                                                <p>Enter the donation amount</p>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="bg-green-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">5</span>
                                                <p>Add a note: "Donation - [Your Name]"</p>
                                            </div>
                                            <div class="flex items-start">
                                                <span class="bg-green-600 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs font-bold mr-3 mt-0.5">6</span>
                                                <p>Complete the transfer and save the transaction receipt</p>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Important Notes --}}
                                    <div class="bg-amber-50 border border-amber-200 p-4 rounded-lg">
                                        <h5 class="font-medium text-amber-900 mb-3 flex items-center">
                                            <i class="fas fa-exclamation-triangle mr-2"></i>
                                            Important Notes
                                        </h5>
                                        <ul class="space-y-1 text-sm text-amber-800">
                                            <li class="flex items-start">
                                                <i class="fas fa-check-circle text-amber-600 mr-2 mt-0.5"></i>
                                                <span>Please use your full name as sender for verification</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check-circle text-amber-600 mr-2 mt-0.5"></i>
                                                <span>Keep the transaction receipt for your records</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check-circle text-amber-600 mr-2 mt-0.5"></i>
                                                <span>Transfers are usually processed within 1-2 business days</span>
                                            </li>
                                            <li class="flex items-start">
                                                <i class="fas fa-check-circle text-amber-600 mr-2 mt-0.5"></i>
                                                <span>For large amounts, consider using RTGS or instant transfer if available</span>
                                            </li>
                                        </ul>
                                    </div>

                                    {{-- Contact Information --}}
                                    <div class="bg-gray-50 border border-gray-200 p-4 rounded-lg">
                                        <h5 class="font-medium text-gray-900 mb-2 flex items-center">
                                            <i class="fas fa-phone mr-2"></i>
                                            Need Help?
                                        </h5>
                                        <p class="text-sm text-gray-700">
                                            If you encounter any issues with the transfer, please contact the vihara administration
                                            or visit us during operating hours.
                                        </p>
                                    </div>
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                                    <div class="flex items-start">
                                        <i class="fas fa-exclamation-triangle text-yellow-600 mr-3 mt-1"></i>
                                        <div>
                                            <p class="text-sm font-medium text-yellow-800">Bank details not configured</p>
                                            <p class="text-sm text-yellow-700 mt-1">Please contact the vihara administration for bank transfer information.</p>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Virtual Account Details --}}
                        <div id="virtual-details" class="payment-detail hidden">
                            <h4 class="font-semibold mb-3">Virtual Account Details</h4>
                            @if($virtualAccounts)
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <p class="whitespace-pre-line text-sm">{!! nl2br(e($virtualAccounts)) !!}</p>
                                </div>
                            @else
                                <div class="bg-yellow-50 border border-yellow-200 p-4 rounded-lg">
                                    <p class="text-sm text-yellow-800">Virtual account details not configured. Please contact the vihara administration.</p>
                                </div>
                            @endif
                        </div>

                        {{-- Cash Details --}}
                        <div id="cash-details" class="payment-detail hidden">
                            <h4 class="font-semibold mb-3">Cash Donation</h4>
                            <div class="bg-blue-50 border border-blue-200 p-4 rounded-lg">
                                <div class="flex items-start space-x-3">
                                    <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                                    <div>
                                        <p class="text-sm text-blue-800 font-medium mb-2">Visit the Vihara</p>
                                        <p class="text-sm text-blue-700">Please visit the vihara during operating hours to make a cash donation. Our staff will be happy to assist you with the donation process.</p>
                                        <div class="mt-3 text-xs text-blue-600">
                                            <p><strong>Operating Hours:</strong></p>
                                            <p>Morning: 6:00 AM - 12:00 PM</p>
                                            <p>Evening: 4:00 PM - 8:00 PM</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Action Buttons --}}
                    <div class="flex space-x-3">
                        <a id="complete-donation-btn" href="{{ route('donate') }}" class="flex-1 px-4 py-2 bg-saffron text-white text-center rounded-lg hover:bg-saffron/90 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                            Complete Donation
                        </a>
                        <button onclick="closeDonationModal()" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedCategoryId = null;
        let selectedCategoryName = '';
        let selectedPaymentMethod = null;

        function openDonationModal(categoryId, categoryName) {
            selectedCategoryId = categoryId;
            selectedCategoryName = categoryName;
            selectedPaymentMethod = null;
            document.getElementById('modalTitle').textContent = `Donate to ${categoryName}`;
            document.getElementById('donationModal').classList.remove('hidden');
            resetPaymentMethods();
            document.getElementById('payment-details').classList.add('hidden');
            updateCompleteButton();
        }

        function closeDonationModal() {
            document.getElementById('donationModal').classList.add('hidden');
            selectedCategoryId = null;
            selectedCategoryName = '';
            selectedPaymentMethod = null;
            resetPaymentMethods();
        }

        function selectPaymentMethod(method) {
            selectedPaymentMethod = method;

            // Reset all buttons
            document.querySelectorAll('.payment-method-btn').forEach(btn => {
                btn.classList.remove('border-saffron', 'bg-saffron', 'bg-opacity-10');
                btn.classList.add('border-gray-200');
            });

            // Highlight selected button
            const selectedBtn = document.getElementById(method + '-btn');
            selectedBtn.classList.remove('border-gray-200');
            selectedBtn.classList.add('border-saffron', 'bg-saffron', 'bg-opacity-10');

            // Hide all payment details
            document.querySelectorAll('.payment-detail').forEach(detail => {
                detail.classList.add('hidden');
            });

            // Show selected payment details
            document.getElementById(method + '-details').classList.remove('hidden');
            document.getElementById('payment-details').classList.remove('hidden');

            updateCompleteButton();
        }

        function resetPaymentMethods() {
            document.querySelectorAll('.payment-method-btn').forEach(btn => {
                btn.classList.remove('border-saffron', 'bg-saffron', 'bg-opacity-10');
                btn.classList.add('border-gray-200');
            });
            selectedPaymentMethod = null;
        }

        function updateCompleteButton() {
            const completeBtn = document.getElementById('complete-donation-btn');

            if (selectedPaymentMethod) {
                // Build URL with parameters
                let url = '{{ route("donate") }}';
                const params = new URLSearchParams();
                
                if (selectedCategoryId) {
                    params.append('fund_category_id', selectedCategoryId);
                }
                if (selectedPaymentMethod) {
                    params.append('donation_method', selectedPaymentMethod);
                }
                
                const paramString = params.toString();
                if (paramString) {
                    url += '?' + paramString;
                }
                
                completeBtn.href = url;
                completeBtn.classList.remove('disabled', 'opacity-50', 'cursor-not-allowed');
                completeBtn.style.pointerEvents = 'auto';
            } else {
                completeBtn.href = '#';
                completeBtn.classList.add('disabled', 'opacity-50', 'cursor-not-allowed');
                completeBtn.style.pointerEvents = 'none';
            }
        }

        // Close modal when clicking outside
        document.getElementById('donationModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeDonationModal();
            }
        });
    </script>
@endsection